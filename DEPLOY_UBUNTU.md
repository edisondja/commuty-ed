# Guía de Deploy en Ubuntu Server

## Requisitos del Servidor

### Software necesario
```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Apache, PHP y extensiones
sudo apt install -y apache2 php8.1 php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-gd php8.1-zip libapache2-mod-php8.1

# Instalar MySQL
sudo apt install -y mysql-server

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# (Opcional) Instalar FFmpeg para procesamiento de video
sudo apt install -y ffmpeg

# (Opcional) Instalar RabbitMQ
sudo apt install -y rabbitmq-server
sudo systemctl enable rabbitmq-server
sudo systemctl start rabbitmq-server

# (Opcional) Instalar Redis
sudo apt install -y redis-server
sudo systemctl enable redis-server
```

## Pasos de Instalación

### 1. Clonar el proyecto
```bash
cd /var/www/html
sudo git clone https://tu-repositorio.git commuty-ed
cd commuty-ed
```

### 2. Configurar permisos
```bash
# Propietario www-data
sudo chown -R www-data:www-data /var/www/html/commuty-ed

# Permisos de directorios
sudo chmod -R 755 /var/www/html/commuty-ed
sudo chmod -R 775 /var/www/html/commuty-ed/uploads
sudo chmod -R 775 /var/www/html/commuty-ed/videos
sudo chmod -R 775 /var/www/html/commuty-ed/previa
sudo chmod -R 775 /var/www/html/commuty-ed/imagenes_tablero
sudo chmod -R 775 /var/www/html/commuty-ed/compile
sudo chmod -R 775 /var/www/html/commuty-ed/traking
sudo chmod -R 775 /var/www/html/commuty-ed/temp_downloads

# Crear directorio de logs si no existe
sudo mkdir -p /var/www/html/commuty-ed/logs
sudo chmod 775 /var/www/html/commuty-ed/logs
```

### 3. Crear directorios necesarios
```bash
mkdir -p uploads videos previa imagenes_tablero compile traking temp_downloads logs
```

### 4. Instalar dependencias de Composer
```bash
cd /var/www/html/commuty-ed
sudo -u www-data composer install --no-dev --optimize-autoloader
```

### 5. Configurar la base de datos
```bash
# Acceder a MySQL
sudo mysql

# Crear base de datos y usuario
CREATE DATABASE edcommunity CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'commuty_user'@'localhost' IDENTIFIED BY 'TuPasswordSeguro123!';
GRANT ALL PRIVILEGES ON edcommunity.* TO 'commuty_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Importar estructura de base de datos
mysql -u commuty_user -p edcommunity < /var/www/html/commuty-ed/database/db.sql

# Ejecutar scripts adicionales
mysql -u commuty_user -p edcommunity < /var/www/html/commuty-ed/database/add_estilos_json.sql
mysql -u commuty_user -p edcommunity < /var/www/html/commuty-ed/database/add_ratings_table.sql
mysql -u commuty_user -p edcommunity < /var/www/html/commuty-ed/database/add_reproductores_vast.sql
mysql -u commuty_user -p edcommunity < /var/www/html/commuty-ed/database/add_reproductor_tablero.sql
```

### 6. Configurar el archivo config.php
```bash
cp config/config.example.php config/config.php
nano config/config.php
```

Modifica:
- `DOMAIN` → tu dominio con HTTPS
- `HOST_BD`, `USER_BD`, `PASSWORD_BD` → credenciales de MySQL
- `APP_ENV` → "production"

### 7. Configurar Apache Virtual Host
```bash
sudo nano /etc/apache2/sites-available/commuty-ed.conf
```

```apache
<VirtualHost *:80>
    ServerName tudominio.com
    ServerAlias www.tudominio.com
    DocumentRoot /var/www/html/commuty-ed

    <Directory /var/www/html/commuty-ed>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Seguridad: ocultar archivos sensibles
    <FilesMatch "(^\.git|config\.php|composer\.(json|lock))$">
        Require all denied
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/commuty-ed_error.log
    CustomLog ${APACHE_LOG_DIR}/commuty-ed_access.log combined
</VirtualHost>
```

```bash
# Habilitar el sitio y mod_rewrite
sudo a2ensite commuty-ed.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 8. Instalar certificado SSL (Let's Encrypt)
```bash
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d tudominio.com -d www.tudominio.com
```

### 9. (Opcional) Configurar Workers de RabbitMQ
```bash
# Crear servicio systemd para consumer_service
sudo nano /etc/systemd/system/commuty-consumer.service
```

```ini
[Unit]
Description=Commuty-ED Video Consumer Service
After=network.target rabbitmq-server.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/commuty-ed
ExecStart=/usr/bin/php /var/www/html/commuty-ed/consumer_service.php
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

```bash
# Crear servicio para consumer_resultado
sudo nano /etc/systemd/system/commuty-resultado.service
```

```ini
[Unit]
Description=Commuty-ED Result Consumer Service
After=network.target rabbitmq-server.service

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/commuty-ed
ExecStart=/usr/bin/php /var/www/html/commuty-ed/consumer_resultado.php
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

```bash
# Habilitar y arrancar servicios
sudo systemctl daemon-reload
sudo systemctl enable commuty-consumer commuty-resultado
sudo systemctl start commuty-consumer commuty-resultado
```

### 10. Configurar límites de subida de archivos

```bash
sudo nano /etc/php/8.1/apache2/php.ini
```

Modificar:
```ini
upload_max_filesize = 256M
post_max_size = 260M
max_execution_time = 300
memory_limit = 512M
```

```bash
sudo systemctl restart apache2
```

## Checklist de Seguridad

- [ ] APP_ENV = "production" en config.php
- [ ] Credenciales de MySQL seguras
- [ ] Credenciales de RabbitMQ cambiadas (no usar guest/guest)
- [ ] Certificado SSL instalado
- [ ] Permisos correctos en directorios
- [ ] Firewall configurado (ufw)
- [ ] Backups automáticos de base de datos
- [ ] Monitoreo de logs

## Comandos útiles

```bash
# Ver logs de Apache
sudo tail -f /var/log/apache2/commuty-ed_error.log

# Ver logs de PHP
sudo tail -f /var/www/html/commuty-ed/logs/php_errors.log

# Ver estado de consumers
sudo systemctl status commuty-consumer commuty-resultado

# Reiniciar consumers
sudo systemctl restart commuty-consumer commuty-resultado

# Verificar RabbitMQ
sudo rabbitmqctl list_queues
```

## Troubleshooting

### Error de conexión a MySQL
- Verificar que MySQL esté corriendo: `sudo systemctl status mysql`
- Verificar credenciales en config.php
- Probar conexión: `mysql -u commuty_user -p edcommunity`

### Error de permisos
```bash
sudo chown -R www-data:www-data /var/www/html/commuty-ed
sudo chmod -R 755 /var/www/html/commuty-ed
```

### Videos no se procesan
- Verificar FFmpeg: `which ffmpeg`
- Verificar RabbitMQ: `sudo systemctl status rabbitmq-server`
- Ver logs del consumer: `sudo journalctl -u commuty-consumer -f`

### Smarty no compila templates
```bash
sudo chmod -R 775 /var/www/html/commuty-ed/compile
sudo chown www-data:www-data /var/www/html/commuty-ed/compile
```
