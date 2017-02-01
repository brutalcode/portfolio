<?php
	// Подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

$id = (int) $_GET['id'];
$ref = trim(strip_tags($_GET['ref']));

delComment($id);

header("Location: $ref");
// echo $result;
exit;