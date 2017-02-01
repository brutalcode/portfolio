<?
session_start();

if(!isset($_SESSION['user'])){
	header('Location: /eshop/login.php?ref='.
	$_SERVER['REQUEST_URI']);
	
}// exit;