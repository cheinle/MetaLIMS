<?php
include('../../config/path.php');
session_start();
if(!isset($_SESSION['username'])){
	$url = $_SERVER["HTTP_HOST"].$root."login.php"; 
	header("Location: http://".$url);
	exit();
}
?>