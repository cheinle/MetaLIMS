<?php	

//display table
function build_daily_data_output($stmt){
	echo "<pre>*Click On Button(s) For More Info<pre>";
	include($_SESSION['include_path'].'functions/convert_header_names.php');
	
	if ($stmt->execute()){
			    if($stmt->fetch()){
			    	$meta = $stmt->result_metadata(); 
		   			while ($field = $meta->fetch_field()){ 
		        		$params[] = &$row[$field->name]; 
		    		} 
		
		    		call_user_func_array(array($stmt, 'bind_result'), $params); 
				
					$stmt->execute();
					$seen = '';
					$seen_check = '';
					$counter = '0';
					$sample_names_array = array();
		    		while ($stmt->fetch()) {
		    			
						$sensorID;
						foreach($row as $key => $value){
							if($key == 'daily_date'){
								$sensorID = htmlspecialchars($value);
							}
							if($key == 'location'){
								$p_location = htmlspecialchars($value);
								if($sensorID == $seen){
									$seen_check = 'not_new';
									continue;
								}
								else{
									$seen = $sensorID;
									$seen_check = 'new';
									if($counter != '0'){//if you are not the beginning, add these to close the end of the last loop
										echo '</tr>';
										echo '</table>';
										echo '<br>';
										echo '</div>';
									}
									$mod_p_location = preg_replace("/\//",'-',$p_location);//jQuery cannot use slashes
									$mod_p_location = preg_replace("/\s+/",'-',$mod_p_location);//jQuery can also not use spaces
									$mod_p_location = preg_replace("/\#/",'-',$mod_p_location);//jQuery can also not use pound sign
									$mod_p_location = preg_replace("/\,/",'-',$mod_p_location);//all of the special chars ...
									echo '<button type="button"  data-toggle="collapse" data-target="#'.$sensorID.$mod_p_location.'" aria-expanded="true" aria-controls="demo" class="buttonLength">'.$sensorID.' '.$p_location.'</button><br>';
									echo '<div id="'.$sensorID.$mod_p_location.'" class="collapse">';
								}
							}
							else{
								if($key == 'part_sens_name'){
									$sample_names_array['part_sens_name'] = $value;
								}
								elseif($key == 'start_time'){
									$sample_names_array['start_time'] = $value;
								}
								elseif($key == 'end_time'){
									$sample_names_array['end_time'] = $value;
								}
								elseif($key == 'avg_measurement'){
									$sample_names_array['avg_measurement'] = $value;
								}
								elseif($key == 'record_source'){
									$sample_names_array['record_source'] = $value;
								}
								else{
									if($seen_check == 'not_new'){
										continue;
									}
									$key = convert_header_names($key);
									if($key == 'false'){
										continue;
									}
									echo '<p class="adjust"><strong>'.$key.'</strong>:  '.$value.'</p>';
								}
								
							}
						}
						if($seen_check == 'new'){
							echo '<table class="bulk">';
							echo '<th class="bulk">Sensor Name</th>';
							echo '<th class="bulk">Start Time</th>';	
							echo '<th class="bulk">End Time</th>';
							echo '<th class="bulk">Avg Measurement</th>';
							echo '<th class="bulk">Record Source</th>';		
						}
						
						echo '<tr>';
						foreach($sample_names_array as $value){
							echo '<td align="center"  style= "border: 1px solid black">'.$value.'</td>';
						}
						$counter++;
					}
					
				}
				else{
					echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
				}			
		}	
}

			

?>
