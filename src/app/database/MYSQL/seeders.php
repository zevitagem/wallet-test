<?php
$queries = array();
$queries[] = "INSERT INTO `type_transaction` (`description`, `active`) VALUES
('deposit', 1),
('transfer', 1),
('withdraw', 1);";