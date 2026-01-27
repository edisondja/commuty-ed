#!/bin/bash

# ============================================
# Wrapper para consumer_resultado.php
# Verifica extensiones PHP antes de ejecutar
# ============================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Verificar extensiones PHP requeridas
REQUIRED_EXTENSIONS=("mysqli" "pdo_mysql" "mbstring" "json")

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
if [ ! -f "consumer_resultado.php" ]; then
    echo "❌ ERROR: consumer_resultado.php no encontrado" >&2
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
                \"service_name\": \"commuty-resultado\",
                \"error_type\": \"${error_type}\",
                \"error_message\": \"${error_msg}\",
                \"additional_data\": {
                    \"script\": \"consumer_resultado.php\",
                    \"php_version\": \"$(php -v | head -n 1)\",
                    \"timestamp\": \"$(date -Iseconds)\"
                }
            }" > /dev/null 2>&1 || true
    fi
    
    # También escribir en log local
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: ${error_msg}" >> "${SCRIPT_DIR}/logs/services/resultado_errors.log" 2>/dev/null || true
}

# Crear directorio de logs si no existe
mkdir -p "${SCRIPT_DIR}/logs/services"

# Capturar errores y registrarlos
trap 'log_error "Consumer resultado detenido inesperadamente" "unexpected_exit"' EXIT
trap 'log_error "Error fatal en consumer_resultado.php" "fatal_error"' ERR

# Ejecutar el consumer y capturar salida de errores
/usr/bin/php consumer_resultado.php "$@" 2>&1 | while IFS= read -r line; do
    # Detectar errores en la salida
    if echo "$line" | grep -qiE "(error|fatal|exception|failed|❌)"; then
        log_error "$line" "output_error"
    fi
    echo "$line"
done

exit_code=${PIPESTATUS[0]}

# Si el consumer salió con error, registrarlo
if [ $exit_code -ne 0 ]; then
    log_error "Consumer resultado terminó con código de error: $exit_code" "exit_error"
fi

exit $exit_code
