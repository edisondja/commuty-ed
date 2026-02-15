#!/bin/bash

# ============================================
# Desinstalador de servicios Commuty-ED
# ============================================

echo "🛑 Desinstalando servicios de Commuty-ED..."

# Verificar que se ejecuta como root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Por favor ejecuta este script como root (sudo)"
    exit 1
fi

# Detener servicios
echo "⏹️ Deteniendo servicios..."
systemctl stop commuty-consumer 2>/dev/null
systemctl stop commuty-resultado 2>/dev/null

# Deshabilitar servicios
echo "🚫 Deshabilitando servicios..."
systemctl disable commuty-consumer 2>/dev/null
systemctl disable commuty-resultado 2>/dev/null

# Eliminar archivos de servicio
echo "🗑️ Eliminando archivos de servicio..."
rm -f /etc/systemd/system/commuty-consumer.service
rm -f /etc/systemd/system/commuty-resultado.service

# Recargar systemd
echo "🔄 Recargando systemd..."
systemctl daemon-reload
systemctl reset-failed

echo ""
echo "✅ Servicios desinstalados correctamente!"
echo ""
echo "📝 Los logs se mantienen en /var/log/commuty/"
echo "   Para eliminarlos: sudo rm -rf /var/log/commuty"
