<?php
	// Подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

$prodId = (int) $_POST['prodId'];
$ref = trim(strip_tags($_POST['ref']));
$name = trim(strip_tags($_POST['name']));
$email = trim(strip_tags($_POST['email']));
$msg = trim(strip_tags($_POST['msg']));

addComment($name, $email, $msg, $prodId);

header("Location: $ref");
// echo $result;
exit;