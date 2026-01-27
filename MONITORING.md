# 🔍 Guía de Monitoreo - Commuty-ED

## Herramientas de Monitoreo

### 1. Script de Diagnóstico CLI (`test_rabbitmq_connection.php`)

Script de línea de comandos para diagnosticar problemas de conexión RabbitMQ directamente desde el servidor.

#### Uso

```bash
# Desde el directorio del proyecto
cd /var/www/commuty-ed
php test_rabbitmq_connection.php
```

#### Qué Verifica

- ✅ Conectividad de red (puerto alcanzable)
- ✅ Conexión AMQP
- ✅ Creación de canales
- ✅ Estado de colas (mensajes y consumidores)
- ✅ Diagnóstico automático de problemas comunes

#### Ventajas

- No requiere acceso web
- Ejecuta directamente en el servidor
- Muestra diagnósticos detallados de errores
- Sugiere soluciones específicas para cada problema

#### Ejemplo de Salida

```
🔍 Diagnóstico de Conexión RabbitMQ
==================================================

📋 Configuración:
   Host: localhost
   Puerto: 5672
   Usuario: guest
   VHost: /

1️⃣ Verificando conectividad de red...
   ✅ Puerto 5672 alcanzable en localhost

2️⃣ Intentando conexión AMQP...
   ✅ Conexión AMQP exitosa

3️⃣ Verificando canales...
   ✅ Canal creado correctamente

4️⃣ Verificando colas...
   ✅ Cola 'procesar_multimedia':
      - Mensajes en cola: 0
      - Consumidores activos: 1
   ✅ Cola 'multimedia_resultado':
      - Mensajes en cola: 0
      - Consumidores activos: 1

✅ Todas las verificaciones pasaron correctamente
```

### 2. Endpoint de Debug (`/controllers/debug_rabbitmq.php`)

Endpoint web que verifica el estado completo del sistema.

#### Uso Básico

```bash
# Verificar estado completo
curl https://meneito.com/controllers/debug_rabbitmq.php

# Enviar mensaje de prueba
curl "https://meneito.com/controllers/debug_rabbitmq.php?test_send=1"
```

#### Qué Verifica

- ✅ Conexión a RabbitMQ
- ✅ Estado de colas (mensajes pendientes, consumers activos)
- ✅ Servicios systemd (commuty-consumer, commuty-resultado)
- ✅ Procesos PHP corriendo
- ✅ Permisos de directorios
- ✅ Configuración de dominio
- ✅ Test de envío de mensaje (opcional)

#### Ejemplo de Respuesta

```json
{
  "timestamp": "2026-01-24 15:30:45",
  "status": "ok",
  "checks": {
    "rabbitmq_connection": {
      "status": "success",
      "message": "Conexión a RabbitMQ exitosa",
      "host": "localhost",
      "port": 5672
    },
    "rabbitmq_queues": {
      "status": "success",
      "queues": {
        "procesar_multimedia": {
          "exists": true,
          "messages": 2,
          "consumers": 1
        }
      }
    },
    "systemd_services": {
      "commuty-consumer": {
        "active": true,
        "enabled": true
      }
    }
  }
}
```

### 2. Script de Monitoreo (`monitor_rabbitmq.sh`)

Script de terminal para monitoreo en tiempo real.

#### Instalación

```bash
cd /var/www/commuty-ed
chmod +x monitor_rabbitmq.sh
```

#### Uso

```bash
# Monitoreo continuo (actualiza cada 5 segundos)
./monitor_rabbitmq.sh https://meneito.com

# Ver estado una vez
./monitor_rabbitmq.sh https://meneito.com --once
```

#### Información Mostrada

- Estado de servicios systemd
- Procesos PHP corriendo (PID, CPU, MEM)
- Estado de RabbitMQ
- Mensajes en colas
- Logs recientes

### 3. Logs Mejorados

El sistema ahora registra información detallada cuando subes un video:

#### Ubicación de Logs

```bash
# Logs del consumer
tail -f /var/log/commuty/consumer.log

# Errores del consumer
tail -f /var/log/commuty/consumer-error.log

# Logs de eventos (en traking/)
tail -f traking/eventos.log
```

#### Información Registrada

Cuando subes un video, se registra:

1. **Inicio del proceso**
   - Board ID
   - Tipo de archivo
   - Token único de seguimiento
   - Timestamp

2. **URL del Producer**
   - URL completa usada en el curl
   - Dominio configurado

3. **Datos del archivo**
   - Ruta del archivo
   - Tamaño (para archivos locales)

4. **Resultado del cURL**
   - HTTP Code
   - Duración de la petición
   - Tamaño subido/descargado
   - Respuesta del servidor

5. **Errores**
   - Errores de conexión
   - Errores de validación
   - Errores de cURL

### 4. Verificar Flujo Completo

#### Paso 1: Subir un Video

Sube un video desde la interfaz web.

#### Paso 2: Verificar Logs

```bash
# Ver logs en tiempo real
tail -f traking/eventos.log | grep "Board:"

# Filtrar por token único
tail -f traking/eventos.log | grep "Token: req_"
```

#### Paso 3: Verificar RabbitMQ

```bash
# Ver mensajes en cola
sudo rabbitmqctl list_queues name messages consumers

# Ver consumers conectados
sudo rabbitmqctl list_consumers
```

#### Paso 4: Verificar Procesamiento

```bash
# Ver si el consumer está procesando
sudo journalctl -u commuty-consumer -f

# Ver logs del consumer
tail -f /var/log/commuty/consumer.log
```

### 5. Solución de Problemas

#### El video no se procesa

1. **Verificar RabbitMQ**
   ```bash
   sudo systemctl status rabbitmq-server
   curl https://meneito.com/controllers/debug_rabbitmq.php
   ```

2. **Verificar Consumers**
   ```bash
   sudo systemctl status commuty-consumer
   ps aux | grep consumer_service.php
   ```

3. **Verificar Logs**
   ```bash
   tail -50 traking/eventos.log | grep "Error"
   tail -50 /var/log/commuty/consumer-error.log
   ```

#### El curl falla

1. **Verificar dominio**
   ```bash
   # Ver qué dominio se está usando
   grep "DOMAIN" config/config.php
   ```

2. **Verificar producer_service.php**
   ```bash
   # Verificar que existe
   ls -la producer_service.php
   
   # Probar acceso directo
   curl https://meneito.com/producer_service.php
   ```

#### RabbitMQ no se puede conectar en producción

**Síntoma**: El servicio RabbitMQ está activo pero no se puede conectar cuando se monitorea.

**Diagnóstico paso a paso**:

1. **Ejecutar script de diagnóstico CLI**:
   ```bash
   cd /var/www/commuty-ed
   php test_rabbitmq_connection.php
   ```

2. **Verificar configuración en `config/config.php`**:
   ```bash
   # Verificar que las constantes estén definidas correctamente
   grep -E "host_rabbit_mq|port_rabbit_mq|user_rabbit_mq" config/config.php
   ```
   
   Debe mostrar algo como:
   ```
   define('host_rabbit_mq','localhost');
   define('port_rabbit_mq','5672');
   define('user_rabbit_mq','guest');
   ```

3. **Verificar que RabbitMQ esté corriendo**:
   ```bash
   sudo systemctl status rabbitmq-server
   ```

4. **Verificar que el puerto esté abierto**:
   ```bash
   sudo netstat -tlnp | grep 5672
   # O
   sudo ss -tlnp | grep 5672
   ```

5. **Verificar conectividad de red**:
   ```bash
   # Desde el servidor web
   telnet localhost 5672
   # O si RabbitMQ está en otro servidor
   telnet <ip_rabbitmq> 5672
   ```

6. **Verificar firewall**:
   ```bash
   sudo ufw status
   # Si está activo, permitir puerto 5672
   sudo ufw allow 5672/tcp
   ```

7. **Verificar logs de RabbitMQ**:
   ```bash
   sudo journalctl -u rabbitmq-server -n 50
   # O
   sudo tail -50 /var/log/rabbitmq/rabbit@*.log
   ```

8. **Verificar usuarios y permisos**:
   ```bash
   sudo rabbitmqctl list_users
   sudo rabbitmqctl list_permissions -p /
   ```

**Soluciones comunes**:

- **Si el host es `localhost` pero RabbitMQ está en otro servidor**:
  - Cambiar `host_rabbit_mq` en `config/config.php` a la IP o hostname del servidor RabbitMQ

- **Si hay problemas de autenticación**:
  ```bash
  # Crear usuario específico
  sudo rabbitmqctl add_user commuty_user tu_password
  sudo rabbitmqctl set_permissions -p / commuty_user ".*" ".*" ".*"
  sudo rabbitmqctl set_user_tags commuty_user administrator
  ```
  Luego actualizar `config/config.php` con el nuevo usuario y contraseña.

- **Si el puerto no está abierto**:
  ```bash
  # Verificar configuración de RabbitMQ
  sudo rabbitmqctl environment | grep listeners
  # Debe mostrar algo como: {listeners,[{amqp,5672}]}
  ```

- **Si hay problemas de red/firewall**:
  ```bash
  # Permitir puerto en firewall
  sudo ufw allow 5672/tcp
  sudo ufw reload
  ```

**Verificar desde el endpoint web**:
```bash
curl https://meneito.com/controllers/debug_rabbitmq.php | jq
```

El endpoint ahora incluye:
- Diagnóstico automático de problemas
- Sugerencias específicas según el error
- Verificación de conectividad de red
- Información detallada de configuración

3. **Ver logs detallados**
   ```bash
   tail -f traking/eventos.log | grep "cURL"
   ```

#### Los servicios no inician

```bash
# Ver errores
sudo journalctl -u commuty-consumer -n 50
sudo journalctl -u commuty-resultado -n 50

# Reiniciar
sudo systemctl restart commuty-consumer
sudo systemctl restart commuty-resultado
```

### 6. Monitoreo Continuo

#### Usar el script de monitoreo

```bash
# En una terminal separada
cd /var/www/commuty-ed
./monitor_rabbitmq.sh https://meneito.com
```

#### Integrar con sistema de monitoreo

```bash
# Agregar a cron para verificación periódica
*/5 * * * * /var/www/commuty-ed/monitor_rabbitmq.sh https://meneito.com --once >> /var/log/commuty/monitor.log 2>&1
```

### 7. Ejemplo de Flujo Completo

```
1. Usuario sube video
   ↓
2. Board.php → enviar_a_procesar_multimedia()
   ↓
3. Log: "🚀 Iniciando procesamiento multimedia"
   ↓
4. cURL a DOMAIN/producer_service.php
   ↓
5. Log: "🌐 URL Producer: https://meneito.com/producer_service.php"
   ↓
6. producer_service.php → Envía a RabbitMQ
   ↓
7. consumer_service.php → Procesa video
   ↓
8. consumer_resultado.php → Actualiza BD
```

Cada paso está registrado en los logs para fácil seguimiento.
