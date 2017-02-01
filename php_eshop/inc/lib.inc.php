<?php
// include 'config.inc.php';
// session_start();
function addComment($name, $email, $msg, $productId){
	global $link; 

	if ($_SERVER['REQUEST_METHOD']=='POST') {
		/*$name = trim(strip_tags($_POST['name']));
		$email = trim(strip_tags($_POST['email']));
		$msg = trim(strip_tags($_POST['msg']));
		$productId = trim(strip_tags($_POST['prodId']));*/
		// Записываем комментарий в БД
		$sql = "INSERT INTO comment(name,email,msg,prodId) VALUES (?,?,?,?)";
		$stmt = mysqli_prepare($link, $sql);
		// echo serialize($stmt)
		mysqli_stmt_bind_param($stmt,"sssi", $name, $email, $msg, $productId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	}
}
function showComment($productId){
	global $link;

	$sql = "SELECT id, name, email, msg, UNIX_TIMESTAMP(datetime) as dt FROM comment WHERE prodId = $productId ORDER BY id DESC";
	$result = mysqli_query($link,$sql);
	if (!$result) echo "Произошла ошибка: ".mysqli_error($link);
	mysqli_close($link);
	$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $row;

}
function delComment($id){
	global $link;

	$sql = "DELETE FROM comment WHERE id = $id";
	$result = mysqli_query($link, $sql);
	if (!$result) echo "Произошла ошибка: " . mysqli_error($link);
	mysqli_close($link);
}
function changeRating($id, $newRat){
	global $link;
	$sql = "SELECT rating, countRat FROM catalog WHERE id = $id";
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = mysqli_fetch_array($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	// return $items;
	$rating = ($items['rating']*$items['countRat']+$newRat)/($items['countRat']+1);
	$countRat = $items['countRat']+1;
	$sql = "UPDATE catalog SET rating = $rating, countRat = $countRat WHERE id = $id";
	if(!$result = mysqli_query($link, $sql))
		return false;
	// echo $rating, $items['countRat'], $newRat;
	return true;

}
function addItemToCatalog($title, $author, $pubyear, $price, $desc, $img){
	global $link;
	// echo $desc . " asdfasdf " . $img;
	$sql = 'INSERT INTO catalog (title, author, pubyear, price, description, img) VALUES (?, ?, ?, ?, ?, ?)';
	// echo $count . "asdfds";
	if (!$stmt = mysqli_prepare($link, $sql)) return false;
	mysqli_stmt_bind_param($stmt, "ssiiss", $title, $author, $pubyear, $price, $desc, $img);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	return true;
}
function selectAllItems(){
	global $link;
	$sql = 'SELECT id, title, author, pubyear, price FROM catalog';
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	return $items;
}
function selectProduct($id){
	global $link;
	$sql = "SELECT id, title, author, pubyear, price, description, img, rating FROM catalog WHERE id = $id";
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = mysqli_fetch_array($result, MYSQLI_ASSOC);
	mysqli_free_result($result);
	return $items;
}
function saveBasket(){
	global $basket;
	$basket = base64_encode(serialize($basket));
	setcookie('basket', $basket, 0x7FFFFFFF);
}
function basketInit(){
	global $basket, $count;
	if(!isset($_COOKIE['basket'])){
		$basket = ['orderid' => uniqid()];
		saveBasket();
	}else{
		$basket = unserialize(base64_decode($_COOKIE['basket']));
		$count = count($basket) - 1;
	}
}
function add2Basket($id){
	global $basket;
	$basket[$id] = 1;
	// return $basket[$id];
	saveBasket();
}
function changeBasket($id, $change){
	global $basket;
	$basket = unserialize(base64_decode($_COOKIE['basket']));

	if ($change) {
		$basket[$id] += 1;
		// echo "++";
	} elseif ($basket[$id]==1) {
		// echo " удаление";
		deleteItemFromBasket($id);
		return "delete";
	} else {
		$basket[$id] -= 1;
	}
	// $basket = unserialize(base64_decode($_COOKIE['basket']));
	saveBasket();
	// $basket = unserialize(base64_decode($_COOKIE['basket']));
	// return $basket[$id];
}
function result2Array($data){
	global $basket;
	$arr = [];
	while($row = mysqli_fetch_assoc($data)){
		$row['quantity'] = $basket[$row['id']];
		$arr[] = $row;
	}
	return $arr;
}
function myBasket(){
	global $link, $basket;
	$goods = array_keys($basket);
	// echo serialize($basket);
	array_shift($goods);
	
	if(!$goods) return false;
	
	$ids = implode(",", $goods);
	$sql = "SELECT id, author, title, pubyear, price FROM catalog WHERE id IN ($ids)";
	if(!$result = mysqli_query($link, $sql))
	return false;
	$items = result2Array($result);
	mysqli_free_result($result);
	return $items;
}
function deleteItemFromBasket($id){
	global $basket;
	unset ($basket[$id]);
	saveBasket();
}
function saveOrder($datetime){
	global $link, $basket;
	$goods = myBasket();
	$stmt = mysqli_stmt_init($link);
	$sql = 'INSERT INTO orders (
		title,
		author,
		pubyear,
		price,
		quantity,
		orderid,
		datetime)
		VALUES (?, ?, ?, ?, ?, ?, ?)';
	if (!mysqli_stmt_prepare($stmt, $sql))
		return false;
	foreach($goods as $item){
		mysqli_stmt_bind_param($stmt, "ssiiisi",
						$item['title'], $item['author'],
						$item['pubyear'], $item['price'],
						$item['quantity'],
						$basket['orderid'],
						$datetime);
		mysqli_stmt_execute($stmt);
	}

	mysqli_stmt_close($stmt);
	setcookie("basket","",time()-3600);
	return true;
}
function getOrders(){
	global $link;
	if(!is_file(ORDERS_LOG))
	return false;
	/* Получаем в виде массива персональные данные пользователей из файла */
	$orders = file(ORDERS_LOG);
	/* Массив, который будет возвращен функцией */
	$allorders = [];
	foreach ($orders as $order) {
		list($name, $email, $phone, $address, $orderid, $date) = explode("|",
		trim($order));
		/* Промежуточный массив для хранения информации о конкретном заказе */
		$orderinfo = [];
		/* Сохранение информацию о конкретном пользователе */
		$orderinfo["name"] = $name;
		$orderinfo["email"] = $email;
		$orderinfo["phone"] = $phone;
		$orderinfo["address"] = $address;
		$orderinfo["orderid"] = $orderid;
		$orderinfo["date"] = $date;
		/* SQL-запрос на выборку из таблицы orders всех товаров для конкретного
		покупателя */
		$sql = "SELECT title, author, pubyear, price, quantity
		FROM orders
		WHERE orderid = '$orderid' AND datetime = $date";
		/* Получение результата выборки */
		if(!$result = mysqli_query($link, $sql))
		return false;
		// echo "asdf";
		$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
		// echo serialize($items);
		mysqli_free_result($result);
		/* Сохранение результата в промежуточном массиве */
		$orderinfo["goods"] = $items;
		/* Добавление промежуточного массива в возвращаемый массив */
		$allorders[] = $orderinfo;
	}
	return $allorders;
}