<?php
 	include('../database_connection.php');
	
	$table_name = $_GET['table_value'];
	$field_value = $_GET['field_value'];
	$pk = $_GET['pk'];
	$visibility = $_GET['visible'];
	
	$pk_query = "UPDATE ".$table_name." SET visible = ? WHERE ".$pk." = ?";
	//echo $table_name.' '.$field_value.' '.$pk.' '.$visibility.'<br>';
	if($stmt = $dbc ->prepare($pk_query)){                 
		$stmt->bind_param('is',$visibility,$field_value);
	    if($stmt -> execute()){
			$rows_affected = $stmt ->affected_rows;;
			$stmt -> close();
			if($rows_affected < 0){
				header('HTTP/1.1 500 Internal Server Booboo');
	       		header('Content-Type: application/json; charset=UTF-8');
	        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}else{
				
				
				//if you are changing the visibility of a drawer, change the visibility of freezer_drawer
				if($table_name == 'drawer'){
					 $update_freezer_drawer = change_freezer_drawer_visibility($dbc,$field_value,$visibility);
					 if($update_freezer_drawer == 'false'){
					 	header('HTTP/1.1 500 Internal Server Booboo');
       					header('Content-Type: application/json; charset=UTF-8');
        				die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
					 }else{
					 	echo "Success!";
					 }
				}else{
					echo "Success!";
				}
			}
		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}
	else{
		header('HTTP/1.1 500 Internal Server Booboo');
       	header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
	}



	function change_freezer_drawer_visibility($dbc,$drawer_name,$visibility){
		$updated_check = 'true'; //visible_flag
		$stmt= $dbc->prepare("SELECT freezer_id FROM freezer_drawer WHERE drawer_id = ?");
		$stmt-> bind_param('s', $drawer_name);
					
		$freezer_drawers = array();
		if ($stmt->execute()){
			$stmt->bind_result($freezer_id);
			while ($stmt->fetch()){
    			$freezer_drawers[] = $freezer_id;
			}
		} 
		else {
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		$stmt -> close();
		
		foreach($freezer_drawers as $freezer_name){
			$freezer_drawer_query = "UPDATE freezer_drawer SET visible_flag = ? WHERE freezer_id = ? AND drawer_id = ?";
			if($stmt_fd = $dbc ->prepare($freezer_drawer_query)){                 
				$stmt_fd->bind_param('iss',$visibility,$freezer_name,$drawer_name);
			    if($stmt_fd-> execute()){
					$rows_affected_fd = $stmt_fd ->affected_rows;
					$stmt_fd -> close();
					if($rows_affected_fd < 0){
						$updated_check = 'false';
					}
				}
				else{
					$updated_check = 'false';
				}
			}
			else{
				$updated_check = 'false';
			}	
		}
	
  		return $updated_check;
	}

?>

