<?php
 	include('../database_connection.php');
	//grab all of the columns from the database and create a text input field for it with a label
	$table_name = $_GET['table_value'];
	$pk_query = "SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'";
	$pk_res = mysqli_query($dbc,$pk_query);
  	while($pk = mysqli_fetch_array($pk_res)){
		
		$query = "SELECT ".$pk[4]." FROM ".$table_name;
		$res = mysqli_query($dbc,$query);

		echo "<option value='0'>-Select-</option>";

  		while($entries = mysqli_fetch_array($res)){
			echo '<option value="'.$entries[0].'">'.$entries[0].'</option>';
		}
	}
	

?>

