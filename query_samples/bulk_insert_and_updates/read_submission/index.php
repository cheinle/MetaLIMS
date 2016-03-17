<?php include('../../../config/path.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;

if(!isset($_SESSION['username'])){
	session_start();
	//This would delete all the session keys
	session_destroy(); 
	$url = $_SERVER["HTTP_HOST"].$root."login.php"; 
	header("Location: http://".$url);
	exit();
}
?>