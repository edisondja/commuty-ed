#!/bin/bash

# ============================================
# Script para instalar/fix extensiones PHP
# ============================================

set -e

echo "🔧 Verificando y corrigiendo extensiones PHP..."
echo ""

# Detectar versión de PHP
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
PHP_API=$(php -r "echo PHP_VERSION_ID;")

echo "📋 PHP Version: $PHP_VERSION (API: $PHP_API)"
echo ""

# Verificar si mysqli está instalado
echo "🔍 Verificando extensiones..."
php -m | grep -i mysqli > /dev/null && echo "✅ mysqli: Instalado" || echo "❌ mysqli: NO instalado"
php -m | grep -i pdo_mysql > /dev/null && echo "✅ pdo_mysql: Instalado" || echo "❌ pdo_mysql: NO instalado"
php -m | grep -i mbstring > /dev/null && echo "✅ mbstring: Instalado" || echo "❌ mbstring: NO instalado"
php -m | grep -i gd > /dev/null && echo "✅ gd: Instalado" || echo "❌ gd: NO instalado"
php -m | grep -i zip > /dev/null && echo "✅ zip: Instalado" || echo "❌ zip: NO instalado"
php -m | grep -i curl > /dev/null && echo "✅ curl: Instalado" || echo "❌ curl: NO instalado"
echo ""

# Detectar sistema operativo
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS=$ID
else
    echo "❌ No se pudo detectar el sistema operativo"
    exit 1
fi

echo "🖥️ Sistema operativo: $OS"
echo ""

# Instalar extensiones según el sistema
if [ "$OS" = "ubuntu" ] || [ "$OS" = "debian" ]; then
    echo "📦 Instalando extensiones PHP para Ubuntu/Debian..."
    
    # Actualizar repositorios
    sudo apt-get update -qq
    
    # Instalar extensiones
    sudo apt-get install -y \
        php${PHP_VERSION}-mysqli \
        php${PHP_VERSION}-pdo \
        php${PHP_VERSION}-pdo-mysql \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-zip \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-fileinfo
    
    echo "✅ Extensiones instaladas"
    
elif [ "$OS" = "centos" ] || [ "$OS" = "rhel" ] || [ "$OS" = "fedora" ]; then
    echo "📦 Instalando extensiones PHP para CentOS/RHEL..."
    
    if command -v dnf > /dev/null; then
        sudo dnf install -y \
            php-mysqli \
            php-pdo \
            php-pdo_mysql \
            php-mbstring \
            php-gd \
            php-zip \
            php-curl \
            php-xml \
            php-fileinfo
    else
        sudo yum install -y \
            php-mysqli \
            php-pdo \
            php-pdo_mysql \
            php-mbstring \
            php-gd \
            php-zip \
            php-curl \
            php-xml \
            php-fileinfo
    fi
    
    echo "✅ Extensiones instaladas"
else
    echo "⚠️ Sistema operativo no soportado automáticamente"
    echo "Por favor instala manualmente:"
    echo "  - php-mysqli"
    echo "  - php-pdo-mysql"
    echo "  - php-mbstring"
    echo "  - php-gd"
    echo "  - php-zip"
    echo "  - php-curl"
fi

echo ""
echo "🔄 Reiniciando PHP-FPM (si está instalado)..."
if systemctl is-active --quiet php${PHP_VERSION}-fpm 2>/dev/null; then
    sudo systemctl restart php${PHP_VERSION}-fpm
    echo "✅ PHP-FPM reiniciado"
fi

echo ""
echo "✅ Verificación final:"
php -m | grep -i mysqli > /dev/null && echo "✅ mysqli: OK" || echo "❌ mysqli: Aún no funciona"
php -m | grep -i pdo_mysql > /dev/null && echo "✅ pdo_mysql: OK" || echo "❌ pdo_mysql: Aún no funciona"

echo ""
echo "📝 Si mysqli aún no funciona, verifica php.ini:"
echo "   php --ini"
echo ""
echo "   Y asegúrate de que estas líneas estén descomentadas:"
echo "   extension=mysqli"
echo "   extension=pdo_mysql"
