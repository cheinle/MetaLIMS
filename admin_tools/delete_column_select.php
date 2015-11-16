<?php
 	include('../database_connection.php');
	
	$table_name = $_GET['table_value'];
	$visible = '';
	$column_name = '';
	if($_GET['visible'] == 'invisible'){
		$visible = 1;
		$column_name = "column";
	}
	if($_GET['visible'] == 'visible'){
		$visible = 0;
		$column_name = "column2";
	}

	
	$pk_query = "SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'";
	$pk_res = mysqli_query($dbc,$pk_query);
	
	if($pk = mysqli_fetch_array($pk_res)){
		$field = $pk[4];			
		$field_query = "SELECT ".$field." FROM ".$table_name." WHERE visible = ".$visible."";
		$field_res = mysqli_query($dbc,$field_query);
		$row_num = mysqli_num_rows($field_res);
		if($row_num < 0 ){
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
		elseif ($row_num == 0) {
			echo "<label class='textbox-label'>Field Value Name:</label>";
			echo "<select id='".$column_name."' name='".$column_name."'>";
			echo "<option value='0'>-Select-</option>";
			echo "</select>";
			
		}
		else{
			$field_res = mysqli_query($dbc,$field_query);
			echo "<label class='textbox-label'>Field Value Name:</label>";
			echo "<select id='".$column_name."' name='".$column_name."'>";
			echo "<option value='0'>-Select-</option>";
		  	while($field_values = mysqli_fetch_array($field_res)){
				echo '<option value="'.$field_values[0].'">'.$field_values[0].'</option>';
			}
			echo "</select>";
			
			if($table_name == 'users' && $column_name == 'column2'){
				echo "<label class='textbox-label'>Admin?:</label>";
				echo "<select id='admin_yn' name='admin_yn'>";
				echo "<option value='0'>-Select-</option>";
				echo "<option value='Y'>Yes</option>";
				echo "<option value='N'>No</option>";
				echo "</select>";
			}
		}
	}
	else{
		header('HTTP/1.1 500 Internal Server Booboo');
       	header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		
	}

?>
