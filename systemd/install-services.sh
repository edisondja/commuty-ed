#!/bin/bash

# ============================================
# Instalador de servicios Commuty-ED
# Optimizado para servidor con 8GB RAM
# ============================================

set -e

echo "🚀 Instalando servicios de Commuty-ED..."
echo "   Optimizado para servidor con 8GB RAM"
echo ""

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

# Configurar logrotate para los logs
echo "📝 Configurando rotación de logs..."
cat > /etc/logrotate.d/commuty << 'EOF'
/var/log/commuty/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload commuty-consumer 2>/dev/null || true
        systemctl reload commuty-resultado 2>/dev/null || true
    endscript
}
EOF

# Copiar archivos de servicio
echo "📋 Copiando archivos de servicio..."
cp commuty-consumer.service /etc/systemd/system/
cp commuty-resultado.service /etc/systemd/system/

# Establecer permisos
chmod 644 /etc/systemd/system/commuty-consumer.service
chmod 644 /etc/systemd/system/commuty-resultado.service

# Optimizar límites del sistema para mejor rendimiento
echo "⚙️ Optimizando límites del sistema..."
if ! grep -q "# Commuty-ED optimizations" /etc/security/limits.conf; then
    cat >> /etc/security/limits.conf << 'EOF'

# Commuty-ED optimizations
www-data soft nofile 65535
www-data hard nofile 65535
www-data soft nproc 4096
www-data hard nproc 4096
EOF
fi

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
sleep 2
systemctl start commuty-resultado

# Esperar a que inicien
sleep 3

# Mostrar estado
echo ""
echo "================================================"
echo "📊 Estado de los servicios:"
echo "================================================"
echo ""
echo "🎬 Consumer de Videos:"
systemctl status commuty-consumer --no-pager -l | head -15
echo ""
echo "📊 Consumer de Resultados:"
systemctl status commuty-resultado --no-pager -l | head -15

echo ""
echo "================================================"
echo "✅ Instalación completada!"
echo "================================================"
echo ""
echo "📊 Recursos asignados:"
echo "   - Consumer Videos:    1GB RAM, 80% CPU"
echo "   - Consumer Resultados: 512MB RAM, 40% CPU"
echo ""
echo "📝 Comandos útiles:"
echo "   Ver estado:        sudo systemctl status commuty-consumer"
echo "   Ver logs live:     sudo journalctl -u commuty-consumer -f"
echo "   Reiniciar:         sudo systemctl restart commuty-consumer"
echo "   Detener:           sudo systemctl stop commuty-consumer"
echo "   Logs archivo:      tail -f /var/log/commuty/consumer.log"
echo ""
echo "📈 Monitorear recursos:"
echo "   htop (filtrar por php)"
echo "   systemctl status commuty-*"
echo ""
