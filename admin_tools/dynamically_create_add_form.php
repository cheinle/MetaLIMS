<?php
 	include('../database_connection.php');
	//grab all of the columns from the database and create a text input field for it with a label
	$table_name = $_GET['table_value'];
	
		$columns_query = "SHOW COLUMNS FROM ".$table_name;
		$col_res = mysqli_query($dbc,$columns_query);
		while($column = mysqli_fetch_array($col_res)){

			if($column[0] != 'visible'){ //don't display visible flags
				echo '<p>';
				echo '<label class="textbox-label">'.$column[0].':</label>';
				echo '<input type="text" name="'.$column[0].'" placeholder="Name" value="">';
				echo '</p>';
			}
	       
		}
	

?>

