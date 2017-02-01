<?php
	// Подключение библиотек
	require "inc/lib.inc.php";
	require "inc/config.inc.php";

$id = (int) $_GET['id'];
$ref = trim(strip_tags($_GET['ref']));
$rat = (int) $_GET['rating'];

// $rating = giveRating($id);
// $rating['rating']
// $rating['countRat']

$result = changeRating($id,$rat);

// $result = $_SESSION['ratingOn'][$id => $result];
// $_SESSION['ratingOn'.$id] = $result;
$_SESSION['ratingOn'][$id] = true;

// echo serialize($_SESSION['ratingOn']);
// echo serialize($result);
// echo $ref;
header("Location: $ref");
// echo $result;
exit;