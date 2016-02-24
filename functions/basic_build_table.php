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
					
		    		echo '<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">';
					#echo '<table>';
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
								echo '<th class = "reg">'.$p_key.'</th>';
							}		
						}
						echo '</tr>';
						echo '</thead>';
					}
					echo '<tbody>';
					echo '<tr>';
					$p_sample_name;
					foreach($row as $key => $value){
						$p_value = htmlspecialchars($value);
						$key = convert_header_names($key);
						if($key == 'false'){
							continue;
						}
						else{
							echo '<td class = "reg">'.$p_value.'</td>';
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

			/*
		   if($stmt->fetch()){
			    	$meta = $stmt->result_metadata(); 
		   			while ($field = $meta->fetch_field()){ 
		        		$params[] = &$row[$field->name]; 
		    		} 
		
		    		call_user_func_array(array($stmt, 'bind_result'), $params); 
					
		    		echo '<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">';
					#echo '<table>';
					$header_ct = 0;	
					$stmt->execute();
					$count_check = $stmt->fetch();
		    		$size =sizeof($count_check);
					$stmt->execute();
		    		while ($stmt->fetch()) {
						//print out headers
						if($header_ct == 0){
							echo '<thead>';
			        		echo '<tr>';
								
							foreach($row as $key => $value){		
								$p_key = htmlspecialchars($key);
								$p_key = convert_header_names($p_key);
								if($p_key == 'false'){
									continue;
								}
								else{
									echo '<th class = "reg">'.$p_key.'</th>';
								}		
							}
							$header_ct++;
							echo '</tr>';
							echo '</thead>';
							
							echo '<tbody>';
							echo '<tr class = "row_collapse">';
							foreach($row as $key => $value){
								#echo $value;
								$p_value = htmlspecialchars($value);
								
								if($key == 'start_samp_date_time' && isset($_SESSION['label_prep'])){
									$date_time = explode(" ",$p_value);
									$p_value = $date_time[0];
								}
								if($key == 'sample_num'){//check that returned number is output as 3 digits
									$regrex_check_sn1  = '/^[0-9]$/';
									if (preg_match("$regrex_check_sn1", $p_value)){
										$p_value= '00'.$p_value;
									}
									else{
										$regrex_check_sn2  = '/^[0-9][0-9]$/';
										if (preg_match("$regrex_check_sn2", $p_value)){
											$p_value = '0'.$p_value;
										}
									}
								}
								if($key == 'total_samp_time'){
									$p_value = convert_time($key, $p_value);
								}
								
								
								$key = convert_header_names($key);
								if($key == 'false'){
									continue;
								}
								else{
									echo '<td class = "reg">'.$p_value.'</td>';
								}
									
							}
							echo '</tr>';
						}
						else{						
							//print out fields
							echo '<tr>';
							
							foreach($row as $key => $value){
								#echo $value;
								$p_value = htmlspecialchars($value);
								
								if($key == 'start_samp_date_time' && isset($_SESSION['label_prep'])){
									$date_time = explode(" ",$p_value);
									$p_value = $date_time[0];
								}
								if($key == 'sample_num'){//check that returned number is output as 3 digits
									$regrex_check_sn1  = '/^[0-9]$/';
									if (preg_match("$regrex_check_sn1", $p_value)){
										$p_value= '00'.$p_value;
									}
									else{
										$regrex_check_sn2  = '/^[0-9][0-9]$/';
										if (preg_match("$regrex_check_sn2", $p_value)){
											$p_value = '0'.$p_value;
										}
									}
								}
								if($key == 'total_samp_time'){
									$p_value = convert_time($key, $p_value);
								}
								$key = convert_header_names($key);
								if($key == 'false'){
									continue;
								}
								else{
									echo '<td class = "reg">'.$p_value.'</td>';
								}
		
							}
							$header_ct++;
							echo '</tr>';
							
							if($header_ct == $size-1){
								echo '<tbody>';
							}
								
							
						}
						
						
					}		
					
		    		$stmt->close();
					echo '</table>';
				}
				else{
					echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
				} 
			}*/
			}
			
?>