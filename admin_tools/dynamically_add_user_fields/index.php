<?php
include('../../index.php');
session_start();
if(!isset($_SESSION['username'])){
	$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
	header("Location: http://".$url);
	exit();
}
?>