<?php
	if(!isset($_SESSION)) { session_start(); }
	$path = $_SESSION['include_path']; //same as $path
	include ($path.'/functions/admin_check.php');
 	include('../database_connection.php');

	$type = $_GET['type'];
	$values = $_GET['values'];
	$error = 'false';

			
	if($type == 'drawer'){
		$error = 'false';
		$p_drawer = htmlspecialchars($values[0]);
		$stmt = $dbc->prepare("SELECT drawer_id FROM drawer WHERE drawer_id = ?");
		$stmt -> bind_param('s', $p_drawer);

		if ($stmt->execute()){
			$stmt->bind_result($drawer_name);
			if ($stmt->fetch()){
    			if($drawer_name == $p_drawer){
    				echo $p_drawer." Exists. Please Check Name.";
					$error = 'true';
				}
			}
		} 
		else {
			$error = 'true';
			die('execute() failed: ' . htmlspecialchars($stmt->error));
			
		}
		$stmt -> close();

		//insert info into db
	    if($error == 'false'){
	    
			//insert data into db. Use prepared statement 
			$query_add = "INSERT INTO drawer (drawer_id) VALUES (?)";
			if($stmt_add = $dbc -> prepare($query_add)){
				$stmt_add->bind_param("s",$p_drawer);
					
				if($stmt_add->execute()){
					$rows_affected_add  = $stmt_add  ->affected_rows;
					$stmt_add  -> close();
					//check if add was successful or not. Tell the user
				   	if($rows_affected_add  > 0){
						echo 'You Added A New Drawer: '.$p_drawer;
					}else{
						echo 'An Error Has Occurred';
						mysqli_error($dbc);
					}
				}else{
					echo 'Execute Error: An Error Has Occurred';
					mysqli_error($dbc);
				}
			}else{
				echo 'Prepare Error: An Error Has Occurred';
				mysqli_error($dbc);
			}
		}
	}


	
	if($type == 'freezer'){
		$error = 'false';
		$p_freezer = htmlspecialchars($values[0]);
		$stmt = $dbc->prepare("SELECT freezer_id FROM freezer WHERE freezer_id = ?");
		$stmt -> bind_param('s', $p_freezer);

		if ($stmt->execute()){
			$stmt->bind_result($freezer_name);
			if ($stmt->fetch()){
    			if($freezer_name == $p_freezer){
    				echo $p_freezer." Exists. Please Check Name.";
					$error = 'true';
				}
			}
		} 
		else {
			$error = 'true';
			die('execute() failed: ' . htmlspecialchars($stmt->error));
			
		}
		$stmt -> close();

		//insert info into db
	    if($error == 'false'){
	    
			//insert data into db. Use prepared statement 
			$query_add = "INSERT INTO freezer (freezer_id) VALUES (?)";
			if($stmt_add = $dbc -> prepare($query_add)){
				$stmt_add  -> bind_param('s',$p_freezer);
					
				if($stmt_add  -> execute()){
					$rows_affected_add  = $stmt_add  ->affected_rows;
					$stmt_add  -> close();
						
					//check if add was successful or not. Tell the user
				   	if($rows_affected_add  > 0){
						echo 'You Added A New freezer: '.$p_freezer;
					}else{
						echo 'An Error Has Occurred';
						mysqli_error($dbc);
					}
				}else{
					echo 'Execute Error: An Error Has Occurred';
					mysqli_error($dbc);
				}
			}else{
				echo 'Prepare Error: An Error Has Occurred';
				mysqli_error($dbc);
			}
		}
		
	}
	
	
	if($type == 'freezer_drawer'){
		$error = 'false';
		$p_drawer = htmlspecialchars($values[1]);
		$p_freezer = htmlspecialchars($values[0]);

		$stmt = $dbc->prepare("SELECT freezer_id,drawer_id FROM freezer_drawer WHERE drawer_id = ? AND freezer_id =?");
		$stmt -> bind_param('ss', $p_drawer,$p_freezer);
	
		if ($stmt->execute()){
			$stmt->bind_result($drawer_name,$freezer_name);
			if ($stmt->fetch()){
				if($drawer_name == $p_drawer && $freezer_name == $p_freezer){
					echo $p_drawer." Exists For Freezer:".$p_freezer."Please Check Name.";
					$error = 'true';
				}
			}
		} 
		else {
			$error = 'true';
			die('execute() failed: ' . htmlspecialchars($stmt->error));
			
		}
		$stmt -> close();
	
		//insert info into db
	    if($error == 'false'){

			//insert data into db. Use prepared statement 
			$query_add = "INSERT INTO freezer_drawer (drawer_id,freezer_id) VALUES (?,?)";
			if($stmt_add = $dbc -> prepare($query_add)){
				$stmt_add  -> bind_param('ss',$p_drawer,$p_freezer);
				
				if($stmt_add  -> execute()){
					$rows_affected_add  = $stmt_add  ->affected_rows;
					$stmt_add  -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected_add  > 0){
						echo 'You Added A New Drawer: '.$p_drawer.' To Freezer '.$p_freezer;
					}else{
						echo 'An Error Has Occurred'.$p_drawer.$p_freezer;
						mysqli_error($dbc);
					}
				}else{
					echo 'Execute Error: An Error Has Occurred'.$p_drawer.$p_freezer;
					mysqli_error($dbc);
				}
			}else{
				echo 'Prepare Error: An Error Has Occurred';
				mysqli_error($dbc);
			}
		}
							
	}


?>

