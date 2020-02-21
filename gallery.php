<?php
include_once "config/setup.php";
include_once "functions/sendmail.php";
session_start();

$c = 0;
$ret = $db->query("SELECT * FROM photos ORDER BY `id` ASC");
while ($ret2 = $ret->fetch())
	$c++;
$c = ceil($c / 5);

$photo = $db->prepare("SELECT `name` FROM photos WHERE `id` = :id");
$photo->bindParam(':id', $_GET["nb"]);
$photo->execute();
$photo_name = $photo->fetch();

$login = $db->prepare("SELECT `id` FROM users WHERE `login` = :login");
$login->bindParam(':login', $_SESSION["loggued_on_user"]);
$login->execute();
$login2 = $login->fetch();

if ($_GET["action"] === "like")
{
	$add_like = $db->prepare("SELECT `name` FROM actions WHERE `name` = :photo_name AND `action` = 'like' AND `id_user` = :id");
	$add_like->bindParam(':photo_name', $photo_name["name"]);
	$add_like->bindParam(':id', $login2["id"]);
	$add_like->execute();

	$check_like = $add_like->rowCount();
	if ($check_like === 0)
	{
		$res1 = $db->prepare("INSERT INTO actions (`name`, `action`, `id_user`) VALUES (:photo_name, 'like', :id_user);");
		$res1->bindParam(':photo_name', $photo_name["name"]);
		$res1->bindParam(':id_user', $login2["id"]);
		$res1->execute();
	}
	else
	{
		$res2 = $db->prepare("DELETE FROM actions WHERE `name` = :photo_name AND `action` = 'like' AND `id_user` = :id_user");
		$res2->bindParam(':photo_name', $photo_name["name"]);
		$res2->bindParam(':id_user', $login2["id"]);
		$res2->execute();
	}
}
else if ($_GET["action"] === "comment" && $_POST["comment_area"] && strlen($_POST["comment_area"]) <= 80)
{
	$comment = $db->prepare("INSERT INTO actions (`name`, `action`, `id_user`, `content`) VALUES (:photo_name, 'comment', :id_user, :comment);");
	$comment->bindParam(':photo_name', $photo_name["name"]);
	$comment->bindParam(':id_user', $login2["id"]);
	$comment->bindParam(':comment', htmlspecialchars($_POST["comment_area"]));
	$comment->execute();
	
	$pref = $db->prepare("SELECT * FROM users WHERE `login` = :login");
	$pref->bindParam(':login', $_SESSION["loggued_on_user"]);
	$pref->execute();
	$mpref = $pref->fetch();

	if ($mpref["pref"] === '1')
		sendmail_3($mpref["email"]);
}
else if ($_GET["action"] === "delete")
{
	$del1 = $db->prepare("SELECT `name` FROM photos WHERE `id` = :id");
	$del1->bindParam(':id', $_GET["nb"]);
	$del1->execute();
	$del1 = $del1->fetch();

	$del2 = $db->prepare("DELETE FROM photos WHERE `name` = :name");
	$del2->bindParam(':name', $del1["name"]);
	$del2->execute();

	if (file_exists("photos/".$del1["name"].".png"))
		unlink("photos/".$del1["name"].".png");
}
?>

<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div class="header">
			<h2 style="width: 300px;">Bienvenue dans la galerie !</h2>
			<a href="camagru.php"><button class="button" name="menu" style="margin-top: -50px; margin-left: 520px;">Main menu</button></a>
			<form action="logout.php">
				<?php if ($_SESSION["loggued_on_user"]) echo '<button class="button" name="signout" style="margin-top: -50px; margin-left: 400px;">Sign out</button>'; ?>
			</form>
			<a href="gallery.php?page=<?php $page = $_GET["page"]; if ($page > 1) $page--; echo $page; ?>"><button class="button" name="previous" style="width: 150px;">Previous Page</button></a>
			<a href="gallery.php?page=<?php $page = $_GET["page"]; if ($page < $c) $page++; echo $page; ?>"><button class="button" name="next" style="width: 150px;">Next page</button></a>
		</div>
		<div style="margin-top: -13px;">
			<?php
			$res = $db->query("SELECT * FROM photos ORDER BY `id` ASC");

			for ($i = 0; $res1 = $res->fetch(); $i++)
			{
				if ($i >= ($_GET["page"] - 1) * 5 && $i < $_GET["page"] * 5)
				{
					$nb = $db->prepare("SELECT `id` FROM photos WHERE `name` = :name");
					$nb->bindParam(':name', $res1["name"]);
					$nb->execute();
					$id = $nb->fetch();
					if (file_exists("photos/".$res1["name"].".png"))
					{
						$like = $db->prepare("SELECT `action` FROM actions WHERE `action` = 'like' AND `name` = :name");
						$like->bindParam(':name', $res1["name"]);
						$like->execute();
						$nb_like = $like->rowCount();
						print('<div class="panel">');
						print('<img class="photos_gallery" src="data:image/png;base64,'.base64_encode(file_get_contents("photos/".$res1["name"].".png")).'" /><br />');
						print('<div id="text_like">'.$nb_like.' like(s)</div><br />');
						if ($_SESSION["loggued_on_user"])
						{
							print('<form method="POST" action="gallery.php?page='.$_GET["page"].'&action=like&nb='.$id["id"].'">');
							print('<input type="submit" class="button" id="like" name="like" value="Like" />');
							print('</form>');
							print('<form method="POST" action="gallery.php?page='.$_GET["page"].'&action=comment&nb='.$id["id"].'">');
							print('<textarea id="comment" name="comment_area"></textarea><br />');
							print('<input type="submit" class="button" id="comment_button" name="comment" value="Comment" />');
							print('</form>');
							if ($_SESSION["loggued_on_user"] === $res1["owner"])
								print('<a href="gallery.php?page='.$_GET["page"].'&action=delete&nb='.$id["id"].'"><button class="button" id="delete" name="delete">Delete</button></a><br />');
						}
						$res3 = $db->prepare("SELECT * FROM actions WHERE `name` = :name AND `action` = 'comment'");
						$res3->bindParam(':name', $res1["name"]);
						$res3->execute();
						while ($comments = $res3->fetch())
						{
							$auth = $db->prepare("SELECT `login` FROM users WHERE `id` = :id_user");
							$auth->bindParam(':id_user', $comments["id_user"]);
							$auth->execute();
							$test = $auth->fetch();
							print($test["login"]." : ");
							print($comments["content"].'<br />');
						}
						print('</div>');
					}
				}
			}
			?>
		</div>
	</body>
</html>