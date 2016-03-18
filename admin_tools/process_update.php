<?php
 	include('../database_connection.php');
	include_once('../functions/white_list.php');
	
	$table_name = $_GET['table_name'];
	$inputs= $_GET['inputs'];
	$pk = $_GET['pk'];
	$original_value = $_GET['original_value'];
	$orig_value = explode("%", $original_value);
	$original_value = $orig_value[0];

	$fields = '';
	$values = array();
	$pk_value = '';
	$params = array();
	foreach($inputs as $key => $value){

		$res = explode("%", $value);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$valid_field_name = whiteList($field_name,'column');
		$valid_table_name = whiteList($table_name,'table');
		
		if($valid_field_name == 'true' && $valid_table_name == 'true'){
			$fields = $fields.$field_name.'= ?,'; //white list field names, if does not exist, throw error
			$values[] = $field_value;
			$params[] = 's';

		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}	
	$params[] = 's'; //one more for pk

	$values[] = $original_value; //original value for pk 
	
	/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
	/*http://www.pontikis.net/blog/dynamically-bind_param-array-mysqli*/
	$a_param_type = array();
	$a_bind_params = array();
	$a_param_type = $params;
	$a_bind_params = $values;
	$a_params = array();
	 
	$param_type = '';
	$n = count($a_param_type);
	for($i = 0; $i < $n; $i++) {
	  $param_type .= $a_param_type[$i];
	}
	 
	/* with call_user_func_array, array params must be passed by reference */
	$a_params[] = & $param_type;
	 
	for($i = 0; $i < $n; $i++) {
	  /* with call_user_func_array, array params must be passed by reference */
	  $a_params[] = & $a_bind_params[$i];
	}

	$fields = trim($fields,",");

	$query1 = 'UPDATE '.$table_name.' SET '; 
	$query2 = $fields;
	$query3 = 'WHERE '.$pk.' = ?';

	$full_query = $query1.$query2.' '.$query3;

	$stmt2 = $dbc -> prepare($full_query);
	if(!$stmt2){
		header('HTTP/1.1 500 Internal Server Booboo');
       	header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
	}
	else{
		call_user_func_array(array($stmt2, 'bind_param'), $a_params);
		if(!$stmt2 -> execute()){
			
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
		else{
			$rows_affected2 = $stmt2 ->affected_rows;
			$stmt2 -> close();
			if($rows_affected2 < 0){
				header('HTTP/1.1 500 Internal Server Booboo');
       			header('Content-Type: application/json; charset=UTF-8');
        		die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}
			else{
				echo "Success!";
			}
		}
		
	}
				
	if($table_name == 'drawer'){
		$res = explode("%", $inputs[0]);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$new_drawer_name = $field_value;
		$old_drawer_name = $original_value;
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
			$freezer_drawer_query = "UPDATE freezer_drawer SET drawer_id = ? WHERE freezer_id = ? AND drawer_id = ?";
			if($stmt_fd = $dbc ->prepare($freezer_drawer_query)){                 
				$stmt_fd->bind_param('sss',$new_drawer_name,$freezer_name,$old_drawer_name);
			    if($stmt_fd-> execute()){
					$rows_affected_fd = $stmt_fd ->affected_rows;
					$stmt_fd -> close();
					if($rows_affected_fd < 0){
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
			}
			else{
				header('HTTP/1.1 500 Internal Server Booboo');
       			header('Content-Type: application/json; charset=UTF-8');
        		die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}	
		}
	
	}	
		
	if($table_name == 'freezer'){
		$res = explode("%", $inputs[0]);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$new_freezer_name = $field_value;
		$old_freezer_name = $original_value;
		$stmt= $dbc->prepare("SELECT drawer_id FROM freezer_drawer WHERE freezer_id = ?");
		$stmt-> bind_param('s', $freezer_name);
					
		$drawer_freezers = array();
		if ($stmt->execute()){
			$stmt->bind_result($drawer_id);
			while ($stmt->fetch()){
    			$drawer_freezers[] = $drawer_id;
			}
		} 
		else {
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
		$stmt -> close();
		
		foreach($drawer_freezers as $drawer_name){
			$drawer_freezer_query = "UPDATE freezer_drawer SET freezer_id = ? WHERE drawer_id = ? AND freezer_id = ?";
			if($stmt_fd = $dbc ->prepare($drawer_freezer_query)){                 
				$stmt_fd->bind_param('sss',$new_freezer_name,$drawer_name,$old_freezer_name);
			    if($stmt_fd-> execute()){
					$rows_affected_fd = $stmt_fd ->affected_rows;
					$stmt_fd -> close();
					if($rows_affected_fd < 0){
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
			}
			else{
				header('HTTP/1.1 500 Internal Server Booboo');
       			header('Content-Type: application/json; charset=UTF-8');
        		die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}	
		}
	
	}				
	

?>

