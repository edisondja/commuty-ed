#!/bin/bash
# Script para corregir permisos de directorios de Smarty

echo "Corrigiendo permisos de directorios de Smarty..."

# Asegurar que los directorios existan
mkdir -p compile
mkdir -p cache

# Dar permisos de escritura
chmod 777 compile/
chmod 777 cache/

# También dar permisos a los archivos dentro de compile
chmod 666 compile/* 2>/dev/null || true

echo "✓ Permisos corregidos para compile/ y cache/"
echo ""
echo "Si el problema persiste, puede que necesites cambiar el propietario:"
echo "  sudo chown -R _www:admin compile/ cache/"
echo "  (o el usuario que ejecuta Apache en tu sistema)"
