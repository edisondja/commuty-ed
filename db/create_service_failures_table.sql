-- ============================================
-- TABLA: service_failures (Registro de fallos de servicios)
-- ============================================
CREATE TABLE IF NOT EXISTS `service_failures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `service_name` varchar(100) NOT NULL,
  `error_type` varchar(50) DEFAULT NULL,
  `error_message` text NOT NULL,
  `stack_trace` text DEFAULT NULL,
  `additional_data` json DEFAULT NULL,
  `resolved` tinyint(1) DEFAULT 0,
  `resolved_at` datetime DEFAULT NULL,
  `resolved_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_service` (`service_name`),
  KEY `idx_timestamp` (`timestamp`),
  KEY `idx_resolved` (`resolved`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
