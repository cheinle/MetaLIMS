<?php
# Start Session
if(!isset($_SESSION)) { session_start(); }
//This would delete all the session keys
$logout_path = $_SESSION['link_root']; //assign logout path before you destory session
session_destroy(); 
$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
header("Location: http://".$url);
exit();
?>