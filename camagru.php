<?php
include_once "config/setup.php";
include_once "functions/create_img.php";

session_start();
$msg = $_GET["msg"];
$img = $_GET["img"];
$photo = $_POST["tar"];

if ($_SESSION["loggued_on_user"] == "")
	header("Location: main.php");
else
{
	if ($msg === '0')
		print("Les notifications par mail sont désactivées !");
	else if ($msg === '1')
		print("Les notifications par mail sont activées !");
	if ($img === "tie")
		echo '<img src="data:image/png;base64,'.base64_encode(file_get_contents("img/PNGPIX-COM-Tie-PNG-Transparent-Image-1-500x536.png")).'" id="wc_tie" />';
	else if ($img === "hat")
		echo '<img src="data:image/png;base64,'.base64_encode(file_get_contents("img/ef1e943bbf78c6e61841680157a8a6b3-western-sheriff-hut-cartoon-by-vexels.png")).'" id="wc_hat" />';
	else if ($img === "hb")
		echo '<img src="data:image/png;base64,'.base64_encode(file_get_contents("img/Download-Happy-Birthday-PNG-HD-263.png")).'" id="wc_hb" />';
	if ($photo && $img)
		create_img($photo, $img, $db, 0);
}
?>

<html>
	<head>
		<title>Camagru</title>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript">
			function init()
			{
				navigator.mediaDevices.getUserMedia({ audio: false, video: { width: 800, height: 800 } }).then(function(mediaStream)
				{
					var video = document.getElementById("srcvid");
					video.srcObject = mediaStream;
					video.onloadedmetadata = function(e)
					{
						video.play();
					};
				}).catch(function(err){ console.log(err.name + ": " + err.message);});
			}
			function clone()
			{
				var vivi = document.getElementById("srcvid");
				var canvas1 = document.getElementById("cvs").getContext("2d");
				canvas1.drawImage(vivi, 0, 0, 150, 150);
				var base64=document.getElementById("cvs").toDataURL("image/png");
				document.getElementById("tar").value=base64;
			}
			window.onload = init;
		</script>
	</head>
	<body>
		<div class="header">
			<h2 style="margin: 10px;">Bonjour <?php print(htmlspecialchars($_SESSION["loggued_on_user"])); ?> !</h2>
			<a href="gallery.php?page=1"><input class="button" id="gallery" type="submit" name="gallery" value="Gallery" /></a>
			<form action="logout.php">
				<input class="button" id="signout" type="submit" name="button" value="Sign out" /><br />
				<a class="link" href="confirm_auth.php?token=login">Modifier son nom d'utilisateur</a><br />
				<a class="link" href="confirm_auth.php?token=email">Modifier son adresse mail</a><br />
				<a class="link" href="confirm_auth.php?token=passwd">Modifier son mot de passe</a><br />
				<a class="link" href="pref.php">Modifier ses préférences</a>
			</form>
		</div>
		<div>
			<form method="POST" action="camagru.php<?php if ($_GET["img"] === "tie") echo '?img=tie'; else if ($_GET["img"] === "hat") echo '?img=hat'; else if ($_GET["img"] === "hb") echo '?img=hb'; ?>">
				<canvas id="cvs" style="position: absolute; margin-top: -10000px;"></canvas>
				<textarea name="tar" id="tar" style="position: absolute; margin-top:-10000px;"></textarea>
				<video id="srcvid" autoplay="true" style="border: 3px solid darkred;"></video><br />
				<?php if ($_GET["img"] === "tie" || $_GET["img"] === "hat" || $_GET["img"] === "hb") echo '<button class="button" id="photo" name="photo" onclick="clone()">Photo</button>'; ?>
			</form>
			<?php
			if ($_GET["img"] === "tie" || $_GET["img"] === "hat" || $_GET["img"] === "hb")
			{
				print('<form method="POST" enctype="multipart/form-data">');
				print('<input type="file" name="file" accept="image/png, image/jpeg" style="margin-left: 200px;" /><br />');
				print('<input type="submit" value="ok" style="width: 77px; margin-left: 200px;" />');
				print('</form>');
			}
			if ($_FILES['file']['type'] === "image/png" || $_FILES['file']['type'] === "image/jpeg")
			{
				$uploadfile = "img/".basename($_FILES['file']['name']);
				move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
				create_img($uploadfile, $img, $db, 1);
			}
			?>
		</div>
		<div class="section_img">
			<a <?php if ($_GET["img"] === "tie") echo 'href="camagru.php"'; else echo 'href="camagru.php?img=tie"'; ?>><img class="img" src="img/PNGPIX-COM-Tie-PNG-Transparent-Image-1-500x536.png" /></a>
			<a <?php if ($_GET["img"] === "hat") echo 'href="camagru.php"'; else echo 'href="camagru.php?img=hat"'; ?>href="camagru.php?img=hat"><img class="img" src="img/ef1e943bbf78c6e61841680157a8a6b3-western-sheriff-hut-cartoon-by-vexels.png" /></a>
			<a <?php if ($_GET["img"] === "hb") echo 'href="camagru.php"'; else echo 'href="camagru.php?img=hb"'; ?>href="camagru.php?img=hb"><img class="img" id="hb" src="img/Download-Happy-Birthday-PNG-HD-263.png" /></a>
		</div>
	</body>
</html>