<?php
// include 'NewsDB.class.php';
if (!$_POST['title'] or !$_POST['category'] or !$_POST['description'] or !$_POST['source'])
	$errMsg = "Заполните все поля формы!";
else {
	$title = $news->clearStr($_POST['title']);
	$category = $news->clearInt($_POST['category']);
	$description = $news->clearStr($_POST['description']);
	$source = $news->clearStr($_POST['source']);
	$result = $news->saveNews($title, $category, $description, $source);
	if($result) {
		header('Location: news.php');
		exit;
	}
	else $errMsg = "Произошла ошибка при добавлении новости";
}
