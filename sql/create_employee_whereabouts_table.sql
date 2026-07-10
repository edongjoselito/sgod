-- Employee Whereabouts Table
CREATE TABLE IF NOT EXISTS `sgod_employee_whereabouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `lName` varchar(100) NOT NULL,
  `section` varchar(255) NOT NULL,
  `secGroup` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `activity` text NOT NULL,
  `status` enum('In Office','Out of Office','On Official Business','On Leave','On Field Work') NOT NULL DEFAULT 'In Office',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `date` (`date`),
  KEY `section` (`section`),
  KEY `secGroup` (`secGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
