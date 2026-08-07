-- Brigada contribution report breakdown items
CREATE TABLE IF NOT EXISTS `brigada_contribution_breakdown` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`id`),
  KEY `idx_report_id` (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
