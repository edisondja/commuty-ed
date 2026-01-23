#!/bin/bash
# Script para cambiar los permisos de todos los archivos del proyecto

echo "Cambiando propietario de todos los archivos..."
sudo chown -R edisondejesusabreu:admin /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/

echo "Dando permisos de escritura..."
sudo chmod -R u+w /Applications/XAMPP/xamppfiles/htdocs/commuty-ed/

echo "Permisos corregidos exitosamente para todo el proyecto"
