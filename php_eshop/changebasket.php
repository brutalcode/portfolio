<?php
	// Подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

$change = (int) $_GET['change'];
$id = (int) $_GET['id'];
// $ref = trim(strip_tags($_GET['ref']));

if ($change == 1){
	$result = changeBasket($id, true);
}else{
	$result = changeBasket($id, false);
}
// echo "все ок". $result;
header("Location: basket.php");
exit;