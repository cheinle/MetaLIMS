<?php
 	include('../database_connection.php');

	$entry_name = $_GET['entry_value'];
	$table_name = $_GET['table_value'];
	
	$columns_query = "SHOW COLUMNS FROM ".$table_name;
		$col_res = mysqli_query($dbc,$columns_query);
		while($column = mysqli_fetch_array($col_res)){
			

			if($column[0] != 'visible'){ //don't display visible flags
				echo '<p>';
				echo '<label class="textbox-label">'.$column[0].':</label>';
				
				//whitelist column and tablename?
				$query = "SELECT ".$column[0]." FROM ".$table_name." WHERE ".$column[0]." = ?";
				if($stmt1 = $dbc->prepare($query)){
					$stmt1 -> bind_param('s',$entry_name);
		  			if ($stmt1->execute()){
		    			$stmt1->bind_result($value);
		    			if ($stmt1->fetch()){
							echo '<input type="text" name="'.$column[0].'" value="'.$value.'">';
							
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
	

?>

