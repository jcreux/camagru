<?php
include_once "config/setup.php";
session_start();

$pref = $db->prepare("SELECT `pref` FROM users WHERE `login` = :login");
$pref->bindParam(':login', $_SESSION["loggued_on_user"]);
$pref->execute();
$mpref = $pref->fetch();

if ($mpref["pref"] === '1')
{
	$token = 0;
	$no = $db->prepare("UPDATE users SET `pref` = :no WHERE `login` = :login");
	$no->bindParam(':no', $token);
	$no->bindParam(':login', $_SESSION["loggued_on_user"]);
	$no->execute();
	header("Location: camagru.php?msg=0");
}
else
{
	$token = 1;
	$yes = $db->prepare("UPDATE users SET `pref` = :yes WHERE `login` = :login");
	$yes->bindParam(':yes', $token);
	$yes->bindParam(':login', $_SESSION["loggued_on_user"]);
	$yes->execute();
	header("Location: camagru.php?msg=1");
}
?>