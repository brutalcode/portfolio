<?php
	// подключение библиотек
	require "secure/session.inc.php";
	require "../inc/lib.inc.php";
	require "../inc/config.inc.php";

	/*if ($_SERVER['REQUEST_METHOD'=='POST']) {*/
	$title = trim(strip_tags($_POST['title']));
	$author = trim(strip_tags($_POST['author']));
	$pubyear = trim(strip_tags($_POST['pubyear']));
	$price = trim(strip_tags($_POST['price']));
	$desc = trim(strip_tags($_POST['desc']));

	if ( $_FILES["image"]["error"] != UPLOAD_ERR_OK ){
	switch($_FILES["image"]["error"]){
		case UPLOAD_ERR_INI_SIZE:
		echo "Превышен максимально допустимый размер"; break;
		case UPLOAD_ERR_FORM_SIZE:
		echo "Превышено значение MAX_FILE_SIZE"; break;
		case UPLOAD_ERR_PARTIAL:
		echo "Файл загружен частично"; break;
		case UPLOAD_ERR_NO_FILE:
		echo "Файл не был загружен"; break;
		case UPLOAD_ERR_NO_TMP_DIR:
		echo "Отсутствует временная папка"; break;
		case UPLOAD_ERR_CANT_WRITE:
		echo "Не удалось записать файл не диск";
	}
	}else{
		// echo "Размер загруженного файла: " . $_FILES["image"]["size"];
		// echo "Тип загруженного файла: " . $_FILES["image"]["type"];
		move_uploaded_file($_FILES["image"]["tmp_name"], "../upload/" . $_FILES["image"]["name"]);
		$img = "upload/" . $_FILES["image"]["name"];
	}

	// echo $desc . "   asdfasf  " . $img;

	if(!addItemToCatalog($title, $author, $pubyear, $price, $desc, $img)){
		echo 'Произошла ошибка при добавлении товара в каталог';
	}else{
		header("Location: add2cat.php");
		exit;
	}

	/*
	}*/
