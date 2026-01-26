#!/bin/bash

# ============================================
# Instalador de servicios Commuty-ED
# ============================================

echo "🚀 Instalando servicios de Commuty-ED..."

# Verificar que se ejecuta como root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Por favor ejecuta este script como root (sudo)"
    exit 1
fi

# Crear directorio de logs
echo "📁 Creando directorio de logs..."
mkdir -p /var/log/commuty
chown www-data:www-data /var/log/commuty
chmod 755 /var/log/commuty

# Copiar archivos de servicio
echo "📋 Copiando archivos de servicio..."
cp commuty-consumer.service /etc/systemd/system/
cp commuty-resultado.service /etc/systemd/system/

# Establecer permisos
chmod 644 /etc/systemd/system/commuty-consumer.service
chmod 644 /etc/systemd/system/commuty-resultado.service

# Recargar systemd
echo "🔄 Recargando systemd..."
systemctl daemon-reload

# Habilitar servicios para inicio automático
echo "✅ Habilitando servicios..."
systemctl enable commuty-consumer
systemctl enable commuty-resultado

# Iniciar servicios
echo "▶️ Iniciando servicios..."
systemctl start commuty-consumer
systemctl start commuty-resultado

# Mostrar estado
echo ""
echo "📊 Estado de los servicios:"
echo "----------------------------"
systemctl status commuty-consumer --no-pager
echo ""
systemctl status commuty-resultado --no-pager

echo ""
echo "✅ Instalación completada!"
echo ""
echo "📝 Comandos útiles:"
echo "  - Ver estado:     sudo systemctl status commuty-consumer"
echo "  - Ver logs:       sudo journalctl -u commuty-consumer -f"
echo "  - Reiniciar:      sudo systemctl restart commuty-consumer"
echo "  - Detener:        sudo systemctl stop commuty-consumer"
echo "  - Logs archivo:   tail -f /var/log/commuty/consumer.log"
