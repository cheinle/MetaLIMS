<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config/path.php');

# Start Session
session_start();
//This would delete all the session keys
session_destroy(); 
header('Location: login.php');
exit();
?>

