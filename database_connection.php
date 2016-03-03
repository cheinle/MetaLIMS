<?php 
	$work = 'true';
	if($work == 'true'){
		include('/xampp_2/config/connect_am_prod.php');
	}
	else{
		include('/xampp/config/connection.txt');
	}
	$admin_email = '';
	$admin_Y = 'Y';
	if($stmt = $dbc->prepare("SELECT user_id FROM users WHERE admin = ?")){
		$stmt -> bind_param('s', $admin_Y );
		if ($stmt->execute()){
			$stmt->bind_result($admin_username);
			if ($stmt->fetch()){
				$admin_email = $admin_username;
			}	
		}	
	}
?>
