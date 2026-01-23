-- Tabla de calificaciones (ratings) para publicaciones
CREATE TABLE IF NOT EXISTS ratings (
  id_rating INT PRIMARY KEY AUTO_INCREMENT,
  id_user INT NOT NULL,
  id_tablero INT NOT NULL,
  puntuacion INT NOT NULL CHECK (puntuacion >= 1 AND puntuacion <= 5),
  estado VARCHAR(15) DEFAULT 'activo',
  fecha_rating DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_tablero) REFERENCES tableros(id_tablero) ON DELETE CASCADE,
  UNIQUE KEY unique_user_board_rating (id_user, id_tablero)
);

-- Índice para mejorar consultas de promedio
CREATE INDEX idx_tablero_estado ON ratings(id_tablero, estado);
