<?php
include_once "config/setup.php";
include_once "functions/error.php";
include_once "functions/sendmail.php";

$login = $_POST["login"];
$email = $_POST["email"];
$passwd = $_POST["passwd"];
$cpasswd = $_POST["confirm_passwd"];

if ($_GET["check"] === "OK")
{
	if ($login && $email && $passwd && $cpasswd)
	{
		if (strlen($login) > 25)
			error(1);
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255)
			error(2);
		else if (login_exist($db, $login) === 1 || email_exist($db, $email) === 1)
			error(3);
		else if ($passwd !== $cpasswd)
			error(4);
		else if (preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $passwd) === 0 || strlen($passwd) > 25)
			error(5);
		else
		{
			$key = md5(microtime(TRUE) * 100000);
			$res = $db->prepare("INSERT INTO users (`login`, `email`, `passwd`, `token`, `key`) VALUES (:login, :email, :passwd, '0', :key);");
			$res->bindParam(':login', $login);
			$res->bindParam(':email', $email);
			$res->bindParam(':passwd', hash("whirlpool", $passwd));
			$res->bindParam(':key', $key);
			$res->execute();
			sendmail_1($email, $login, $key);
			header("Location: main.php");
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
			<form method="POST" action="register.php?check=OK">
				<input class="input" type="text" name="login" placeholder="Login" required /><br />
				<input class="input" type="email" name="email" placeholder="Email" required /><br />
				<input class="input" type="password" name="passwd" placeholder="Password" required /><br />
				<input class="input" type="password" name="confirm_passwd" placeholder="Confirm password" required /><br />
				<input class="button" type="submit" name="button" value="Register" />
			</form>
		</div>
	</body>
</html>