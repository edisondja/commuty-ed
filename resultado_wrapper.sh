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

# Ejecutar el consumer
exec /usr/bin/php consumer_resultado.php "$@"
