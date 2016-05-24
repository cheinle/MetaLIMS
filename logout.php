
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
# Start Session
if(!isset($_SESSION)) { session_start(); }
$logout_path = $_SESSION['link_root']; //assign logout path before you destory session
//This would delete all the session keys
session_destroy(); 
$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
header("Location: http://".$url);
exit();
?>

