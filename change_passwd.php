<?php
include_once "config/setup.php";
include_once "functions/error.php";

session_start();
$old_login = $_SESSION["loggued_on_user"];
$passwd = $_POST["passwd"];
$cpasswd = $_POST["confirm_passwd"];

if ($old_login === "")
	header("Location: main.php");
if ($_GET["check"] === "OK")
{
	if ($passwd && $cpasswd)
	{
		if ($passwd !== $cpasswd)
			error(4);
		else if (preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $passwd) === 0 || strlen($passwd) > 25)
			error(5);
		else
		{
			$res = $db->prepare("UPDATE users SET `passwd` = :passwd WHERE `login` = :old_login");
			$res->bindParam(':passwd', hash("whirlpool", $passwd));
			$res->bindParam(':old_login', $old_login);
			$res->execute();
			print("Mot de passe modifié !");
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
			<form method="POST" action="change_passwd.php?check=OK">
				<input class="input" type="passwd" name="passwd" placeholder="New Password" required /><br />
				<input class="input" type="password" name="confirm_passwd" placeholder="Confirm password" required /><br />
				<input class="button" type="submit" name="button" value="Update" />
			</form>
			<form action="logout.php">
				<input class="button" id="signout" type="submit" name="button" value="Sign out" /><br />
			</form>
		</div>
	</body>
</html>