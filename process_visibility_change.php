<?php
 	include('database_connection.php');
	
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
				echo "Success!";
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

?>

