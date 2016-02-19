<?php include('../config/path.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;

if(!isset($_SESSION['username'])){
	session_start();
	//This would delete all the session keys
	session_destroy(); 
	header('Location:'.$path.'login.php');
	exit();
}
?>