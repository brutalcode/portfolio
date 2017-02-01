<?php
	require "secure/session.inc.php";
	require "../inc/lib.inc.php";
	require "../inc/config.inc.php";

	$orders = getOrders();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Поступившие заказы</title>
	<meta charset="utf-8">
</head>
<body>
<h1>Поступившие заказы:</h1>
<?php
if(!$orders){
	echo "Заказов нет!";
	exit;
}
//start foreach1
foreach ($orders as $order) {
	$orderSum = 0;
	$countItem = 0;
?>
<hr>
<h2>Заказ номер: <?= $order['orderid']?> </h2>
<p><b>Заказчик</b>: <?= $order['name']?></p>
<p><b>Email</b>: <?= $order['email']?></p>
<p><b>Телефон</b>: <?= $order['phone']?></p>
<p><b>Адрес доставки</b>: <?= $order['address']?></p>
<p><b>Дата размещения заказа</b>: <?= date("d-m-Y H:i", $order['date']) ?></p>

<h3>Купленные товары:</h3>
<table border="1" cellpadding="5" cellspacing="0" width="90%">
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
</tr>
	<?php
		$num = 1;
		//start foreach2
		foreach ($order['goods'] as /*$goods*/ $goods) {
			// echo serialize($order['goods'])
		// echo ' : '. serialize($value) . "\n";
		// $num = 1;
	?>

	<tr>
		<td><?= $num++?></td>
		<td><?= $goods['title']?></td>
		<td><?= $goods['author']?></td>
		<td><?= $goods['pubyear']?></td>
		<td><?= $goods['price']?></td>
		<td><?= $goods['quantity']?></td>

	</tr>


	<?php
		$countItem+=$goods['quantity'];
		$orderSum+=$goods['price']*$goods['quantity'];
		}//end foreach2
	?>
</table>
<p>Всего товаров в <?= $countItem?> заказе на сумму: <?= $orderSum?>руб.</p>
<?php
}//end foreach1
?>
</body>
</html>