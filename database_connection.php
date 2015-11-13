<?php 
	$work = 'false';
	if($work == 'true'){
		include('/xampp_2/config/connect_am_prod.php');
	}
	else{
		include('/xampp/config/connection.txt');
	}
	//$admin_user = 'cheinle@ntu.edu.sg';
	$admin_user = 'N';//no by default
	if($dbc->prepare("SELECT admin FROM users WHERE user_id = ?")){
		$stmt1 -> bind_param('s', $_SESSION['username']);
				
	  	if ($stmt1->execute()){
	    	$stmt1->bind_result($admin_check);
			$stmt1->store_result();
			$stmt1->fetch();
			 
			if($admin_check == 'Y'){
				$admin_user = 'Y';
			}
		}
	}
	
	//check that your admin user is working
	//add error to die...d you need this? die('execute() failed: ' . htmlspecialchars($stmt1->error)
?>
