<?php
include_once "config/setup.php";
include_once "functions/sendmail.php";

$email = $_POST["email"];

if ($_GET["check"] === "OK")
{
	$res1 = $db->prepare("SELECT `login` FROM users WHERE `email` = :email;");
	$res1->bindParam(':email', $email);
	$res1->execute();
	if ($res1->rowCount() === 0)
		print("Cette email n'est pas associé.");
	else
	{
		$res2 = $db->prepare("SELECT `login` FROM users WHERE `email` = :email AND `token` = '1';");
		$res2->bindParam(':email', $email);
		$res2->execute();
		if ($res2->rowCount() !== 0)
			print("Ce compte est déjà activé.");
		else
		{
			$res3 = $db->prepare("SELECT `login` FROM users WHERE `email` = :email;");
			$res3->bindParam(':email', $email);
			$res3->execute();
			$login = $res3->fetch();
			$key = md5(microtime(TRUE) * 100000);
			$res4 = $db->prepare("UPDATE users SET `key` = :key WHERE `email` = :email");
			$res4->bindParam(':email', $email);
			$res4->bindParam(':key', $key);
			$res4->execute();
			sendmail_1($email, $login[0], $key);
			print("Lien d'activation envoyé!");
		}
	}
}
?>

<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="header">
			<form method="POST" action="change_key.php?check=OK">
				<input class="input" type="email" name="email" placeholder="Email" required /><br />
				<input class="button" type="submit" name="button" value="Send" />
			</form>
		</div>
	</body>
</html>