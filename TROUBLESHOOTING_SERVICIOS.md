# Servicios en producción: por qué no funcionan y qué revisar

## 1. Ejecutar diagnóstico en el servidor

En la raíz del proyecto (ej. `/var/www/commuty-ed`):

```bash
# Como tu usuario
php diagnostico_produccion.php

# Como el usuario del servicio (recomendado)
sudo -u www-data php diagnostico_produccion.php
```

El script comprueba: config (DOMAIN, BD, RabbitMQ), PHP y extensiones, directorios escribibles, MySQL, RabbitMQ y archivos del servicio.

---

## 2. Causas frecuentes por las que el servicio no funciona

### A) DOMAIN en producción

En **producción**, `config/config.php` debe tener **DOMAIN** con tu URL real, no `http://localhost/...`.

- El sitio llama a `DOMAIN . "/producer_service.php"` para enviar videos a la cola.
- Si DOMAIN es localhost o está vacío, la petición al producer falla y el video nunca entra a RabbitMQ.

**Qué hacer:** En el servidor, edita `config/config.php` y define por ejemplo:

```php
define("DOMAIN", "https://tu-dominio.com");
```

(con o sin barra final, el código lo normaliza).

---

### B) Ruta del proyecto (WorkingDirectory)

Los systemd usan:

- `WorkingDirectory=/var/www/commuty-ed`
- `ExecStart=/bin/bash /var/www/commuty-ed/consumer_wrapper.sh`

Si en tu servidor el proyecto está en **otra ruta** (ej. `/var/www/html/commuty-ed`), el servicio no arranca bien.

**Qué hacer:** Editar los `.service` y el wrapper:

```bash
sudo nano /etc/systemd/system/commuty-consumer.service
# Cambiar WorkingDirectory y ExecStart a tu ruta real

sudo systemctl daemon-reload
sudo systemctl restart commuty-consumer
```

---

### C) RabbitMQ no está instalado o no corre

Si RabbitMQ no está levantado, el consumer no puede conectar y suele salir con error.

**Comprobar:**

```bash
sudo systemctl status rabbitmq-server
```

**Arrancar:**

```bash
sudo systemctl start rabbitmq-server
sudo systemctl enable rabbitmq-server
```

**Probar puerto:**

```bash
php test_rabbitmq_connection.php
```

---

### D) Permisos y usuario www-data

Los servicios corren como **User=www-data**. Si los directorios o archivos son de otro usuario y no escribibles, el consumer falla al crear videos/miniaturas o al escribir logs.

**Qué hacer:**

```bash
cd /var/www/commuty-ed   # o tu ruta

sudo mkdir -p uploads videos previa imagenes_tablero logs tmp
sudo chown -R www-data:www-data uploads videos previa imagenes_tablero logs tmp
sudo chmod -R 755 uploads videos previa imagenes_tablero logs tmp

sudo chmod +x consumer_wrapper.sh resultado_wrapper.sh
sudo chown www-data:www-data consumer_wrapper.sh resultado_wrapper.sh
```

---

### E) PHP o extensiones

El wrapper exige extensiones como **mysqli**, **mbstring**, **curl**, **json**. Si falta alguna, el script PHP del consumer falla al iniciar.

**Comprobar:**

```bash
php -m
```

**Instalar (ej. Ubuntu/Debian):**

```bash
sudo apt install php-mysql php-mbstring php-curl
# o el script del proyecto:
sudo bash systemd/fix_php_extensions.sh
```

---

### F) Logs del servicio

Para ver **por qué** está fallando:

```bash
# Estado e intentos de arranque
sudo systemctl status commuty-consumer
sudo systemctl status commuty-resultado

# Últimas líneas del journal (consumer)
sudo journalctl -u commuty-consumer -n 100 --no-pager

# Errores del consumer
sudo tail -100 /var/log/commuty/consumer-error.log

# Crear directorio de log si no existe
sudo mkdir -p /var/log/commuty
sudo chown www-data:www-data /var/log/commuty
```

Ahí suelen aparecer mensajes como “Connection refused” (RabbitMQ), “No such file or directory” (rutas), “Permission denied” (permisos) o errores de PHP.

---

### G) Config de producción

Debe existir **config/config.php** en el servidor (no solo `config.production.example.php`). Y debe tener:

- **DOMAIN** = URL pública del sitio (https://…).
- **HOST_BD**, **USER_BD**, **PASSWORD_BD**, **NAME_DB** correctos para ese servidor.
- **host_rabbit_mq**, **port_rabbit_mq**, **user_rabbit_mq**, **password_rabbit_mq** si RabbitMQ no está en localhost o usa otro usuario.

---

## 3. Checklist rápido

1. `php diagnostico_produccion.php` (y con `sudo -u www-data`) sin errores.
2. DOMAIN en `config/config.php` = URL real de producción.
3. RabbitMQ corriendo y `php test_rabbitmq_connection.php` OK.
4. Directorios `uploads`, `videos`, `previa`, `imagenes_tablero`, `logs`, `tmp` existen y son de `www-data` y escribibles.
5. `commuty-consumer.service` y `commuty-resultado.service` con **WorkingDirectory** y **ExecStart** apuntando a la ruta real del proyecto.
6. Revisar `journalctl` y `consumer-error.log` para el mensaje concreto del fallo.

Si después de esto el servicio sigue sin funcionar, el mensaje exacto que salga en **journalctl** o en **consumer-error.log** indica el siguiente paso (BD, RabbitMQ, PHP, permisos, etc.).
