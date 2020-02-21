<?php

function	push_img($dest, $db)
{
	session_start();
	$name = md5(microtime(TRUE) * 100000);
	imagepng($dest, "photos/".$name.".png");
	$res = $db->prepare("INSERT INTO photos (`owner`, `name`) VALUES (:owner, :name);");
	$res->bindParam(':owner', $_SESSION["loggued_on_user"]);
	$res->bindParam(':name', $name);
	$res->execute();
}

function	merge($photo, $src, $src_x, $src_y, $m_src_x, $m_src_y, $width, $height, $token)
{
	if ($token === 0)
		$dest = imagecreatefromstring(base64_decode(substr($photo, 22)));
	else
		$dest = $photo;
	$dest = imagecrop($dest, ["x" => 0, "y" => 0, "width" => 150, "height" => 150]);
	$src_copy = imagecreatetruecolor($src_x, $src_y);
	imagecopyresized($src_copy, $src, 0, 0, 0, 0, $src_x, $src_y, $width, $height);
	$background = imagecolorallocate($src_copy, 0, 0, 0);
	imagecolortransparent($src_copy, $background);
	imagealphablending($src_copy, false);
	imagesavealpha($src_copy, true);
	imagecopymerge($dest, $src_copy, $m_src_x, $m_src_y, 0, 0, $src_x, $src_y, 100);
	return ($dest);
}

function	create_img($photo, $img, $db, $token)
{
	if ($token === 1)
	{
		$path = $photo;
		if (file_get_contents($photo) == false)
			return ;
		$photo = imagecreatefromstring(file_get_contents($photo));
		$photo_size = getimagesize($path);
		$photo_copy = imagecreatetruecolor($photo_size[0], $photo_size[1]);
		imagecopyresized($photo_copy, $photo, 0, 0, 0, 0, 150, 150, $photo_size[0], $photo_size[1]);
		$photo = $photo_copy;
	}
	if ($img === "tie")
	{
		$src = imagecreatefrompng("img/PNGPIX-COM-Tie-PNG-Transparent-Image-1-500x536.png");
		$src_size = getimagesize("img/PNGPIX-COM-Tie-PNG-Transparent-Image-1-500x536.png");
		$dest = merge($photo, $src, 55.5, 66, 48, 83, $src_size[0], $src_size[1], $token);
	}
	else if ($img === "hat")
	{
		$src = imagecreatefrompng("img/ef1e943bbf78c6e61841680157a8a6b3-western-sheriff-hut-cartoon-by-vexels.png");
		$src_size = getimagesize("img/ef1e943bbf78c6e61841680157a8a6b3-western-sheriff-hut-cartoon-by-vexels.png");
		$dest = merge($photo, $src, 64.5, 72, 48, -10.5, $src_size[0], $src_size[1], $token);
	}
	else if ($img === "hb")
	{
		$src = imagecreatefrompng("img/Download-Happy-Birthday-PNG-HD-263.png");
		$src_size = getimagesize("img/Download-Happy-Birthday-PNG-HD-263.png");
		$dest = merge($photo, $src, 111.7, 51.9, 25, 90, $src_size[0], $src_size[1], $token);
	}
	if (!file_exists("photos/"))
		mkdir("photos/");
	push_img($dest, $db);
}

?>