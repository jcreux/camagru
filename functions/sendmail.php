<?php

function	sendmail_1($email, $login, $key)
{
	$message = "
Bienvenue sur Camagru,

Pour activer votre compte, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.

http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/activate.php?login=".urlencode($login)."&key=".urlencode($key)."

---------------------------------------------------------
Ceci est un mail automatique, merci de ne pas y répondre.
";
	mail($email, "Camagru", $message, "Camagru");
}

function	sendmail_2($email, $passwd)
{
	$message = "
Bienvenue sur Camagru,

Pour activer votre nouveau mot de passe, veuillez cliquer sur le lien ci dessous
ou copier/coller dans votre navigateur internet.

http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/forget_passwd.php?check=OK_2&email=".$email."&passwd=".urlencode($passwd)."

---------------------------------------------------------
Ceci est un mail automatique, merci de ne pas répondre.
";
	mail($email, "Camagru", $message, "Camagru");
}

function	sendmail_3($email)
{
	$message = "
Nouveau commentaire sur une de vos photos !

---------------------------------------------------------
Ceci est un mail automatique, merci de ne pas répondre.
";
	mail($email, "Camagru", $message, "Camagru");
}

?>