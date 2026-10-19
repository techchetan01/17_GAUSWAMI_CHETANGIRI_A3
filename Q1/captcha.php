<?php
session_start();

$captcha_code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
$_SESSION['captcha'] = $captcha_code;

$img = imagecreatetruecolor(120, 40);
$bg = imagecolorallocate($img, 240, 240, 240);
$fg = imagecolorallocate($img, 20, 20, 20);
$line = imagecolorallocate($img, 150, 150, 150);

imagefilledrectangle($img, 0, 0, 120, 40, $bg);

 for ($i = 0; $i < 3; $i++) {
    imageline($img, 0, rand(0, 40), 120, rand(0, 40), $line);
}

imagestring($img, 5, 35, 12, $captcha_code, $fg);

header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);
?>