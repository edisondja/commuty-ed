#!/bin/bash

# ============================================
# Monitor de RabbitMQ y Servicios Commuty-ED
# ============================================

DOMAIN="${1:-https://meneito.com}"
DEBUG_URL="${DOMAIN}/controllers/debug_rabbitmq.php"

echo "================================================"
echo "🔍 Monitor de RabbitMQ - Commuty-ED"
echo "================================================"
echo "Dominio: $DOMAIN"
echo "URL Debug: $DEBUG_URL"
echo ""

# Función para mostrar estado
show_status() {
    clear
    echo "================================================"
    echo "🔍 Monitor de RabbitMQ - Commuty-ED"
    echo "================================================"
    echo "Actualizado: $(date '+%Y-%m-%d %H:%M:%S')"
    echo ""
    
    # 1. Estado de servicios systemd
    echo "📊 SERVICIOS SYSTEMD:"
    echo "-------------------"
    for service in commuty-consumer commuty-resultado; do
        if systemctl is-active --quiet $service 2>/dev/null; then
            status="✅ ACTIVO"
        else
            status="❌ INACTIVO"
        fi
        echo "  $service: $status"
    done
    echo ""
    
    # 2. Procesos PHP corriendo
    echo "🐘 PROCESOS PHP:"
    echo "---------------"
    php_count=$(ps aux | grep -E 'consumer_service\.php|consumer_resultado\.php' | grep -v grep | wc -l)
    if [ "$php_count" -gt 0 ]; then
        echo "  ✅ $php_count proceso(s) corriendo"
        ps aux | grep -E 'consumer_service\.php|consumer_resultado\.php' | grep -v grep | awk '{print "    PID: "$2" | CPU: "$3"% | MEM: "$4"% | "$11" "$12}'
    else
        echo "  ❌ No hay procesos corriendo"
    fi
    echo ""
    
    # 3. Estado de RabbitMQ
    echo "🐰 RABBITMQ:"
    echo "------------"
    if systemctl is-active --quiet rabbitmq-server 2>/dev/null; then
        echo "  ✅ Servicio activo"
        
        # Verificar conexión
        if rabbitmqctl status > /dev/null 2>&1; then
            echo "  ✅ Conexión OK"
            
            # Contar mensajes en colas
            echo ""
            echo "  📦 Colas:"
            queue1=$(rabbitmqctl list_queues name messages consumers | grep 'procesar_multimedia' | awk '{print $2}')
            queue2=$(rabbitmqctl list_queues name messages consumers | grep 'resultado_procesamiento' | awk '{print $2}')
            
            echo "    procesar_multimedia: ${queue1:-0} mensajes"
            echo "    resultado_procesamiento: ${queue2:-0} mensajes"
        else
            echo "  ⚠️ No se puede conectar"
        fi
    else
        echo "  ❌ Servicio inactivo"
    fi
    echo ""
    
    # 4. Llamar al endpoint de debug
    echo "🌐 VERIFICACIÓN REMOTA:"
    echo "----------------------"
    if command -v curl > /dev/null; then
        response=$(curl -s -w "\nHTTP_CODE:%{http_code}" "$DEBUG_URL" 2>/dev/null)
        http_code=$(echo "$response" | grep "HTTP_CODE:" | cut -d: -f2)
        body=$(echo "$response" | sed '/HTTP_CODE:/d')
        
        if [ "$http_code" = "200" ]; then
            echo "  ✅ Endpoint accesible (HTTP $http_code)"
            
            # Parsear JSON básico
            if echo "$body" | grep -q '"status"'; then
                status=$(echo "$body" | grep -o '"status":"[^"]*"' | cut -d'"' -f4)
                echo "  Estado: $status"
            fi
        else
            echo "  ❌ Error HTTP $http_code"
        fi
    else
        echo "  ⚠️ curl no disponible"
    fi
    echo ""
    
    # 5. Logs recientes
    echo "📝 LOGS RECIENTES (últimas 3 líneas):"
    echo "------------------------------------"
    if [ -f "/var/log/commuty/consumer.log" ]; then
        echo "  consumer.log:"
        tail -3 /var/log/commuty/consumer.log 2>/dev/null | sed 's/^/    /'
    fi
    if [ -f "/var/log/commuty/consumer-error.log" ]; then
        echo "  consumer-error.log:"
        tail -3 /var/log/commuty/consumer-error.log 2>/dev/null | sed 's/^/    /'
    fi
    echo ""
    
    echo "================================================"
    echo "Presiona Ctrl+C para salir"
    echo "Actualizando cada 5 segundos..."
}

# Modo interactivo
if [ "$2" != "--once" ]; then
    while true; do
        show_status
        sleep 5
    done
else
    show_status
fi
