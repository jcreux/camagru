<?php
include_once "config/setup.php";

$login = $_GET["login"];
$key = $_GET["key"];

$res1 = $db->prepare("SELECT `key` FROM users WHERE `login` = :login AND `key` = :key;");
$res1->bindParam(':login', $login);
$res1->bindParam(':key', $key);
$res1->execute();
if ($res1->rowCount() === 0)
	print("Lien d'activation non valide.");
else
{
	$res2 = $db->prepare("UPDATE users SET `token` = '1' WHERE `login` = :login");
	$res2->bindParam(':login', $login);
	$res2->execute();
	print("Compte activé!");
}
?>