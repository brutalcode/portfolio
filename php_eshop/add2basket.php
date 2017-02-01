<?php
	// Подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

$id = (int) $_GET['id'];
$ref = trim(strip_tags($_GET['ref']));
// $ref = trim(strip_tags($_GET['ref']));

/*if (isset($_GET['change'])){
	$change = trim(strip_tags($_GET['change']));
	if ($change == "add") add2Basket($id, true);
	else add2Basket($id, false);
}*/
/*elseif($id){*/
	$result =  add2Basket($id);
/*}*/
// echo $id;
header("Location: $ref");
// echo $result;
exit;