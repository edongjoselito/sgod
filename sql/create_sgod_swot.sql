CREATE TABLE IF NOT EXISTS `one_sgod_swot` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_name` VARCHAR(255) NOT NULL,
  `sec_group` VARCHAR(50) NOT NULL DEFAULT 'SGOD',
  `applicable_year` VARCHAR(10) NOT NULL,
  `effectivity_date` DATE NOT NULL,
  `swot_type` VARCHAR(20) NOT NULL,
  `description` TEXT NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_section_year` (`section_name`, `sec_group`, `applicable_year`),
  KEY `idx_swot_type` (`swot_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
