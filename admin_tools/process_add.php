<?php
	include ('../functions/admin_check.php');
 	include('../database_connection.php');
	include_once('../functions/white_list.php');
	
	$table_name = $_GET['table_name'];
	$inputs= $_GET['inputs'];
	$fields = '';
	$values = array();
	$question_marks = '';
	$params = array();
	foreach($inputs as $key => $value){

		$res = explode("-", $value);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$valid_field_name = whiteList($field_name,'column');
		
		if($valid_field_name == 'true'){
			$fields = $fields.','.$field_name; //white list field names, if does not exist, throw error
			$values[] = $field_value;
			$question_marks = $question_marks.',?';
			$params[] = 's';
		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}	


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
	$question_marks = trim ($question_marks,",");	

	$query1 = "INSERT INTO ".$table_name." (".$fields.") VALUES (";
	$query2 = $question_marks.")";
	$full_query = $query1.$query2;
	
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
			elseif($rows_affected2 == 0){
				echo "Value Already Exists. Please Check Entry";
			}
			else{
				echo "Success!";
			}
		}
	}
				
				
	

?>

