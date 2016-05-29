<?php if(!isset($_SESSION)) { session_start(); }
//This would delete all the session keys
session_destroy(); 
include('../path.php');
$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
header("Location: http://".$url);
exit();
?>