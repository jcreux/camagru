<?php
include_once "config/setup.php";
include_once "functions/error.php";

session_start();
$login = $_POST["login"];
$passwd = $_POST["passwd"];

if ($_GET["check"] === "OK")
{
	if ($login && $passwd)
	{
		$res1 = $db->prepare("SELECT `login` FROM users WHERE `login` = :login;");
		$res1->bindParam(':login', $login);
		$res1->execute();
		if ($res1->rowCount() === 0)
			print("Identifiant invalide.");
		else
		{
			$res2 = $db->prepare("SELECT `login` FROM users WHERE `login` = :login AND `passwd` = :passwd;");
			$res2->bindParam(':login', $login);
			$res2->bindParam(':passwd', hash("whirlpool", $passwd));
			$res2->execute();
			if ($res2->rowCount() === 0)
				print("Mot de passe invalide.");
			else
			{
				$res3 = $db->prepare("SELECT `token` FROM users WHERE `login` = :login AND `token` = '1';");
				$res3->bindParam(':login', $login);
				$res3->execute();
				if ($res3->rowCount() === 0)
					print("Compte non activé.");
				else
				{
					$_SESSION["loggued_on_user"] = $login;
					header("Location: camagru.php");
				}
			}
		}
	}
	else
		error(0);
}
?>

<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="header">
			<form method="POST" action="login.php?check=OK">
				<input class="input" type="text" name="login" placeholder="Login" required /><br />
				<input class="input" type="password" name="passwd" placeholder="Password" required /><br />
				<input class="button" type="submit" name="button" value="Login" /><br />
				<a class="link" href="forget_passwd.php">Mot de passe oublié ?</a><br />
				<a class="link" href="change_key.php">Renvoyer un email de confirmation</a>
			</form>
		</div>
	</body>
</html>