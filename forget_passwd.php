<?php
include_once "config/setup.php";
include_once "functions/error.php";
include_once "functions/sendmail.php";

$email = $_POST["email"];
$new_passwd = $_POST["new_passwd"];
$cnew_passwd = $_POST["confirm_passwd"];

if ($_GET["check"] === "OK_1")
{
	if ($email && $new_passwd && $cnew_passwd)
	{
		$res1 = $db->prepare("SELECT `email` FROM users WHERE `email` = :email;");
		$res1->bindParam(':email', $email);
		$res1->execute();
		if ($res1->rowCount() === 0)
			print("Email non valide.");
		else if ($new_passwd !== $cnew_passwd)
			error(4);
		else if (preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $new_passwd) === 0 || strlen($new_passwd) > 25)
			error(5);
		else
			sendmail_2($email, hash("whirlpool", $new_passwd));
	}
	else
		error(0);
}
else if ($_GET["check"] === "OK_2")
{
	$email = $_GET["email"];
	$passwd = $_GET["passwd"];
	
	$res2 = $db->prepare("UPDATE users SET `passwd` = :passwd WHERE `email` = :email;");
	$res2->bindParam(':email', $email);
	$res2->bindParam(':passwd', $passwd);
	$res2->execute();
	print("Nouveau mot de passe actif.");
}
?>

<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="header">
			<form method="POST" action="forget_passwd.php?check=OK_1">
				<input class="input" type="email" name="email" placeholder="Email" required /><br />
				<input class="input" type="password" name="new_passwd" placeholder="New password" required /><br />
				<input class="input" type="password" name="confirm_passwd" placeholder="Confirm password" required /><br />
				<input class="button" type="submit" name="button" value="Send" />
			</form>
		</div>
	</body>
</html>