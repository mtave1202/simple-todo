CREATE DATABASE IF NOT EXISTS `kadai_todo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

use `kadai_todo`;

CREATE TABLE IF NOT EXISTS `todo` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `status` BOOLEAN NOT NULL default false,
    `title` varchar(100) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` datetime,
    PRIMARY KEY(`id`)
) engine=InnoDB;
