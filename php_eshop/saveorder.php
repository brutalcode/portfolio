<?php
	require "inc/lib.inc.php";
	require "inc/config.inc.php";
	require "inc/session.inc.php";
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$name = trim(strip_tags($_POST['name']));
		$email = trim(strip_tags($_POST['email']));
		$phone = trim(strip_tags($_POST['phone']));
		$adress = trim(strip_tags($_POST['address']));
	}
	$orderId = $basket['orderid'];
	$orderTime = time();
	$order = $name ."|". $email ."|". $phone ."|". $adress ."|". $orderId ."|". $orderTime ."\n";

	file_put_contents("admin/".ORDERS_LOG, $order, FILE_APPEND);
	saveOrder($orderTime);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Сохранение данных заказа</title>
</head>
<body>
	<p>Ваш заказ принят.</p>
	<p><a href="catalog.php">Вернуться в каталог товаров</a></p>
</body>
</html>