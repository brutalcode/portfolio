<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";
	require "inc/session.inc.php";

	$myBasket = myBasket();
	
	$i = 1;
	$sum = 0;
	$countItem = 0;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Корзина пользователя</title>
</head>
<body>
	<h1>Ваша корзина</h1>
<?php
	if($count == 0) {
		echo "<p>Корзина пуста! Вернитесь в  <a href='catalog.php'>каталог.</a></p>";
		exit;
	} 
	else echo "<p>Вернуться в <a href='catalog.php'> каталог</a></p>";
?>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<th>N п/п</th>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>Количество</th>
	<th>Удалить</th>
</tr>
<?php
	// echo serialize($myBasket);
	if ($myBasket){
		foreach ($myBasket as $key => $value) {
?>
<tr>
	<td><?= $i ?></td>
	<td><?= $value['title'] ?></td>
	<td><?= $value['author'] ?></td>
	<td><?= $value['pubyear'] ?></td>
	<td><?= $value['price'] ?></td>
	<td><?= $value['quantity'] ?>
		<a href="<?= 'changeBasket.php?change=0'.'&id='.$value['id']?>"> - /</a>
		<a href="<?= 'changeBasket.php?change=1'.'&id='.$value['id']?>"> +</a>
	</td>
	<td><a href='delete_from_basket.php?id=<?= $value['id'] .'&ref='.$_SERVER['REQUEST_URI'] ?>'>Удалить</a></td>
</tr>
<?php
		$sum+=$value['price']*$value['quantity'];
		$i+=1;
		$countItem+= (int) $value['quantity'];

		}//end foreach
	}
?>
</table>

<p>Всего товаров в корзине <?= $countItem ?> на сумму: <?= $sum ?>руб.</p>

<div align="center">
	<input type="button" value="Оформить заказ!"
                      onClick="location.href='orderform.php'" />
</div>

</body>
</html>







