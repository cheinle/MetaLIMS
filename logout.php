<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('/config/path.php');

# Start Session
session_start();

//unset($_SESSION['username']); //Delete the username
//unset($_SESSION['session_id']); //Delete the session_id
echo session_id();
session_destroy(); //This would delete all the session keys
echo "next ".session_id();
#session_start();
#echo "again".session_id();
header('Location: '.$root.'login.php');
exit();

?>
