# Correcciones para Errores en Producción

## Problemas Resueltos

### 1. Error: `getcwd() failed: No such file or directory`

**Causa**: Los wrappers (`consumer_wrapper.sh`, `resultado_wrapper.sh`) intentaban cambiar al directorio del script sin verificar que existiera o que fuera accesible.

**Solución**:
- Verificación robusta del directorio antes de cambiar
- Manejo de symlinks
- Verificación de que los archivos necesarios existen antes de continuar
- Mensajes de error más descriptivos

**Archivos modificados**:
- `consumer_wrapper.sh`
- `resultado_wrapper.sh`

### 2. Error: `No se pudo crear el video comprimido: videos/20260127145243_30.mp4`

**Causa**: 
- Uso de rutas relativas que dependían del directorio de trabajo actual
- Directorios de salida no existían o no tenían permisos
- Falta de verificación de errores de FFmpeg

**Solución**:
- Uso de rutas absolutas basadas en `$BASE_DIR`
- Creación automática de directorios necesarios (`uploads/`, `videos/`, `previa/`, `imagenes_tablero/`, `tmp/`)
- Verificación de permisos antes de escribir
- Mejor logging de errores de FFmpeg con salida completa
- Verificación de existencia y legibilidad de archivos de entrada

**Archivos modificados**:
- `consumer_service.php`

## Cambios Implementados

### consumer_service.php

1. **Directorio base absoluto**:
   ```php
   $BASE_DIR = dirname(__FILE__);
   chdir($BASE_DIR);
   ```

2. **Función helper para directorios**:
   ```php
   function ensureDirectory($dir) {
       if (!is_dir($dir)) {
           if (!mkdir($dir, 0777, true)) {
               throw new Exception("No se pudo crear directorio: $dir");
           }
       }
       return realpath($dir);
   }
   ```

3. **Rutas absolutas**:
   - `$rutaImagen = $BASE_DIR . "/imagenes_tablero/{$fecha}_{$board_id}.jpg";`
   - `$video_completo = $BASE_DIR . "/videos/{$fecha}_{$board_id}.mp4";`
   - `$reciduo_video = $BASE_DIR . "/previa/{$fecha}_{$board_id}.mp4";`

4. **Mejor manejo de errores FFmpeg**:
   - Muestra salida completa de FFmpeg cuando falla
   - Verifica permisos antes de escribir
   - Información detallada en excepciones

### consumer_wrapper.sh y resultado_wrapper.sh

1. **Detección robusta del directorio**:
   ```bash
   SCRIPT_PATH="${BASH_SOURCE[0]}"
   if [ -L "$SCRIPT_PATH" ]; then
       SCRIPT_DIR="$(cd "$(dirname "$(readlink -f "$SCRIPT_PATH")")" && pwd)"
   else
       SCRIPT_DIR="$(cd "$(dirname "$SCRIPT_PATH")" && pwd)"
   fi
   ```

2. **Verificaciones antes de cambiar de directorio**:
   - Verifica que el directorio existe
   - Verifica que se puede cambiar al directorio
   - Verifica que los archivos necesarios existen

## Para Aplicar en Producción

### 1. Actualizar archivos

```bash
cd /var/www/commuty-ed
git pull  # o copiar archivos manualmente
```

### 2. Crear directorios necesarios

```bash
sudo mkdir -p /var/www/commuty-ed/tmp
sudo chown -R www-data:www-data /var/www/commuty-ed/{uploads,videos,previa,imagenes_tablero,tmp,logs}
sudo chmod -R 755 /var/www/commuty-ed/{uploads,videos,previa,imagenes_tablero,tmp,logs}
```

### 3. Verificar permisos de wrappers

```bash
sudo chmod +x /var/www/commuty-ed/consumer_wrapper.sh
sudo chmod +x /var/www/commuty-ed/resultado_wrapper.sh
sudo chown www-data:www-data /var/www/commuty-ed/consumer_wrapper.sh
sudo chown www-data:www-data /var/www/commuty-ed/resultado_wrapper.sh
```

### 4. Reiniciar servicios

```bash
sudo systemctl restart commuty-consumer
sudo systemctl restart commuty-resultado
```

### 5. Verificar logs

```bash
# Ver logs del consumer
sudo journalctl -u commuty-consumer -f

# Ver logs del resultado
sudo journalctl -u commuty-resultado -f

# Ver logs de errores
tail -f /var/www/commuty-ed/logs/services/consumer_errors.log
```

## Verificación

Después de aplicar los cambios, verifica:

1. **Servicios activos**:
   ```bash
   sudo systemctl status commuty-consumer
   sudo systemctl status commuty-resultado
   ```

2. **Sin errores de directorio**:
   ```bash
   sudo journalctl -u commuty-consumer | grep -i "getcwd\|directorio"
   # No debería mostrar errores
   ```

3. **Procesamiento de videos**:
   - Sube un video de prueba
   - Verifica que se crea en `/var/www/commuty-ed/videos/`
   - Verifica que no hay errores en los logs

## Notas Adicionales

- Los directorios se crean automáticamente si no existen
- Las rutas son siempre absolutas, no dependen del directorio de trabajo
- Los errores se registran tanto en archivos de log como en la base de datos (si está configurado)
- El sistema de registro de fallos captura automáticamente estos errores
