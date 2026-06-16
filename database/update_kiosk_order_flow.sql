-- Update database for the kiosk/self-order flow.
-- Import this if the database already exists.
-- Target database name follows .env: DB_DATABASE=laravel



CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_name` VARCHAR(255) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `customer_name` VARCHAR(255) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `items` JSON DEFAULT NULL,
  `payment_method` ENUM('debit','cashier') NOT NULL DEFAULT 'cashier',
  `cashier_name` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `total_price` DECIMAL(10,2) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `orders`
  ADD COLUMN IF NOT EXISTS `quantity` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `item_name`,
  ADD COLUMN IF NOT EXISTS `customer_name` VARCHAR(255) DEFAULT NULL AFTER `quantity`,
  ADD COLUMN IF NOT EXISTS `notes` TEXT DEFAULT NULL AFTER `customer_name`,
  ADD COLUMN IF NOT EXISTS `items` JSON DEFAULT NULL AFTER `notes`,
  ADD COLUMN IF NOT EXISTS `payment_method` ENUM('debit','cashier') NOT NULL DEFAULT 'cashier' AFTER `items`,
  ADD COLUMN IF NOT EXISTS `cashier_name` VARCHAR(255) DEFAULT NULL AFTER `payment_method`,
  ADD COLUMN IF NOT EXISTS `status` VARCHAR(255) NOT NULL DEFAULT 'pending' AFTER `cashier_name`,
  ADD COLUMN IF NOT EXISTS `total_price` DECIMAL(10,2) DEFAULT NULL AFTER `status`,
  ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `total_price`,
  ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;

ALTER TABLE `orders`
  MODIFY COLUMN `status` VARCHAR(255) NOT NULL DEFAULT 'pending';

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `is_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;

INSERT INTO `users` (`name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`)
VALUES ('Administrator', 'admin@resto.test', '$2y$10$vUko/4FZJ3bdtngwe7kuPeAxjB9SlESEUZO46XShMnZBV/VycY4Ni', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `password` = VALUES(`password`),
  `is_admin` = VALUES(`is_admin`),
  `updated_at` = NOW();
