<?php
define(DB_HOST, 'localhost');
define(DB_LOGIN, 'root');
define(DB_PASSWORD, '');
define(DB_NAME, 'eshop');
define(ORDERS_LOG, 'orders.log');

session_start();
$basket = [];
$count = 0;

// session_start();
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
if (!$link) {
	echo "Ошибка: ".mysqli_errno($link)." - ".mysqli_error($link);
}
// Создание или чтение корзины
basketInit();
// echo serialize($_SESSION['user']);
// if(!isset($_SESSION['user'])){
// 	header('Location: /eshop/login.php');
// 	exit;
// }