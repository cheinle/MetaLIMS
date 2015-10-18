<?php
 	include('../database_connection.php');
	include_once('../functions/white_list.php');
	
	$table_name = $_GET['table_name'];
	$inputs= $_GET['inputs'];
	print_r($inputs);
	
	$fields = '';
	$values = '';
	$question_marks = '';
	$params = '';
	foreach($inputs as $key => $value){

		echo $value;
		$res = explode("-", $value);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$valid_field_name = whiteList($field_name,'column');
		
		if($valid_field_name == 'true'){
			$fields = $fields.','.$field_name; //white list field names, if does not exist, throw error
			$values = $values.','.$field_value;
			$question_marks = $question_marks.',?';
			$params = $params.'s';
		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}	
	
	$fields = trim($fields,",");
	$values = trim($values,",");
	$question_marks = trim ($question_marks,",");	
	
	echo "Fields:".$fields.'<br>';
	echo "Val:".$values.'<br>';
	echo "Questions Marks:".$question_marks.'<br>';
	$query1 = "INSERT INTO ".$table_name." (".$fields.") VALUES (";
	$query2 = $question_marks.")";
	$full_query = $query1.$query2;
	echo $full_query;

	//$stmt2 = $dbc -> prepare($full_query);
	//if(!$stmt2){
	//	header('HTTP/1.1 500 Internal Server Booboo');
   //    	header('Content-Type: application/json; charset=UTF-8');
   //     die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
	//}
	/*else{
		 $stmt2 -> bind_param($params, $values);
		 if(!$stmt2 -> execute()){
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		 }
		else{
			$rows_affected2 = $stmt2 ->affected_rows;
			$stmt2 -> close();
			if($rows_affected2 < 1){
				header('HTTP/1.1 500 Internal Server Booboo');
       			header('Content-Type: application/json; charset=UTF-8');
        		die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}
		}
	}*/
				
				
	

?>

