-- Script para agregar el campo estilos_json a la tabla configuracion
-- Ejecutar este script en la base de datos para habilitar la funcionalidad de estilos personalizados

ALTER TABLE configuracion 
ADD COLUMN estilos_json TEXT NULL 
AFTER redis_cache;
