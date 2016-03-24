<?php
include(INCLUDE_ROOT.'/config/path.php');
session_start();
if(!isset($_SESSION['username'])){
	$url = $_SERVER["HTTP_HOST"].LINK_ROOT."login.php"; 
	header("Location: http://".$url);
	exit();
}
?>