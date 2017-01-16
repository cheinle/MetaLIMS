<?php	

//display table
function basic_build_table($stmt,$table_type,$root){ //table types are 'dislapy' and 'xls'

	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'functions/convert_time.php');
	include($path.'functions/convert_header_names.php');
	include($path.'functions/find_samplers.php');
	
	if ($stmt->execute()){
	 			
			    if($stmt->fetch()){
			    	$meta = $stmt->result_metadata(); 
		   			while ($field = $meta->fetch_field()){
		        		$params[] = &$row[$field->name]; 
		    		} 
					
		    		call_user_func_array(array($stmt, 'bind_result'), $params); 
					echo '<div class="border">';
					echo '<table id = "datatable_bulk" style="width:90%;margin-left:5%; background-color:white">';
					$header_ct = 0;	
					$stmt->execute();
					$count_check = $stmt->fetch();
		    		$size =sizeof($count_check);
					$stmt->execute();
					$sample_names_seen = array();
		    		while ($stmt->fetch()) {
		    			
						if($header_ct == 0){
							$header_ct++;
							echo '<thead>';
		        			echo '<tr>';
							foreach($row as $key => $value){		
								$p_key = htmlspecialchars($key);
								$p_key = convert_header_names($p_key);
								if($p_key == 'false'){
									continue;
								}
								else{
									echo '<th>'.$p_key.'</th>';
								}		
							}
							echo '</tr>';
							echo '</thead>';
							
							echo '<tbody>';
						}
						
						echo '<tr>';
						$p_sample_name;
						foreach($row as $key => $value){
							$p_value = htmlspecialchars($value);
							$key = convert_header_names($key);
							if($key == 'false'){
								continue;
							}
							else{
								echo '<td>'.$p_value.'</td>';
							}
	
						}
						echo '</tr>';
							
					}		
					
		    		$stmt->close();
					echo '</tbody>';
					echo '</table>';
					
				}
				else{
					echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
				} 

		}	
	}		
?>