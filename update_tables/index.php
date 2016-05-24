<?php
if(!isset($_SESSION)) { session_start(); }
include('../path.php');
//This would delete all the session keys
session_destroy(); 
$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
header("Location: http://".$url);
exit();
?>