<?php
	session_start();
	$string = "";
	for ($i = 0; $i < rand(4,7); $i++)
		$string .= chr(rand(97, 122));
	
	$_SESSION['rand_code'] = $string;

	$dir = "fonts/";

	$image = imagecreatetruecolor(160, 50);
	$black = imagecolorallocate($image, 0, 0, 0);
	$color = imagecolorallocate($image, rand(50, 255), rand(50, 155), rand(50, 155));
	$white = imagecolorallocate($image, 255, 255, 255);

	imagefilledrectangle($image,0,0,399,99,$white);
	imagettftext ($image, 30, 0, 10, 40, $color, $dir."MTF Chunkie.ttf", $_SESSION['rand_code']);

	header("Content-type: image/png");
	imagepng($image);
?> 