<?php	

//display table
function build_table_tab($stmt,$table_type){ //table types are 'dislapy' and 'xls'
	include('convert_time.php');
	include('convert_header_names.php');
	include('find_samplers.php');
	
	$myfile = fopen("document_name.xls", "w") or die("Unable to open file!");

	if ($stmt->execute()){
	 			
			    if($stmt->fetch()){
			    	$meta = $stmt->result_metadata(); 
		   			while ($field = $meta->fetch_field()){
		        		$params[] = &$row[$field->name]; 
		    		} 
					
		    		call_user_func_array(array($stmt, 'bind_result'), $params); 
					
		    		//echo '<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">';
					#echo '<table>';
					$header_ct = 0;	
					$stmt->execute();
					$count_check = $stmt->fetch();
		    		$size =sizeof($count_check);
					$stmt->execute();
					$sample_names_seen = array();
		    		while ($stmt->fetch()) {
		    			
						
						//print out headers
						if($header_ct == 0){
							//echo '<thead>';
			        		//echo '<tr>';
								
							foreach($row as $key => $value){		
								$p_key = htmlspecialchars($key);
								$p_key = convert_header_names($p_key);
								if($p_key == 'false'){
									continue;
								}
								else{
									//echo '<th class = "reg">'.$p_key.'</th>';
									$p_key = $p_key."\t";
									fwrite($myfile, $p_key);
									
								}		
							}
							fwrite($myfile, "\n");
							$header_ct++;
							//echo '</tr>';
							//echo '</thead>';
							
							
							
							//echo '<tbody>';
							//echo '<tr class = "row_collapse">';
							$p_sample_name;
							foreach($row as $key => $value){
								$p_value = htmlspecialchars($value);
								if($key == 'sample_name'){
									$p_sample_name = $p_value;
									if (in_array($p_sample_name, $sample_names_seen)){
										break;
									}else{
										//echo '<tr class = "row_collapse">';
										array_push($sample_names_seen,$p_sample_name);
									}
								}
							
								
					
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
								if($key == 'sampler_name'){
									$p_value = find_samplers($p_sample_name,$table_type);
								}
								if($key == 'total_samp_time'){
									$p_value = convert_time($key, $p_value);
								}
								
								
								$key = convert_header_names($key);
								if($key == 'false'){
									continue;
								}
								else{
									//echo '<td class = "reg">'.$p_value.'</td>';
									$p_value = preg_replace( "/\r|\n/", "", $p_value );
									$p_value = $p_value."\t";
									fwrite($myfile, $p_value);
								}
							
									
							}
							//echo '</tr>';
							fwrite($myfile, "\n");
						}
						else{						
							//print out fields
						
							//echo '<tr>';
							$break_flag = 'N';
							foreach($row as $key => $value){
								$p_value = htmlspecialchars($value);
								if($key == 'sample_name'){
									$p_sample_name = $p_value;
									if (in_array($p_sample_name, $sample_names_seen)){
										$break_flag = 'Y';
										break;
									}else{
										//echo '<tr>';
										array_push($sample_names_seen,$p_sample_name);
									}
								}
								
								
							
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
								if($key == 'sampler_name'){
									$p_value = find_samplers($p_sample_name,$table_type);
								}
								if($key == 'total_samp_time'){
									$p_value = convert_time($key, $p_value);
								}	
								
								$key = convert_header_names($key);
								if($key == 'false'){
									continue;
								}
								else{
										//echo '<td class = "reg">'.$p_value.'</td>';
										$p_value = preg_replace( "/\r|\n/", "", $p_value );
										$p_value = $p_value."\t";
										fwrite($myfile, $p_value);
								}
								
		
							}
							$header_ct++;
							//echo '</tr>';
							if($break_flag == 'N'){
								fwrite($myfile, "\n");
							}
							//if($header_ct == $size-1){
								//echo '</tbody>';
							//}
								
							
						}
						
						
					}		
					
		    		$stmt->close();
					//echo '</table>';
				}
				else{
					echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
				} 
			}

			fclose($myfile);
}	

			
?>