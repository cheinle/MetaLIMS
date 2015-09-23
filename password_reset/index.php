<?php
session_start();
if(!isset($_SESSION['username'])){ 
	header('Location: /series/dynamic/airmicrobiomes/login.php');
	exit();
}
?>