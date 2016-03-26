<?php	

//display table
function build_pooled_samp_output($stmt){
		include($_SESSION['include_path'].'functions/convert_header_names.php');
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
							if($key == 'new_pooled_samp_name'){
								$pooled_name = htmlspecialchars($value);
								if($pooled_name == $seen){
									$seen_check = 'not_new';
									continue;
								}
								else{
									$seen = $pooled_name;
									$seen_check = 'new';
									if($counter != '0'){//if you are not the beginning, add these to close the end of the last loop
										echo '</tr>';
										echo '</table>';
										echo '<br>';
										echo '</div>';
									}
									$pooled_name= preg_replace("/\//",'-',$pooled_name);//jQuery cannot use slashes
									$pooled_name= preg_replace("/\s+/",'-',$pooled_name);//jQuery can also not use spaces
									echo '<button type="button"  data-toggle="collapse" data-target="#'.$pooled_name.'" aria-expanded="true" aria-controls="demo" class="buttonLength">'.$pooled_name.'</button><br>';
									echo '<div id="'.$pooled_name.'" class="collapse">';
								}
							}
							else{
								if($key == 'orig_sample_name'){
									$sample_names_array['orig_sample_name'] = $value;
								}
								else{
									if($seen_check == 'not_new'){
										continue;
									}
									$key = convert_header_names($key);
									echo '<p class="adjust"><strong>'.$key.'</strong>:  '.$value.'<br></p>';
								}
								
							}
						}
						if($seen_check == 'new'){
							echo '<table class=\'bulk\'>';
							echo '<th class=\'bulk\'>Original Sample Name</th>';
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
