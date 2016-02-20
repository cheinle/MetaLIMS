<?php include('../config/path.php');
session_start();
if(!isset($_SESSION['username'])){ 
	header('Location:'.$root.'login.php');
	exit();
}
?>