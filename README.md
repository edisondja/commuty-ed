# Commuty-ED - Plataforma de Red Social

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/License-Proprietary-red)
![Status](https://img.shields.io/badge/Status-Production%20Ready-green)

**Commuty-ED** es una plataforma web moderna basada en arquitectura MVC que combina funcionalidades de red social con capacidades de comercio electrónico. Los usuarios pueden compartir contenido multimedia, interactuar mediante comentarios y calificaciones, gestionar publicaciones y administrar configuraciones del sitio desde un panel de administración completo.

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación Rápida](#-instalación-rápida)
- [Instalación Manual](#-instalación-manual)
- [Configuración de Producción](#-configuración-de-producción)
- [URLs Modernas](#-urls-modernas)
- [Servicios del Sistema](#-servicios-del-sistema)
- [SEO y Meta Tags](#-seo-y-meta-tags)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [API y Endpoints](#-api-y-endpoints)
- [Solución de Problemas](#-solución-de-problemas)

## ✨ Características Principales

### Red Social
- **Publicaciones Multimedia**: Compartir imágenes y videos con descripciones
- **Sistema de Comentarios**: Comentarios con respuestas anidadas (hilos)
- **Sistema de Calificación**: Calificación de 1 a 5 estrellas para publicaciones
- **Me Gusta y Favoritos**: Interacción social completa
- **Vistas y Estadísticas**: Seguimiento de visualizaciones
- **Perfiles de Usuario**: Perfiles personalizables con biografía
- **Vista Previa de Videos**: Activación automática en hover y touch
- **Compartir en Redes Sociales**: Facebook, Twitter, WhatsApp y Telegram

### Panel de Administración
- **Gestión de Publicaciones**: Aprobar, rechazar y moderar contenido
- **Configuración del Sitio**: Personalización completa de estilos y colores
- **Monitoreo RabbitMQ**: Supervisión de procesamiento multimedia
- **Gestión de Usuarios**: Administración de cuentas y permisos
- **Sistema de Reportes**: Gestión de reportes de contenido
- **Gestión de Banners**: Sistema de publicidad
- **Reproductores VAST**: Soporte para anuncios de video

### SEO y Optimización
- **URLs Amigables**: Rutas modernas como `/post/123/titulo`
- **Sitemap Automático**: Generación dinámica de `sitemap.xml`
- **Meta Tags Open Graph**: Imágenes y descripciones al compartir enlaces
- **Twitter Cards**: Previsualización optimizada para Twitter

### Tecnologías Avanzadas
- **Procesamiento Asíncrono**: RabbitMQ para videos e imágenes
- **Compresión de Videos**: Conversión automática a MP4 optimizado
- **Caché Redis**: Optimización de rendimiento
- **Sistema de Estilos Dinámicos**: Personalización en tiempo real

## 🔧 Requisitos del Sistema

### Servidor
- **PHP**: 7.4 o superior (compatible con PHP 8.2+)
- **MySQL/MariaDB**: 5.7 o superior
- **Apache**: Con mod_rewrite habilitado
- **Composer**: Para gestión de dependencias

### Servicios Opcionales (Recomendados)
- **Redis**: Sistema de caché
- **RabbitMQ**: Procesamiento asíncrono de multimedia
- **FFmpeg**: Procesamiento y compresión de videos

### Extensiones PHP Requeridas
```
mysqli, json, mbstring, gd, zip, fileinfo
```

## 🚀 Instalación Rápida

### Usando el Instalador Web

1. Sube los archivos al servidor
2. Visita `https://tudominio.com/install/`
3. Sigue las instrucciones del instalador
4. **Elimina la carpeta `install/` al finalizar**

## 📦 Instalación Manual

### 1. Clonar el Proyecto

```bash
cd /var/www
git clone [url-del-repositorio] commuty-ed
cd commuty-ed
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Importar Base de Datos

```bash
mysql -u root -p -e "CREATE DATABASE edcommunity CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p edcommunity < db/schema.sql
```

### 4. Configurar

Edita `config/config.php`:

```php
<?php
define("DOMAIN", "https://tudominio.com");
define("HOST_BD", "localhost");
define("USER_BD", "tu_usuario");
define("PASSWORD_BD", "tu_password");
define("NAME_DB", "edcommunity");
```

### 5. Permisos

```bash
sudo chown -R www-data:www-data /var/www/commuty-ed
sudo chmod -R 755 /var/www/commuty-ed
sudo chmod -R 775 uploads/ videos/ compile/ cache/ imagenes_tablero/ assets/
```

## 🌐 Configuración de Producción

### Apache VirtualHost

```apache
<VirtualHost *:80>
    ServerName tudominio.com
    ServerAlias www.tudominio.com
    DocumentRoot /var/www/commuty-ed

    <Directory /var/www/commuty-ed>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Habilitar Módulos

```bash
sudo a2enmod rewrite
sudo a2ensite tudominio.com.conf
sudo systemctl restart apache2
```

### Configurar .htaccess para Producción

En el archivo `.htaccess`, cambiar:

```apache
# De (desarrollo):
RewriteBase /commuty-ed/

# A (producción):
RewriteBase /
```

### Actualizar Dominio en Base de Datos

```sql
UPDATE configuracion SET dominio = 'https://tudominio.com' WHERE id_config = 1;
```

## 🔗 URLs Modernas

El sistema usa URLs amigables:

| Tipo | URL |
|------|-----|
| Publicación | `/post/123/titulo-del-post` |
| Perfil | `/profile/usuario` |
| Paginación | `/page/2` |
| Búsqueda | `/search/termino` |
| Admin | `/admin` |
| Admin Usuarios | `/admin/users` |
| Admin Posts | `/admin/boards` |
| Admin Config | `/admin/settings` |

## ⚙️ Servicios del Sistema

### Instalar Servicios de Procesamiento

Los consumers procesan videos y multimedia de forma asíncrona.

```bash
cd /var/www/commuty-ed/systemd
sudo chmod +x *.sh
sudo ./install-services.sh
```

### Comandos de Gestión

```bash
# Ver estado
sudo systemctl status commuty-consumer
sudo systemctl status commuty-resultado

# Ver logs
sudo journalctl -u commuty-consumer -f

# Reiniciar
sudo systemctl restart commuty-consumer

# Detener
sudo systemctl stop commuty-consumer
```

### Desinstalar Servicios

```bash
cd /var/www/commuty-ed/systemd
sudo ./uninstall-services.sh
```

## 📊 SEO y Meta Tags

### Sitemap Automático

El sitemap se genera automáticamente en:
```
https://tudominio.com/sitemap.xml
```

Incluye:
- Todas las publicaciones activas con imágenes
- Perfiles de usuarios
- Páginas principales

### Meta Tags Open Graph

Cuando compartes un enlace en redes sociales, se mostrará:
- Imagen de la publicación (primera imagen o preview del video)
- Título de la publicación
- Descripción
- URL canónica

### Validar Meta Tags

- **Facebook**: https://developers.facebook.com/tools/debug/
- **Twitter**: https://cards-dev.twitter.com/validator
- **LinkedIn**: https://www.linkedin.com/post-inspector/

### Robots.txt

El archivo `robots.txt` está configurado para:
- Permitir indexación de contenido público
- Bloquear directorios sensibles (admin, controllers, config)
- Apuntar al sitemap

## 📁 Estructura del Proyecto

```
commuty-ed/
├── assets/                 # Archivos estáticos (logos, imágenes)
├── cache/                  # Caché de Smarty
├── compile/                # Templates compilados
├── config/                 # Configuración
│   └── config.php
├── controllers/            # Controladores API
│   └── actions_board.php   # API principal
├── css/                    # Estilos CSS
├── db/                     # Scripts de base de datos
│   └── schema.sql          # Esquema completo
├── imagenes_tablero/       # Imágenes de publicaciones
├── install/                # Instalador web
├── js/                     # JavaScript frontend
├── models/                 # Modelos de datos
├── systemd/                # Servicios para Ubuntu
│   ├── commuty-consumer.service
│   ├── commuty-resultado.service
│   ├── install-services.sh
│   └── uninstall-services.sh
├── template/               # Plantillas Smarty
├── uploads/                # Archivos temporales
├── videos/                 # Videos procesados
├── vendor/                 # Dependencias Composer
├── .htaccess               # Configuración Apache
├── bootstrap.php           # Inicialización
├── consumer_service.php    # Procesador de multimedia
├── consumer_resultado.php  # Procesador de resultados
├── index.php               # Página principal
├── single_board.php        # Vista de publicación
├── sitemap.php             # Generador de sitemap
└── robots.txt              # Configuración para buscadores
```

## 🔌 API y Endpoints

### Estructura

Todas las peticiones van a `/controllers/actions_board.php`:

```javascript
const formData = new FormData();
formData.append('action', 'nombre_accion');
formData.append('param1', 'valor1');

axios.post(`${baseUrl}/controllers/actions_board.php`, formData);
```

### Endpoints Principales

| Acción | Descripción |
|--------|-------------|
| `create_board` | Crear publicación |
| `update_board` | Actualizar publicación |
| `delete_board` | Eliminar publicación |
| `save_post` | Guardar comentario |
| `reply_coment` | Responder comentario |
| `save_rating` | Guardar calificación |
| `get_rating_average` | Obtener promedio |
| `like_board` | Dar like |
| `search_boards` | Buscar publicaciones |
| `search_users` | Buscar usuarios |

## 🐛 Solución de Problemas

### Error 404 en rutas amigables

1. Verificar que `mod_rewrite` está habilitado:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

2. Verificar `AllowOverride All` en VirtualHost

3. Verificar `RewriteBase` en `.htaccess`

### Videos no se procesan

1. Verificar RabbitMQ:
```bash
sudo systemctl status rabbitmq-server
```

2. Verificar consumers:
```bash
sudo systemctl status commuty-consumer
```

3. Ver logs:
```bash
tail -f /var/log/commuty/consumer-error.log
```

### Error de permisos al subir archivos

```bash
sudo chown -R www-data:www-data /var/www/commuty-ed
sudo chmod -R 775 uploads/ videos/ imagenes_tablero/
```

### Imágenes no aparecen al compartir

1. Verificar que la imagen existe y es accesible públicamente
2. Usar el debugger de Facebook para refrescar caché
3. Crear imagen por defecto: `assets/default_share.png` (1200x630px)

### Limpiar caché de Smarty

```bash
rm -rf /var/www/commuty-ed/compile/*
rm -rf /var/www/commuty-ed/cache/*
```

## 📝 Licencia

Este proyecto es de propiedad privada. Todos los derechos reservados.

Copyright © 2026 Meneito.com. All Rights Reserved.

## 📞 Soporte

- **Sitio Web**: https://meneito.com
- **Email**: soporte@meneito.com

---

**Desarrollado con ❤️ para la comunidad**
