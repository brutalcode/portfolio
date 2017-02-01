<?php
	// подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";
	require "inc/session.inc.php";

	$id = (int) $_GET['id'];
	$item = selectProduct($id);
	$requestUri = $_SERVER['REQUEST_URI'];
	$commentArr = showComment($id);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Карточка товара</title>
</head>
<body>
<p>Вернуться в <a href="catalog.php">каталог</a></p>
<p>Товаров в <a href="basket.php">корзине</a>: <?= $count?></p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
	<th>Название</th>
	<th>Автор</th>
	<th>Год издания</th>
	<th>Цена, руб.</th>
	<th width="40%">Описание</th>
</tr>
<tr>
	<td><?= $item['title']?></td>
	<td><?= $item['author']?></td>
	<td><?= $item['pubyear']?></td>
	<td><?= $item['price']?></td>
	<td><?= $item['description']?></td>
</tr>
<tr>
<tr>
	<td colspan="6">
		<?php 
			if( $_SESSION['ratingOn'][$id] ) {
				echo $item['rating'];
				// echo serialize($_SESSION['ratingOn']);
			}
			else{
		?>
		<form action="rating.php ">
			<select name="rating">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			<input type="hidden" name="id" value="<?= $id ?>" />
			<input type="hidden" name="ref" value="<?= $_SERVER['REQUEST_URI'] ?>" />
			<input type="submit" value="Оценить"/>
		</form>
		<?php
			}
		?>
	</td>
</tr>
</tr>
<tr>
	<td colspan="6">
		<img src=" <?= trim($item['img']) ?> " width="80%" />
	</td>
</tr>
<tr>
	<td colspan="6">
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
</table>
<form method="post" action="add_comment.php">
	Имя: <br /><input type="text" name="name" /><br />
	Email: <br /><input type="text" name="email" /><br />
	Сообщение: <br /><textarea name="msg"></textarea><br />
	<input type="hidden" name="prodId" value="<?= $item['id'] ?>">
	<input type="hidden" name="ref" value="<?= $_SERVER['REQUEST_URI']?>"><br />
	<input type="submit" value="Отправить!" />
</form>
<?php
	foreach ($commentArr as $value) {
		echo '<p>
		<a href="mailto:'.$value['email'].'">'.$value['name'].'</a>
		' . date('d-m-Y', $value['dt']). ' в '.date('H:i',$value['dt']).'
		написал(а)<br />'.$value['msg'].'
		</p>
		<p align="right">
		<a href="del_comment.php?id='.$value['id'].'&ref='.$_SERVER['REQUEST_URI'].'">
		Удалить</a>
		</p>';
	}
?>
</body>
</html>