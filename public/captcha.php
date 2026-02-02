<?php
session_start();

$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
$captcha_text = '';
for ($i = 0; $i < 6; $i++) {
    $captcha_text .= $characters[rand(0, strlen($characters) - 1)];
}

$_SESSION['captcha_text'] = $captcha_text;

header('Content-Type: text/plain');
echo $captcha_text;
?>
