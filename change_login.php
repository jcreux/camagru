<?php
include_once "config/setup.php";
include_once "functions/error.php";

session_start();
$old_login = $_SESSION["loggued_on_user"];
$login = $_POST["login"];

if ($old_login === "")
	header("Location: main.php");
if ($_GET["check"] === "OK")
{
	if ($login)
	{
		if (strlen($login) > 25)
			error(1);
		else if (login_exist($db, $login) === 1)
			error(3);
		else
		{
			$res = $db->prepare("UPDATE users SET `login` = :login WHERE `login` = :old_login");
			$res->bindParam(':login', $login);
			$res->bindParam(':old_login', $old_login);
			$res->execute();

			$chlogin = $db->prepare("UPDATE photos SET `owner` = :login WHERE `owner` = :old_login");
			$chlogin->bindParam(':login', $login);
			$chlogin->bindParam(':old_login', $old_login);
			$chlogin->execute();

			$_SESSION["loggued_on_user"] = $login;
			print("Nom d'utilisateur modifié !");
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
			<form method="POST" action="change_login.php?check=OK">
				<input class="input" type="text" name="login" placeholder="New login" required /><br />
				<input class="button" type="submit" name="button" value="Update" />
			</form>
			<form action="logout.php">
				<input class="button" id="signout" type="submit" name="button" value="Sign out" /><br />
			</form>
		</div>
	</body>
</html>