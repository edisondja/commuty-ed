-- Agregar campo para asignar reproductor a cada tablero
ALTER TABLE tableros ADD COLUMN id_reproductor INT NULL DEFAULT NULL;

-- Índice para mejorar búsquedas
ALTER TABLE tableros ADD INDEX idx_reproductor (id_reproductor);
