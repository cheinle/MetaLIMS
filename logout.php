<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

# Start Session
session_start();
//This would delete all the session keys
session_destroy(); 
$url = $_SERVER["HTTP_HOST"].$_SESSION['link_root']."login.php"; 
header("Location: http://".$url);
exit();
?>

