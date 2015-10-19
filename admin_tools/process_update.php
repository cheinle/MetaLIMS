<?php
 	include('../database_connection.php');
	include_once('../functions/white_list.php');
	
	$table_name = $_GET['table_name'];
	$inputs= $_GET['inputs'];
	$pk = $_GET['pk'];
	
	$fields = '';
	$values = array();
	$question_marks = '';
	$params = array();
	foreach($inputs as $key => $value){

		$res = explode("-", $value);
		$field_name = $res[0];
		$field_value = $res[1];
		echo "test".$field_name;
		
		$valid_field_name = whiteList($field_name,'column');
		
		/*if($valid_field_name == 'true'){
			$fields = $fields.'= ?,'.$field_name; //white list field names, if does not exist, throw error
			$values[] = $field_value;
			$question_marks = $question_marks.',?';
			$params[] = 's';
		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}*/
	}	

	
				
	

?>

