# Commuty-ED - Plataforma de Red Social y Comercio

![PHP Version](https://img.shields.io/badge/PHP-7.2%2B-blue)
![License](https://img.shields.io/badge/License-Proprietary-red)
![Status](https://img.shields.io/badge/Status-Active-green)

**Commuty-ED** es una plataforma web moderna basada en arquitectura MVC que combina funcionalidades de red social con capacidades de comercio electrónico. Los usuarios pueden compartir contenido multimedia, interactuar mediante comentarios y calificaciones, gestionar publicaciones y administrar configuraciones del sitio desde un panel de administración completo.

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Base de Datos](#-base-de-datos)
- [API y Endpoints](#-api-y-endpoints)
- [Uso y Ejemplos](#-uso-y-ejemplos)
- [Desarrollo](#-desarrollo)
- [Solución de Problemas](#-solución-de-problemas)
- [Contribución](#-contribución)
- [Licencia](#-licencia)

## ✨ Características Principales

### Red Social
- **Publicaciones Multimedia**: Compartir imágenes y videos con descripciones
- **Sistema de Comentarios**: Comentarios con respuestas anidadas (hilos)
- **Sistema de Calificación**: Calificación de 1 a 5 estrellas para publicaciones
- **Me Gusta y Favoritos**: Interacción social básica
- **Vistas y Estadísticas**: Seguimiento de visualizaciones
- **Perfiles de Usuario**: Perfiles personalizables con biografía

### Panel de Administración
- **Gestión de Publicaciones**: Aprobar, rechazar y moderar contenido
- **Configuración del Sitio**: Personalización completa de estilos, colores y configuraciones
- **Monitoreo RabbitMQ**: Supervisión y control de servicios de procesamiento multimedia
- **Gestión de Usuarios**: Administración de cuentas y permisos
- **Sistema de Reportes**: Gestión de reportes de contenido

### Tecnologías Avanzadas
- **Procesamiento Asíncrono**: RabbitMQ para procesamiento de videos e imágenes
- **Caché Redis**: Optimización de rendimiento con caché distribuido
- **Sistema de Estilos Dinámicos**: Personalización de colores y estilos en tiempo real
- **Vista Previa de Videos**: Activación automática en hover y touch
- **Compartir en Redes Sociales**: Integración con Facebook, Twitter, WhatsApp y Telegram

## 🔧 Requisitos del Sistema

### Servidor
- **PHP**: 7.2 o superior (compatible con PHP 8+)
- **MySQL/MariaDB**: 5.7 o superior
- **Apache/Nginx**: Con mod_rewrite habilitado
- **Composer**: Para gestión de dependencias

### Servicios Opcionales
- **Redis**: Para sistema de caché (opcional pero recomendado)
- **RabbitMQ**: Para procesamiento asíncrono de multimedia (opcional)
- **FFmpeg**: Para procesamiento de videos (opcional)

### Extensiones PHP Requeridas
- `mysqli`
- `json`
- `mbstring`
- `gd` o `imagick` (para procesamiento de imágenes)
- `zip` (para descargas)

## 📦 Instalación

### 1. Clonar o Descargar el Proyecto

```bash
cd /ruta/de/tu/servidor/web
git clone [url-del-repositorio] commuty-ed
# O descargar y extraer el archivo ZIP
```

### 2. Instalar Dependencias

```bash
cd commuty-ed
composer install
```

Esto instalará todas las dependencias necesarias:
- Smarty (Motor de plantillas)
- Firebase JWT (Autenticación)
- PHPMailer (Envío de correos)
- Predis (Cliente Redis)
- php-amqplib (Cliente RabbitMQ)
- FPDF/FPDI (Generación de PDFs)

### 3. Configurar Permisos

```bash
# Dar permisos a directorios necesarios
chmod -R 777 cache/
chmod -R 777 compile/
chmod -R 777 assets/
chmod -R 777 imagenes_tablero/
chmod -R 777 videos/
chmod -R 777 uploads/
chmod -R 777 traking/

# O usar el script proporcionado
chmod +x fix_all_permissions.sh
./fix_all_permissions.sh
```

### 4. Configurar Base de Datos

```bash
# Importar esquema de base de datos
mysql -u root -p < database/db.sql

# Importar tablas adicionales (si existen)
mysql -u root -p edcommunity < database/add_ratings_table.sql
mysql -u root -p edcommunity < database/add_estilos_json.sql
```

### 5. Configurar el Proyecto

Editar `config/config.php` con tus configuraciones (ver sección de Configuración).

## ⚙️ Configuración

### Archivo de Configuración Principal

Edita `config/config.php` con tus valores:

```php
<?php
// Dominio del sitio
define("DOMAIN", "http://localhost/commuty-ed");

// Configuración de Base de Datos
define("HOST_BD", "localhost");
define("USER_BD", "root");
define("PASSWORD_BD", "tu_password");
define("NAME_DB", "edcommunity");

// Configuración del Sitio
define("NAME_SITE", "Ventas RD");
define("DESCRIPTION_SLOGAN", "El mejor lugar para comprar tus articulos");
define("DESCRIPTION_SITE", "Nunca vender fue tan facil como en ventasrd");
define("LOGOSITE", DOMAIN."/assets/ventasRD.png");
define("FAVICON", DOMAIN."/assets/favicon.ico");

// Configuración RabbitMQ (Opcional)
define('host_rabbit_mq', 'localhost');
define('port_rabbit_mq', '5672');
define('user_rabbit_mq', 'guest');
define('password_rabbit_mq', 'guest');

// Configuración Redis (Opcional)
define("host_redis_cache", "localhost");
define("port_redis_cache", "6379");
define("scheme_redis_cache", "tcp");
?>
```

### Configuración PHP

Asegúrate de que `php.ini` tenga estos valores:

```ini
upload_max_filesize = 250M
post_max_size = 250M
max_execution_time = 300
memory_limit = 256M
```

### Configuración de Servicios

#### Redis (Opcional)
```bash
# Instalar Redis
sudo apt-get install redis-server  # Ubuntu/Debian
brew install redis                 # macOS

# Iniciar Redis
redis-server
```

#### RabbitMQ (Opcional)
```bash
# Instalar RabbitMQ
sudo apt-get install rabbitmq-server  # Ubuntu/Debian
brew install rabbitmq                # macOS

# Iniciar RabbitMQ
sudo systemctl start rabbitmq-server
```

#### Iniciar Servicios de Procesamiento

```bash
# Procesador de multimedia
php consumer_service.php

# O ejecutar en segundo plano
nohup php consumer_service.php > /dev/null 2>&1 &
```

## 📁 Estructura del Proyecto

```
commuty-ed/
├── assets/                 # Archivos estáticos (imágenes, logos, favicon)
├── cache/                  # Caché de Smarty
├── compile/                # Archivos compilados de Smarty
├── config/                 # Archivos de configuración
│   └── config.php         # Configuración principal
├── controllers/            # Controladores y lógica de negocio
│   ├── actions_board.php  # API principal (endpoints)
│   ├── rabbitmq_monitor.php
│   └── libs/              # Librerías auxiliares
├── database/              # Scripts SQL
│   ├── db.sql            # Esquema principal
│   ├── add_ratings_table.sql
│   └── add_estilos_json.sql
├── imagenes_tablero/      # Imágenes de publicaciones
├── videos/               # Videos de publicaciones
├── js/                    # JavaScript del frontend
│   ├── BoardOperation.js
│   ├── comments_system.js
│   ├── rating_system.js
│   └── ...
├── models/                # Modelos de datos (MVC)
│   ├── Board.php
│   ├── User.php
│   ├── Coment.php
│   ├── Rating.php
│   └── ...
├── template/              # Plantillas Smarty
│   ├── header.tpl        # Template principal
│   ├── board.tpl
│   ├── single_board.tpl
│   └── back_office_components/
├── traking/              # Logs del sistema
├── uploads/              # Archivos subidos
├── vendor/               # Dependencias de Composer
├── bootstrap.php         # Inicialización de la aplicación
├── index.php            # Punto de entrada principal
├── single_board.php     # Vista de publicación individual
├── backcoffe.php        # Panel de administración
└── composer.json        # Dependencias PHP
```

## 🛠 Tecnologías Utilizadas

### Backend
- **PHP 7.2+**: Lenguaje principal del servidor
- **MySQL/MariaDB**: Base de datos relacional
- **Smarty 3.1**: Motor de plantillas
- **Composer**: Gestor de dependencias PHP

### Frontend
- **JavaScript (ES6+)**: Lógica del cliente
- **Axios**: Cliente HTTP para peticiones AJAX
- **Bootstrap 5**: Framework CSS
- **Font Awesome**: Iconos
- **Alertify.js**: Notificaciones

### Servicios y Herramientas
- **Redis**: Sistema de caché
- **RabbitMQ**: Cola de mensajes para procesamiento asíncrono
- **FFmpeg**: Procesamiento de video (opcional)
- **JWT (Firebase)**: Autenticación basada en tokens
- **PHPMailer**: Envío de correos electrónicos

## 🗄 Base de Datos

### Tablas Principales

- **users**: Usuarios del sistema
- **tableros**: Publicaciones/Posts
- **comentarios**: Comentarios principales
- **reply_coment**: Respuestas a comentarios
- **ratings**: Calificaciones de publicaciones
- **likes**: Me gusta
- **favoritos**: Favoritos de usuarios
- **configuracion**: Configuración del sitio
- **asignar_multimedia_t**: Multimedia asociada a publicaciones

### Scripts de Base de Datos

```bash
# Crear base de datos completa
mysql -u root -p < database/db.sql

# Agregar tabla de calificaciones
mysql -u root -p edcommunity < database/add_ratings_table.sql

# Agregar columna de estilos JSON
mysql -u root -p edcommunity < database/add_estilos_json.sql
```

## 🔌 API y Endpoints

### Estructura de API

Todas las peticiones se realizan a `controllers/actions_board.php` con el parámetro `action`:

```javascript
axios.post(`${dominio}/controllers/actions_board.php`, {
    action: 'nombre_accion',
    // otros parámetros
})
```

### Endpoints Principales

#### Publicaciones
- `create_board`: Crear nueva publicación
- `cargar_un_tablero`: Obtener publicación individual
- `update_board`: Actualizar publicación
- `delete_board`: Eliminar publicación

#### Comentarios
- `save_post`: Guardar comentario
- `load_coments`: Cargar comentarios de una publicación
- `reply_coment`: Responder a un comentario
- `delete_coment`: Eliminar comentario

#### Calificaciones
- `save_rating`: Guardar calificación (1-5)
- `get_rating_average`: Obtener promedio de calificaciones
- `get_my_rating`: Obtener calificación del usuario actual

#### Usuarios
- `login_user`: Iniciar sesión
- `register_user`: Registrar nuevo usuario
- `load_user_info`: Cargar información de usuario

#### Configuración
- `config_site_text`: Guardar configuración del sitio
- `config_load_site`: Cargar configuración
- `save_styles`: Guardar estilos personalizados
- `load_styles`: Cargar estilos

### Ejemplo de Uso

```javascript
// Crear publicación
const formData = new FormData();
formData.append('action', 'create_board');
formData.append('descripcion', 'Mi nueva publicación');
formData.append('media', fileInput.files[0]);

axios.post(`${dominio}/controllers/actions_board.php`, formData, {
    headers: {
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
})
.then(response => {
    console.log('Publicación creada:', response.data);
})
.catch(error => {
    console.error('Error:', error);
});
```

## 💻 Uso y Ejemplos

### Crear una Nueva Página

1. **Crear el Template** (`template/mi_pagina.tpl`):
```smarty
<div class="container">
    <h1>{$titulo}</h1>
    <p>{$descripcion}</p>
</div>
```

2. **Crear el Controlador** (`mi_pagina.php`):
```php
<?php
require('bootstrap.php');

$smarty->assign('titulo', 'Mi Página');
$smarty->assign('descripcion', 'Descripción de mi página');
$smarty->assign('content_config', 'mi_pagina');
$smarty->display('../template/header.tpl');
?>
```

3. **Registrar en `header.tpl`**:
```smarty
{if $content_config == 'mi_pagina'}
    {include file="mi_pagina.tpl"}
{/if}
```

### Crear un Nuevo Endpoint API

En `controllers/actions_board.php`:

```php
switch ($action) {
    case 'mi_nueva_accion':
        header('Content-Type: application/json');
        
        // Tu lógica aquí
        $resultado = ['success' => true, 'data' => $datos];
        
        echo json_encode($resultado);
        break;
}
```

### Autenticación con JWT

```javascript
// Login
axios.post(`${dominio}/controllers/actions_board.php`, {
    action: 'login_user',
    usuario: 'usuario',
    clave: 'password_md5'
})
.then(response => {
    localStorage.setItem('token', response.data.token);
});

// Usar token en peticiones
axios.post(url, data, {
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
    }
});
```

## 🔨 Desarrollo

### Arquitectura MVC

El proyecto sigue una arquitectura Modelo-Vista-Controlador:

- **Modelos** (`models/`): Lógica de negocio y acceso a datos
- **Vistas** (`template/`): Plantillas Smarty para presentación
- **Controladores** (`controllers/`): Coordinación entre modelos y vistas

### Convenciones de Código

- **PHP**: PSR-1 y PSR-2 (parcialmente)
- **JavaScript**: ES6+ con funciones modernas
- **Nombres de archivos**: snake_case para PHP, camelCase para JS
- **Base de datos**: Nombres de tablas en plural (`users`, `tableros`)

### Debugging

```php
// Habilitar errores (config/config.php)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logging personalizado
$this->TrackingLog('Mensaje de debug', 'eventos');
```

### Testing

```bash
# Verificar sintaxis PHP
php -l archivo.php

# Verificar permisos
ls -la cache/ compile/ assets/
```

## 🐛 Solución de Problemas

### Error: "Call to a member function bind_param() on boolean"
- **Causa**: Error en la preparación de consulta SQL
- **Solución**: Verificar conexión a base de datos y sintaxis SQL

### Error: "Permission denied" al subir archivos
- **Causa**: Permisos incorrectos en directorios
- **Solución**: 
```bash
chmod -R 777 assets/ imagenes_tablero/ videos/ uploads/
```

### Error: "Smarty: unable to write file"
- **Causa**: Permisos en directorios de Smarty
- **Solución**:
```bash
chmod -R 777 cache/ compile/
```

### Error: "Incorrect integer value" en autenticacion_ssl
- **Causa**: Tipo de dato incorrecto en base de datos
- **Solución**: El sistema convierte automáticamente "si"/"no" a 1/0

### Videos no se procesan
- **Causa**: RabbitMQ o consumer_service no está corriendo
- **Solución**: 
```bash
php consumer_service.php
# O iniciar desde panel de administración
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guías de Contribución

- Seguir las convenciones de código existentes
- Documentar funciones nuevas
- Probar cambios antes de hacer commit
- Actualizar README si es necesario

## 📝 Licencia

Este proyecto es de propiedad privada. Todos los derechos reservados.

Copyright © 2024 VentasRD. All Rights Reserved.

## 📞 Soporte

Para soporte, contacta a:
- **Email**: jhon@ventasrd.com
- **Sitio Web**: [Ventas RD](http://localhost/commuty-ed)

## 🙏 Agradecimientos

- Smarty Template Engine
- Bootstrap Team
- Todos los contribuidores de las librerías utilizadas

---

**Desarrollado con ❤️ para la comunidad**
