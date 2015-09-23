<?php
include('..config/path.php');
session_start();
if(!isset($_SESSION['username'])){ 
	header('Location: '.$path.'login.php');
	exit();
}
?>