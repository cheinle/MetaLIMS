<?php
 	include('../database_connection.php');

	$entry_name = $_GET['entry_value'];
	$table_name = $_GET['table_value'];
	
	$columns_query = "SHOW COLUMNS FROM ".$table_name;
		$col_res = mysqli_query($dbc,$columns_query);
		$pk = '';
		$counter = 0;
		$store_original_value = '';
		while($column = mysqli_fetch_array($col_res)){
			$counter++;
			if($counter == 1){
				$pk = $column[0]; //assume that the primary key is the first input
			}

			if($column[0] != 'visible' && $column[0] != 'password' && $column[0] != 'session_id' && $column[0] != 'time' && $column[0] !='status' && $column[0] != 'pkey' ){ //don't display visible flags
				
				$pieces = explode('_',$column[0]);
				$new_label_name = '';
				foreach($pieces as $name_piece){
					$name_piece = ucfirst($name_piece);
					$new_label_name = $new_label_name.' '.$name_piece;
				}
				echo '<p>';
				echo '<label class="textbox-label">'.$new_label_name.':</label>';
				
				//whitelist column and tablename?
				$query = "SELECT ".$column[0]." FROM ".$table_name." WHERE ".$pk." = ?";
				if($stmt1 = $dbc->prepare($query)){
					$stmt1 -> bind_param('s',$entry_name);
		  			if ($stmt1->execute()){
		    			$stmt1->bind_result($value);
		    			if ($stmt1->fetch()){
							echo '<input type="text" name="'.$column[0].'" value="'.$value.'">';
							if($counter == 1){
								$store_original_value =  $value;
							}
							
						}else{
							echo '<input type="text" name="'.$column[0].'" value="">';
						}
					} 
					else {
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
				
				$stmt1 -> close();

			}
	       	echo '</p>';
	       	
			
		}
		echo '<input type="hidden"  name="original" id = "original" value="'.$store_original_value.'">';
		//style="visibility:hidden"
?>

