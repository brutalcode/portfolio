<?php
	// подключение библиотек
require "inc/lib.inc.php";
require "inc/config.inc.php";

$delId = trim(strip_tags($_GET['id']));
$ref = trim(strip_tags($_GET['ref']));
if ($delId) {
	deleteItemFromBasket($delId);
}
// $ref = "/eshop/admin".trim(strip_tags($_SERVER['REQUEST_URI']));
header("Location: $ref");

exit;
