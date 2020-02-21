<?php
include_once "config/setup.php";
include_once "functions/error.php";

session_start();
$check = $_GET["check"];
$token = $_GET["token"];

if ($_SESSION["loggued_on_user"] == "")
	header("Location: main.php");
if ($check === "OK")
{
	$passwd = $_POST["passwd"];
	if ($passwd)
	{
		$res = $db->prepare("SELECT `passwd` FROM users WHERE `passwd` = :passwd;");
		$res->bindParam(':passwd', hash("whirlpool", $passwd));
		$res->execute();
		if ($res->rowCount() === 0)
			print("Mot de passe invalide.");
		else if ($token === "login")
			header("Location: change_login.php");
		else if ($token === "email")
			header("Location: change_email.php");
		else if ($token === "passwd")
			header("Location: change_passwd.php");
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
			<form method="POST" action="confirm_auth.php?check=OK&token=<?php print($_GET["token"]); ?>">
				<h4 style="margin: 10px 0px;">Pour continuer, veuillez saisir votre mot de passe.</h4>
				<input class="input" type="password" name="passwd" placeholder="Password" required /><br />
				<input class="button" type="submit" name="button" value="Continue" />
			</form>
			<form action="logout.php">
				<input class="button" id="signout" type="submit" name="button" value="Sign out" /><br />
			</form>
		</div>
	</body>
</html>