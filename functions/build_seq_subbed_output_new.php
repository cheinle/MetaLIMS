<?php	


//display table
function build_seq_subbed_output_new($stmt,$root){
	
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'/database_connection.php');
	include($path.'functions/convert_header_names.php');
	echo "*Click On Button(s) For More Info<br>";
	//first grab all of the uniq seq submission names
	//then for each seq submission name, grab all of the samples (and their info)
	
	$seq_id_array = array();
	$size = sizeof($seq_id_array);
	if($size == 0){
		echo '<script>alert("Sorry! No sequencing submission results found")</script>';
	}
	if ($stmt->execute()){
	  	$stmt->bind_result($seq_id);
	    while ($stmt->fetch()){
	    	//echo "Sequencing Submission ID: {$seq_id}<br>";
			$seq_id_array[] = $seq_id;
		}
	}
	$stmt->close();
	foreach($seq_id_array as $id){
		//foreach id, make a sql call
		//1) for the sample submission info
		//2) for each sample
		echo '<button type="button"  data-toggle="collapse" data-target="#'.$id.'" aria-expanded="true" aria-controls="demo" class="buttonLength">'.$id.'</button><br>';
		echo '<div id="'.$id.'" class="collapse">';
		//1
		$query_seq = "SELECT container_type,date_submitted,entered_by,library_prep_kit,sample_type,sequencing_method,sequencing_type,submitted_by,quant_method,read_length,seq_pool,amplicon_type,primerL,primerR,results_recieved FROM sequencing2 WHERE sequencing_info = ?";
		if($stmt_seq = $dbc->prepare($query_seq)){
			$stmt_seq -> bind_param('s', $id);
		//////////////
			$params_seq = array();
			if ($stmt_seq->execute()){
				while($stmt_seq->fetch()){
					$meta_seq = $stmt_seq->result_metadata(); 
				    while ($field_seq = $meta_seq->fetch_field()){ 
				    	$params_seq[] = &$row_seq[$field_seq->name]; 
				    } 
				
				    call_user_func_array(array($stmt_seq, 'bind_result'), $params_seq); 
				
					$stmt_seq->execute();
				    $counter = 0;
				    while ($stmt_seq->fetch()) {
						if($counter == '0'){
							echo '<tr>';
							foreach($row_seq as $key_seq => $value_seq){		
								$p_key_seq = htmlspecialchars($key_seq);
								$p_value_seq = htmlspecialchars($value_seq);
								$p_key_seq = convert_header_names($p_key_seq);
								if($p_key_seq == 'false'){
									continue;
								}
								else{
									if($p_key_seq == 'Results Recieved' && $p_value_seq == ''){
										echo '<strong>'.$p_key_seq.'</strong>: <a style="color: hotpink;" href="recieved_seq_results.php?id='.$id.'">Click Here To Mark Sequencing Results Recieved</a><br>';
									}else{
										echo '<strong>'.$p_key_seq.'</strong>:  '.$p_value_seq.'<br>';
									}
								}	
							}
						}
						
						$name_parts = explode("_",$id);
						
						$filename = "SamplesSubmissionForm_".$name_parts[0].'_'.$name_parts[1].".xlsx";
						echo '<strong>Sample Submission Form</strong>:<a style="color: hotpink" href='.$root.'sequencing/sequencing_sample_submission_forms/'.$filename.' download>Click Here To Download</a><br>';
					
					}
				}
			}else{
				//throw execute error	
				throw new Exception("Execution Error: Unable To Retrieve Sequencing Info");
			}
		}else{
			//throw prepare error
			throw new Exception("Preperation Error: Unable To Retrieve Sequencing Info");
		}	
		$stmt_seq->close();
		
	
		////////////////
		//2
		
		echo '<table class=\'bulk\'>';
		$query_sample = "SELECT * FROM sample_sequencing2 WHERE seq_id = ?";
		if($stmt_sample = $dbc->prepare($query_sample)){
			$stmt_sample -> bind_param('s', $id);
			$params_sample = array();
			if ($stmt_sample->execute()){
				while($stmt_sample->fetch()){
					$meta_sample = $stmt_sample->result_metadata(); 
				    while ($field_sample = $meta_sample->fetch_field()){ 
				    	$params_sample[] = &$row_sample[$field_sample->name]; 
				    } 
				
				    call_user_func_array(array($stmt_sample, 'bind_result'), $params_sample); 
				
					$stmt_sample->execute();
				    //////////////////////
				    $counter = 0;
				    while ($stmt_sample->fetch()) {
						if($counter == '0'){
							echo '<tr>';
							foreach($row_sample as $key => $value){		
								$p_key = htmlspecialchars($key);
								$p_key = convert_header_names($p_key);
								if($p_key == 'false'){
									continue;
								}
								else{
									echo '<th class=\'bulk\' >'.$p_key.'</th>';
								}	
							}	
							echo '</tr>';
							echo '<tr>';
							foreach($row_sample as $key => $value){
								if($p_key == 'seq_id'){
									continue;
								}
								$p_value= htmlspecialchars($value);
								echo '<td class=\'bulk\'>'.$p_value.'</td>';
							}
							echo '</tr>';
							
						}
						else{
							echo '<tr>';
							foreach($row_sample as $key => $value){
								$p_value= htmlspecialchars($value);
								echo '<td class=\'bulk\'>'.$p_value.'</td>';
							}
							echo '</tr>';
						}
						$counter++;
					}
				    
				////////////////////////
				}
			}
			else{
				//throw execute error
				throw new Exception("Execution Error: Unable To Retrieve Sequencing Info");	
								
			}
			$stmt_sample->close();
			echo '</table>';
			echo '</div>';
			echo '<p class="adjust"></p>';
		}
		else{
			//throw prepare error
			throw new Exception("Preperation Error: Unable To Retrieve Sequencing Info");						
		}
	}
}
	
?>
