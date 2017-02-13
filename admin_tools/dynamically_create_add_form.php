<?php
	if(!isset($_SESSION)) { session_start(); }
	$path = $_SESSION['include_path']; //same as $path
	include ($path.'/functions/admin_check.php');
 	include('../database_connection.php');
	//grab all of the columns from the database and create a text input field for it with a label
	$table_name = $_GET['table_value'];
	
		$columns_query = "SHOW COLUMNS FROM ".$table_name;
		$col_res = mysqli_query($dbc,$columns_query);
		while($column = mysqli_fetch_array($col_res)){

			if($column[0] != 'visible' && $column[0] != 'password' && $column[0] != 'session_id' && $column[0] != 'time' && $column[0] !='status' && $column[0] != 'pkey' ){ //don't display hidden flags
				$pieces = explode('_',$column[0]);
				$new_label_name = '';
				foreach($pieces as $name_piece){
					$name_piece = ucfirst($name_piece);
					$new_label_name = $new_label_name.' '.$name_piece;
				}
				echo '<p>';
				echo '<label class="textbox-label">'.$new_label_name.':</label>';
				echo '<input type="text" name="'.$column[0].'" placeholder="Name" value="">';
				echo '</p>';
			}
	       
		}
	

?>

