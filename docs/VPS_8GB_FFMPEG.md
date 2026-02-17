# Optimización RabbitMQ + FFmpeg para VPS 8GB RAM

## Resumen

El procesamiento de video (transferencia por enlace o subida) usa **consumer_service.php** (FFmpeg) y **consumer_resultado.php** (solo BD). En un VPS de 8GB se ha ajustado:

- **Menos hilos en FFmpeg** → menos pico de RAM.
- **Prioridad `nice`** → FFmpeg no acapara CPU y es menos probable que el OOM killer mate otros servicios.
- **Borrado temprano del archivo original** → tras comprimir se elimina el temporal para no tener dos copias grandes a la vez.
- **Límites en systemd** → el consumer de video tiene techo de memoria para no dejar al sistema sin RAM.

## Configuración (config.php)

```php
// Hilos FFmpeg (2 = bajo uso de RAM; 4 si tienes margen)
define('FFMPEG_THREADS', 2);

// Prioridad del proceso (5–10 recomendado; mayor = menos prioridad)
define('FFMPEG_NICE', 5);
```

- **FFMPEG_THREADS**: 2 recomendado para 8GB. Si el VPS tiene más RAM libre, puedes subir a 3 o 4 para acortar tiempo de compresión.
- **FFMPEG_NICE**: 5–10 deja que el resto del sistema (MySQL, RabbitMQ, web) respire. No poner 0 para no competir con todo.

## Systemd (8GB)

- **commuty-consumer**: `MemoryMax=2560M`, `MemoryHigh=2G` → un solo video en proceso puede usar hasta ~2.5GB (PHP + FFmpeg).
- **commuty-resultado**: `MemoryMax=512M` → solo escribe en BD.

Un único worker procesa un video a la vez (`basic_qos(null, 1, null)`). No conviene levantar varias instancias del consumer en 8GB o se repite el pico de memoria.

## Aplicar cambios en producción

1. Copiar archivos actualizados al servidor:
   - `config/config.php` (constantes FFMPEG_THREADS y FFMPEG_NICE).
   - `consumer_service.php`.
   - `systemd/commuty-consumer.service` y `systemd/commuty-resultado.service`.
2. Recargar systemd y reiniciar:
   ```bash
   sudo cp systemd/commuty-consumer.service /etc/systemd/system/
   sudo cp systemd/commuty-resultado.service /etc/systemd/system/
   sudo systemctl daemon-reload
   sudo systemctl restart commuty-consumer commuty-resultado
   ```
3. (Opcional) Revisar uso de memoria mientras procesas un video:
   ```bash
   watch -n 2 'ps -o pid,rss,cmd -C ffmpeg -C php | head -20'
   ```

## Si sigue habiendo exit 137 (OOM)

- Añadir **swap** (por ejemplo 2GB) para picos puntuales.
- Bajar **FFMPEG_THREADS** a 1 en `config.php`.
- Asegurar que solo corre **una** instancia de `commuty-consumer` (no varios workers para la misma cola).
