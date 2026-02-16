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

---

## 4. Exit code 137 al transferir / procesar video (OOM Killer)

### Qué significa

Si en los logs aparece:

```text
Consumer resultado terminó con código de error: 137
Consumer resultado detenido inesperadamente
```

(o lo mismo para el consumer FFmpeg), **137 = 128 + 9**: el proceso fue terminado con **SIGKILL (9)**. En servidores con poca RAM suele ser el **OOM Killer** del kernel: al quedarse sin memoria, Linux mata uno o más procesos (a veces el de FFmpeg, a veces el consumer resultado como efecto colateral).

### Qué hacer

1. **Comprobar que fue OOM** (en el servidor):
   ```bash
   sudo dmesg -T | grep -i "out of memory"
   sudo dmesg -T | grep -i "killed process"
   ```
   Ahí verás si el kernel mató algún proceso por memoria.

2. **Aumentar memoria disponible**
   - **Añadir swap** (si no hay o es poca):
     ```bash
     sudo fallocate -l 2G /swapfile
     sudo chmod 600 /swapfile
     sudo mkswap /swapfile
     sudo swapon /swapfile
     echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
     ```
   - Subir la RAM del VPS/servidor si es posible.

3. **Límite de memoria para PHP**  
   Los wrappers ya usan `-d memory_limit=...` (256M para resultado, 512M para consumer). Puedes subirlo por entorno antes de arrancar el servicio:
   ```bash
   # En el .service o en el entorno del usuario que ejecuta
   export PHP_MEMORY_LIMIT=512M
   ```
   (No evita que FFmpeg use mucha RAM; solo limita el proceso PHP.)

4. **Procesar un video a la vez**  
   Si tienes varios workers del consumer FFmpeg, en servidores justos de RAM deja solo **1** consumiendo la cola `procesar_multimedia`, para no tener dos videos pesados en memoria a la vez.

5. **Videos más pequeños**  
   Si los vídeos son muy grandes, el servidor puede no tener RAM suficiente para descargarlos + FFmpeg. Limitar tamaño en el front o en la API de transferencia reduce el riesgo de 137.

---

## 5. Transferencia de video por enlace (no funciona en producción)

### Qué se hizo para evitarlo

- **CORS:** En producción el navegador no puede llamar directamente a la API externa (ej. videosegg.com) porque esa API no envía cabeceras CORS para tu dominio. Por eso se añadió un **proxy** en tu servidor:
  - El frontend llama a `actions_board.php?action=get_transfer_video_url` (POST con `ruta` = URL del video).
  - El backend llama a **API_TRANSFER_VIDEO** con esa `ruta` y devuelve la respuesta (incluido `url_video`) al navegador. Así no hay petición cross-origin desde el navegador a la API externa.

### Qué debes tener en producción

1. **API_TRANSFER_VIDEO** en `config/config.php` debe estar definida y ser la URL correcta de tu API de descarga (ej. `https://videosegg.com/download_video.php`). Si está vacía, el proxy responderá "API de transferencia no configurada".
2. Desde el **servidor** (PHP/cURL) debe poder alcanzar esa URL: firewall, DNS y que la API acepte peticiones desde tu IP/servidor.
3. El **consumer** (al procesar el mensaje de la cola) descarga el video desde la URL que devolvió la API. Esa URL debe ser accesible desde el servidor donde corre el consumer (misma red/firewall). Si la URL es temporal o con cookies de sesión, puede fallar; en ese caso la API debe devolver un enlace directo y durable al archivo.
