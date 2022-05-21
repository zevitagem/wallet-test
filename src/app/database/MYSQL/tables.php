<?php
$queries = array();
$queries[] = "CREATE TABLE `account` (
  `id` int NOT NULL,
  `name` varchar(10) NOT NULL,
  `balance` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE `transaction` (
  `id` int NOT NULL,
  `type` varchar(10) NOT NULL,
  `origin` int DEFAULT NULL,
  `destination` int DEFAULT NULL,
  `amount` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "CREATE TABLE `type_transaction` (
  `description` varchar(10) NOT NULL,
  `active` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$queries[] = "ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);";

$queries[] = "ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);";

$queries[] = "ALTER TABLE `type_transaction`
  ADD UNIQUE KEY `unique_description` (`description`);";

$queries[] = "ALTER TABLE `transaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;";