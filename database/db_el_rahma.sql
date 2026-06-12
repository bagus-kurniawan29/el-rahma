CREATE DATABASE IF NOT EXISTS `db_el_rahma`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_el_rahma`;

CREATE TABLE IF NOT EXISTS `branches` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_name_unique` (`name`),
  UNIQUE KEY `branches_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','member') NOT NULL,
  `branch_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_index` (`branch_id`),
  CONSTRAINT `fk_users_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `branch_id` INT UNSIGNED NOT NULL,
  `amount` BIGINT UNSIGNED NOT NULL,
  `term_months` TINYINT UNSIGNED NOT NULL,
  `purpose` TEXT NOT NULL,
  `activation_code` VARCHAR(30) NOT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` TEXT DEFAULT NULL,
  `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  `reviewed_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applications_activation_unique` (`activation_code`),
  KEY `applications_branch_status_index` (`branch_id`,`status`),
  KEY `applications_user_index` (`user_id`),
  CONSTRAINT `fk_applications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_applications_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `fk_applications_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(128) NOT NULL,
  `data` LONGTEXT NOT NULL,
  `last_activity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `branches` (`id`, `name`, `slug`) VALUES
  (1, 'Rempung', 'rempung'),
  (2, 'Pringgabaya', 'pringgabaya'),
  (3, 'Mataram', 'mataram'),
  (4, 'Mamben', 'mamben');

INSERT IGNORE INTO `users` (`name`, `email`, `phone`, `password`, `role`, `branch_id`) VALUES
  ('Admin Rempung', 'admin.rempung@elrahma.id', NULL, '$2y$10$VfScPM2pyGC04rB8XgzkUe8uANbPOTvjvPO.17L/m/ARglcfbrLfa', 'admin', 1),
  ('Admin Pringgabaya', 'admin.pringgabaya@elrahma.id', NULL, '$2y$10$VfScPM2pyGC04rB8XgzkUe8uANbPOTvjvPO.17L/m/ARglcfbrLfa', 'admin', 2),
  ('Admin Mataram', 'admin.mataram@elrahma.id', NULL, '$2y$10$VfScPM2pyGC04rB8XgzkUe8uANbPOTvjvPO.17L/m/ARglcfbrLfa', 'admin', 3),
  ('Admin Mamben', 'admin.mamben@elrahma.id', NULL, '$2y$10$VfScPM2pyGC04rB8XgzkUe8uANbPOTvjvPO.17L/m/ARglcfbrLfa', 'admin', 4),
  ('Member Demo', 'member@elrahma.id', '081234567890', '$2y$10$KeXImNLuMOX4S4nH12WyfOPExEoD60vJLk.bCP8A27FNgy48SXA42', 'member', NULL);
