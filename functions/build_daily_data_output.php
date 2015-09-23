<?php	

//display table
function build_daily_data_output($stmt){
	include('convert_header_names.php');
	echo "*Click On Button(s) For More Info<br>";
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
		    			
						foreach($row as $key => $value){
							if($key == 'daily_date'){
								$seq_subID = htmlspecialchars($value);
								if($seq_subID == $seen){
									$seen_check = 'not_new';
									continue;
								}
								else{
									$seen = $seq_subID;
									$seen_check = 'new';
									if($counter != '0'){//if you are not the beginning, add these to close the end of the last loop
										echo '</tr>';
										echo '</table>';
										echo '<br>';
										echo '</div>';
									}
									echo '<button type="button"  data-toggle="collapse" data-target="#'.$seq_subID.'" aria-expanded="true" aria-controls="demo" class="buttonLength">'.$seq_subID.'</button><br>';
									echo '<div id="'.$seq_subID.'" class="collapse">';
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
								else{
									if($seen_check == 'not_new'){
										continue;
									}
									$key = convert_header_names($key);
									echo '<p class="adjust"><strong>'.$key.'</strong>:  '.$value.'</p>';
								}
								
							}
						}
						if($seen_check == 'new'){
							echo '<table class="bulk">';
							echo '<th class="bulk">Sensor Name</th>';
							echo '<th class="bulk">Start Time</th>';	
							echo '<th class="bulk">End Time</th>';	
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
