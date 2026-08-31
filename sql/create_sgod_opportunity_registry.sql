CREATE TABLE IF NOT EXISTS `one_sgod_opportunity_registry` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `section_name` VARCHAR(255) NOT NULL, `sec_group` VARCHAR(50) NOT NULL DEFAULT 'SGOD', `username` VARCHAR(255) NOT NULL,
 `declared_process_opportunity` TEXT NOT NULL, `likelihood` TINYINT UNSIGNED NOT NULL DEFAULT 1, `impact` TINYINT UNSIGNED NOT NULL DEFAULT 1, `opportunity_rating` TINYINT UNSIGNED NOT NULL DEFAULT 1,
 `pursuit_action_plan` TEXT NULL, `person_responsible` VARCHAR(255) NULL, `target_date` DATE NULL, `created_at` DATETIME NOT NULL, `updated_at` DATETIME NULL,
 PRIMARY KEY (`id`), KEY `idx_section` (`section_name`,`sec_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
