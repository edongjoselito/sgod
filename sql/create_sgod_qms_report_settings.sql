CREATE TABLE IF NOT EXISTS `one_sgod_qms_report_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_name` VARCHAR(255) NOT NULL,
  `sec_group` VARCHAR(50) NOT NULL DEFAULT 'SGOD',
  `letterhead_file` VARCHAR(255) NULL,
  `footer_file` VARCHAR(255) NULL,
  `monitored_by` VARCHAR(255) NULL,
  `reviewed_by` VARCHAR(255) NULL,
  `acknowledged_by` VARCHAR(255) NULL,
  `risk_prepared_by` VARCHAR(255) NULL,
  `risk_approved_by` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_section` (`section_name`, `sec_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
