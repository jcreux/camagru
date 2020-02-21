<?php
include_once "config/setup.php";
include_once "functions/error.php";

session_start();
$old_login = $_SESSION["loggued_on_user"];
$email = $_POST["email"];

if ($old_login === "")
	header("Location: main.php");
if ($_GET["check"] === "OK")
{
	if ($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255)
			error(2);
		else if (email_exist($db, $email) === 1)
			error(3);
		else
		{
			$res = $db->prepare("UPDATE users SET `email` = :email WHERE `login` = :old_login");
			$res->bindParam(':email', $email);
			$res->bindParam(':old_login', $old_login);
			$res->execute();
			print("Email modifié !");
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
			<form method="POST" action="change_email.php?check=OK">
				<input class="input" type="email" name="email" placeholder="New email" required /><br />
				<input class="button" type="submit" name="button" value="Update" />
			</form>
			<form action="logout.php">
				<input class="button" id="signout" type="submit" name="button" value="Sign out" /><br />
			</form>
		</div>
	</body>
</html>