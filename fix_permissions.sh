#!/bin/bash
# Script para cambiar los permisos de los archivos del proyecto

# Cambiar el propietario de todos los archivos al usuario actual
sudo chown -R edisondejesusabreu:admin /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/

# Dar permisos de escritura al propietario
sudo chmod -R u+w /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/

echo "Permisos corregidos exitosamente"
