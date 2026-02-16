# Dónde se guardan los logs fallidos de los servicios

## Ubicación principal: `logs/services/`

Todos los logs de fallos de los servicios (consumer FFmpeg y consumer resultado) se guardan dentro del proyecto en:

```
<raíz del proyecto>/logs/services/
```

### Archivos que se generan

| Archivo | Origen | Contenido |
|--------|--------|-----------|
| **consumer_errors.log** | `consumer_wrapper.sh` | Líneas de error detectadas en la salida del consumer (FFmpeg) y al salir con código distinto de 0. Formato: `[YYYY-MM-DD HH:MM:SS] ERROR: mensaje` |
| **resultado_errors.log** | `resultado_wrapper.sh` | Igual que arriba pero para el consumer de resultados. |
| **service_failures_YYYY-MM-DD.log** | `log_service_failure.php` | Un archivo por día. Cada línea es un JSON con: timestamp, service_name, error_type, error_message, stack_trace, additional_data, server (hostname, php_version, memory). Lo rellenan los wrappers cuando hacen POST al endpoint. |

### Flujo

1. Los wrappers (`consumer_wrapper.sh`, `resultado_wrapper.sh`) ejecutan el PHP del consumer.
2. Si detectan líneas con "error/fatal/exception/failed" o si el proceso termina con código ≠ 0, llaman a `log_error()`.
3. `log_error()` hace dos cosas:
   - Escribe en **logs/services/consumer_errors.log** (o resultado_errors.log).
   - Opcionalmente hace POST a `DOMAIN/controllers/log_service_failure.php`, que escribe en **logs/services/service_failures_YYYY-MM-DD.log** y, si está configurada la BD, en la tabla `service_failures`.

---

## Otras rutas de log del proyecto

- **traking/errores.txt** – Errores generales vía `TrackingLog(..., 'errores')` (Board, User, etc.).
- **traking/eventos.txt** – Eventos vía `TrackingLog(..., 'eventos')`.
- **traking/usuarios.txt**, **traking/alertas.txt** – Otros tipos de TrackingLog.

Los **servicios** (consumers) no usan TrackingLog; usan solo **logs/services/** y, si se llama al endpoint, **service_failures_*.log**.

---

## En producción (systemd)

Si los servicios se ejecutan con systemd, la salida estándar y errores también van al **journal**:

```bash
# Ver últimos fallos del consumer FFmpeg
journalctl -u commuty-consumer -n 200 --no-pager

# Ver últimos fallos del consumer resultado
journalctl -u commuty-resultado -n 200 --no-pager
```

Los archivos bajo **logs/services/** siguen siendo la referencia para “logs fallidos” guardados en disco; el journal es complementario para ver qué imprimió el proceso antes de caer.

---

## Resumen rápido

**¿Dónde guardar / dónde están los logs fallidos de los servicios?**

- **En el proyecto:** `logs/services/`
  - `consumer_errors.log` – fallos del consumer FFmpeg
  - `resultado_errors.log` – fallos del consumer resultado
  - `service_failures_YYYY-MM-DD.log` – fallos registrados vía API (por día)
- Asegurar que el directorio existe y que el usuario que corre los servicios (ej. `www-data`) puede escribir:

```bash
mkdir -p logs/services
chown -R www-data:www-data logs
chmod 755 logs
chmod 755 logs/services
```
