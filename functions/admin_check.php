<?php
   if(!isset($_SESSION)) { session_start(); }
   if(isset($_SESSION['admin_user'])){
   		if($_SESSION['admin_user'] != 'Y'){
   			$url = $_SERVER["HTTP_HOST"].$_SESSION['link_root']."404.php"; 
			header("Location:http://".$url);
   		}
   }
?>