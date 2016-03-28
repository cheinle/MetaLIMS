<?php
include('../path.php');
# Start Session
if(!isset($_SESSION)) { session_start(); }
//This would delete all the session keys
session_destroy(); 
$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
header("Location: http://".$url);
exit();
?>