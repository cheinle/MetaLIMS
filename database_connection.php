<?php 

	/**my connection. dont forget to remove**/
	$work = 'true';
	if($work == 'true'){
		include('/xampp_2/config/connect_am_prod.php');
	}
	else{
		include('/xampp/config/connection.txt');
	}
	
	//please change path to point to where you have stored your database connection 
		//Ensure that database connection is outside of document root directory
	//include('/my/connection/path/example_connection.txt');
?>

