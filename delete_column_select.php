<?php
 	include('database_connection.php');
	
	$table_name = $_GET['table_value'];
	
	$pk_query = "SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'";
	$pk_res = mysqli_query($dbc,$pk_query);
	
	if($pk = mysqli_fetch_array($pk_res)){
		$field = $pk[4];			
		$field_query = "SELECT ".$field." FROM ".$table_name." Where visible = 1";
		$field_res = mysqli_query($dbc,$field_query);
		if(!$field_values = mysqli_fetch_array($field_res)){
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
		else{
			$field_res = mysqli_query($dbc,$field_query);
			echo "<label class='textbox-label'>Field Value Name:</label>";
			echo "<select id='column' name='column'>";
			echo "<option value='0'>-Select-</option>";
		  	while($field_values = mysqli_fetch_array($field_res)){
				echo '<option value="'.$field_values[0].'">'.$field_values[0].'</option>';
			}
			echo "</select>";
		}
	}
	else{
		header('HTTP/1.1 500 Internal Server Booboo');
       	header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		
	}
	
	

	?>

	</body>
	
	
</html>
