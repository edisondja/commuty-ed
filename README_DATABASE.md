# Instrucciones para crear la base de datos

## Opción 1: Usar phpMyAdmin (Recomendado)

1. Inicia XAMPP y asegúrate de que MySQL esté corriendo
2. Abre phpMyAdmin en: http://localhost/phpmyadmin
3. Selecciona o crea la base de datos `edcommunity`
4. Ve a la pestaña "SQL"
5. Copia y pega el contenido del archivo `database/db.sql`
6. Haz clic en "Ejecutar"

## Opción 2: Usar la línea de comandos de MySQL

```bash
# Conectarte a MySQL de XAMPP
/Applications/XAMPP/xamppfiles/bin/mysql -u root

# Luego ejecutar:
source /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/database/db.sql
```

O directamente:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root < /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/database/db.sql
```

## Opción 3: Ejecutar el script PHP (después de iniciar MySQL)

```bash
# Asegúrate de que MySQL esté corriendo en XAMPP
php setup_database.php
```

## Verificar que las tablas se crearon

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "USE edcommunity; SHOW TABLES;"
```
