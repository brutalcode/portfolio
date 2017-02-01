<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";
	require "inc/session.inc.php";

	$goods = selectAllItems();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Каталог товаров</title>
</head>
<body>
<p>Товаров в <a href="basket.php">корзине</a>: <?= $count?></p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th>В корзину</th>
</tr>
<?php
foreach($goods as $item){
?>
<tr>
	<td><a href="product.php?id=<?= $item['id'] ?>"><?= $item['title']?></a></td>
	<td><?= $item['author']?></td>
	<td><?= $item['pubyear']?></td>
	<td><?= $item['price']?></td> 
	<td>
		<?php
			if ($basket[$item['id']]){
		?>
				<a href="<?= 'delete_from_basket.php?id='.$item['id'].'&ref='.$_SERVER['REQUEST_URI']?>">Удалить из корзины</a>
		<?
			}else{
		?>
				<a href="<?= 'add2basket.php?id='.$item['id'].'&ref='.$_SERVER['REQUEST_URI']?>">В корзину</a>
		<?
			}
		?>
	</td>
</tr>
<?
}
echo serialize($_SESSION['user'])
?>
</table>
</body>
</html>