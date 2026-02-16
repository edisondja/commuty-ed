#!/bin/bash

# ============================================
# Wrapper para consumer_service.php
# Verifica extensiones PHP antes de ejecutar
# ============================================

# Obtener directorio del script de forma robusta
SCRIPT_PATH="${BASH_SOURCE[0]}"
if [ -L "$SCRIPT_PATH" ]; then
    # Si es un symlink, seguir el enlace
    SCRIPT_DIR="$(cd "$(dirname "$(readlink -f "$SCRIPT_PATH")")" && pwd)"
else
    SCRIPT_DIR="$(cd "$(dirname "$SCRIPT_PATH")" && pwd)"
fi

# Verificar que el directorio existe antes de cambiar
if [ ! -d "$SCRIPT_DIR" ]; then
    echo "❌ ERROR: Directorio del script no existe: $SCRIPT_DIR" >&2
    exit 1
fi

# Cambiar al directorio del script
if ! cd "$SCRIPT_DIR" 2>/dev/null; then
    echo "❌ ERROR: No se pudo cambiar al directorio: $SCRIPT_DIR" >&2
    exit 1
fi

# Verificar que estamos en el directorio correcto
if [ ! -f "consumer_service.php" ] && [ ! -f "config/config.php" ]; then
    echo "❌ ERROR: No se encontró consumer_service.php o config/config.php en: $(pwd)" >&2
    echo "Directorio actual: $(pwd)" >&2
    exit 1
fi

# Verificar extensiones PHP requeridas
REQUIRED_EXTENSIONS=("mysqli" "pdo_mysql" "mbstring" "curl" "json")

MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if ! php -m | grep -qi "^${ext}$"; then
        MISSING_EXTENSIONS+=("$ext")
    fi
done

if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
    echo "❌ ERROR: Extensiones PHP faltantes: ${MISSING_EXTENSIONS[*]}" >&2
    echo "Ejecuta: sudo bash systemd/fix_php_extensions.sh" >&2
    exit 1
fi

# Verificar que el archivo existe
if [ ! -f "consumer_service.php" ]; then
    echo "❌ ERROR: consumer_service.php no encontrado" >&2
    exit 1
fi

# Función para registrar errores
log_error() {
    local error_msg="$1"
    local error_type="${2:-runtime_error}"
    
    # Intentar registrar en el sistema de logs
    if command -v curl > /dev/null; then
        # Obtener dominio desde config si es posible
        DOMAIN=$(php -r "require 'config/config.php'; echo DOMAIN;" 2>/dev/null || echo "http://localhost")
        
        curl -s -X POST "${DOMAIN}/controllers/log_service_failure.php" \
            -H "Content-Type: application/json" \
            -d "{
                \"service_name\": \"commuty-consumer\",
                \"error_type\": \"${error_type}\",
                \"error_message\": \"${error_msg}\",
                \"additional_data\": {
                    \"script\": \"consumer_service.php\",
                    \"php_version\": \"$(php -v | head -n 1)\",
                    \"timestamp\": \"$(date -Iseconds)\"
                }
            }" > /dev/null 2>&1 || true
    fi
    
    # También escribir en log local
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: ${error_msg}" >> "${SCRIPT_DIR}/logs/services/consumer_errors.log" 2>/dev/null || true
}

# Crear directorio de logs si no existe
mkdir -p "${SCRIPT_DIR}/logs/services"

# Capturar errores y registrarlos
trap 'log_error "Consumer detenido inesperadamente" "unexpected_exit"' EXIT
trap 'log_error "Error fatal en consumer_service.php" "fatal_error"' ERR

# Límite de memoria para PHP. El consumo fuerte lo hace FFmpeg (proceso hijo); esto evita que PHP crezca sin control
export PHP_MEMORY_LIMIT="${PHP_MEMORY_LIMIT:-512M}"

# Ejecutar el consumer y capturar salida de errores
/usr/bin/php -d memory_limit="${PHP_MEMORY_LIMIT}" consumer_service.php "$@" 2>&1 | while IFS= read -r line; do
    # Detectar errores en la salida
    if echo "$line" | grep -qiE "(error|fatal|exception|failed|❌)"; then
        log_error "$line" "output_error"
    fi
    echo "$line"
done

exit_code=${PIPESTATUS[0]}

# Si el consumer salió con error, registrarlo
if [ $exit_code -ne 0 ]; then
    log_error "Consumer terminó con código de error: $exit_code" "exit_error"
    # Código 137 = proceso matado por el kernel (SIGKILL), típicamente OOM al procesar video
    if [ "$exit_code" = "137" ]; then
        log_error "Exit 137 = proceso matado por falta de memoria (OOM). Solución: más RAM, swap, o videos más pequeños. Ver: dmesg | grep -i 'out of memory'" "oom_kill"
    fi
fi

exit $exit_code
