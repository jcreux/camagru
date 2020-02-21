<?php
include_once "config/setup.php";

$db->exec("CREATE TABLE IF NOT EXISTS users(
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(25) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`passwd` VARCHAR(128) NOT NULL,
	`token` ENUM('0', '1') NOT NULL,
	`pref` ENUM('1', '0') NOT NULL,
	`key` VARCHAR(32) NOT NULL);"
);

$db->exec("CREATE TABLE IF NOT EXISTS photos(
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`owner` VARCHAR(25) NOT NULL,
	`name` VARCHAR(32) NOT NULL);"
);

$db->exec("CREATE TABLE IF NOT EXISTS actions(
	`name` VARCHAR(32) NOT NULL,
	`action` ENUM('like', 'comment') NOT NULL,
	`id_user` INT NOT NULL,
	`content` TEXT NOT NULL);"
);

header("Location: main.php");
?>