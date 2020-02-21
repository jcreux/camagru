<?php

function	login_exist($db, $login)
{
	$res = $db->prepare("SELECT `login` FROM users WHERE `login` = :login;");
	$res->bindParam(':login', $login);
	$res->execute();
	if ($res->rowCount() !== 0)
		return (1);
	return (0);
}

function	email_exist($db, $email)
{
	$res = $db->prepare("SELECT `email` FROM users WHERE `email` = :email;");
	$res->bindParam(':email', $email);
	$res->execute();
	if ($res->rowCount() !== 0)
		return (1);
	return (0);
}

function	error($token)
{
	if ($token === 0)
		print("Veuillez remplir tous les champs du formulaire.");
	else if ($token === 1)
		print("Le login doit contenir moins de 25 caractères.");
	else if ($token === 2)
		print("Format d'email non valide.");
	else if ($token === 3)
		print("Login ou email déjà utilisés.");
	else if ($token === 4)
		print("Les mots de passe ne sont pas identiques.");
	else if ($token === 5)
		print("Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre et sa longueur doit être comprise entre 8 et 25.");
}

?>