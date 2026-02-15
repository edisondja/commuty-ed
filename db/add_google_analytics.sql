-- Agregar campo para Google Analytics ID
ALTER TABLE configuracion 
ADD COLUMN google_analytics_id VARCHAR(50) DEFAULT NULL AFTER redis_cache;
