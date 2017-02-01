<?php
$id = abs((int) $_GET['delId']);
if ($id) $result = $news->deleteNews($id);
else header("Location: news.php");

if (!$result) {
	$errMsg = "Произошла ошибка при удалении новости";
} else header("Location: news.php");

