<?php
include('../config/path.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;
include ('../database_connection.php');
include ($path.'functions/dropDown.php');
include ($path.'functions/convert_header_names.php');
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sample Pooling</title>	
	</head>
	
	<body><?php include ('../index.php');?>
	<div class="page-header">
	<h3>Sample Pooling Form</h3>
	</div>
	
<?php 	
		//a check to see if user has tried to submit data
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';//so far you have no errors
			$initial_error_check = 'false';//so far you have no errors
			
			//sanatize user input and check for required fields
			$p_mydate = htmlspecialchars($_GET['mydate']);
			$p_projName = htmlspecialchars($_GET['projName']);
			if($p_mydate == ''){
					echo '<p>You Must Enter A Date!<p>';
					$initial_error_check = 'true';
			}
			if($p_projName == ''){
					echo '<p>You Must Enter A Project Name!<p>';
					$initial_error_check = 'true';
			}
			
			//sample type will be 'P' for pooled
			$p_sample_type = 'P';
			
			//check that all samples names entered exist in the database
			//create a list & array of the sample names
			
			$pooled_sample_names = '';
			$pooled_sn_array = array();
			$num_of_sens = $_GET['pool_num'];
			$sample_ct = 0;
			for ($x = 1; $x <= $num_of_sens; $x++) {
				$sample_ct++;
				$sample[$x] = htmlspecialchars($_GET['sample_name'.$x]);
				$pooled_sn_array[] = $sample[$x];
				
				//cat sample names together
				if($sample_ct == '1'){
					$pooled_sample_names = $sample[$x];	
				}
				else{
					$pooled_sample_names = $pooled_sample_names.','.$sample[$x];	
				}
				
				//check to see that name exists in in DB in sample table
				$stmt1 = $dbc->prepare("SELECT sample_name,sample_sort,project_name FROM sample WHERE sample_name = ?");
				$stmt1 -> bind_param('s', $sample[$x]);	
	  			if ($stmt1->execute()){
	    			$stmt1->bind_result($name,$name2,$sample_project_name);
					$counter = 0;
					$name_check = 'false';
	    			while ($stmt1->fetch()){
	        			$counter++;
	        			if($name == $sample[$x]){//you have found your sample in the databse
	        				$name_check = 'true';
						}
					}
					
					if($counter == 0){//if you retrieved nothing from the db
						echo $sample[$x]." Does Not Exist. Please Check Sample Name.<br>";
						$initial_error_check = 'true';
					}
					if($name_check == 'false'){//if you have retrieved names similar to yours but not yours...would this ever be true?
						echo $sample[$x]." Does Not Exisit. Please Check Sample Name.<br>";
						$initial_error_check = 'true';
					}
					//check if project names are the same as user has choosen
					if($sample_project_name != $p_projName){
						echo $sample[$x]." Does Not Have The Project Name-".$p_projName.'. Please check Sample.<br>';
						$initial_error_check = 'true';
					}
				} 
				else {
					$error = 'true';
	    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				};
				$stmt1 -> close();			
			}
			
			//check if there are duplicates sample names
			if (count(array_unique($pooled_sn_array)) < count($pooled_sn_array)){
 				echo 'ERROR: Duplicate Sample Names Exisit. Please Check Names<br>';
				$initial_error_check = 'true';
			 }
			
			//if all names exist, sort names,
			//grab the project name and find the last sampled entered
			//new name includes a p and db has a flag for pooled samples
			//store pooled samples in a new pooled sample table so they can be queried easily
			//update note section for all samples and disable editing for each of the pooled samples on update samples page (and update storage/bulk entry?)
			//update storage info for new sample and old samples
			
			//grab the last number entered into the project
			$stmt2 = $dbc->prepare("SELECT sample_name,sample_sort FROM sample WHERE BINARY project_name = ?");		
	  		$stmt2 -> bind_param('s', $p_projName);
	  		if ($stmt2->execute()){
	    		$stmt2->bind_result($name,$name2);
	    		while ($stmt2->fetch()){
	    			$data[] = array('sample_name' => $name, 'sample_sort' => $name2);
				}
			} 
			else {
				$initial_error_check = 'true';
	    		die('execute() failed: ' . htmlspecialchars($stmt2->error));
			};
			$stmt2 -> close();		
			
			// Obtain a list of columns
			foreach ($data as $key => $row) {
			    $ss[$key]  = $row['sample_sort'];
			    $sn[$key] = $row['sample_name'];
			}
			
			// Sort samples and grab the last one in the list
			array_multisort($ss, SORT_ASC, $sn, SORT_ASC, $data);
			$last_sample_entered = array_pop($data);
			#echo $last_sample_entered['sample_sort'];
			
			//grab sample number from last sample entered
			$regrex_check = '/.*([0-9]{3})$/';
			preg_match($regrex_check,$last_sample_entered['sample_sort'],$matches);
			$last_sample_number = $matches[1];
			
			//create new sample number
			$new_sample_number = $last_sample_number + 1;
			if($new_sample_number > '999'){
				echo "ERROR: Project Number Exceeds Sample Limit. Please Check Project Or Contact Admin For Assistance<br>";
				$initial_error_check = 'true';
			}
			
			//check that sample number is three digits. If not, make it 3
			$regrex_check_sn1  = '/^[0-9]$/';
			if (preg_match("$regrex_check_sn1", $new_sample_number )){
				$new_sample_number = '00'.$new_sample_number ;
			}
			else{
				$regrex_check_sn2  = '/^[0-9][0-9]$/';
				if (preg_match("$regrex_check_sn2", $new_sample_number )){
					$new_sample_number = '0'.$new_sample_number ;
				}
			}
			
			//format date
			$regrex_check = '/^(20[0-9][0-9])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
			preg_match($regrex_check,$p_mydate,$matches);
			$p_mydate = $matches[1].'/'.$matches[2].'/'.$matches[3];
			
			//create new sample name
			$new_sample_name = $p_mydate.$p_projName.$p_sample_type.$new_sample_number;
			$new_sample_sort = $p_projName.$new_sample_number;
			$sampler_name = '(pooled)';
			$collector_name = '(pooled)';	
			$start_time = $p_mydate.' 01:00:00';
			$end_time = $p_mydate.' 01:00:00';
			$total_sampling_time = '0';											
			$location_name = '(pooled)';
			$relt_location_name = '(pooled)';												
			$media_type = '(pooled)';												
			$notes = $p_mydate.' pooled the following samples:'.$pooled_sample_names.'. New sample name:'.$new_sample_name;
			$part_sens_name = '(pooled)';
			$sampling_height = '0.00';
			$sample_type = 'P';
			$pooled_flag = 'NP';
			$s_flow_rate = '0.00';
			$e_flow_rate = '0.00';
			
			//get username and update entered by with
			$entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
			#$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
			
			//get current time
			$orig_time_stamp = date_default_timezone_set("Asia/Singapore");
			$orig_time_stamp = date("Y-m-d H:i:s");
			
			//grab seq_id_start from project name
			//grab abbreviated project name to create new ID for sequencing submission
			$seq_id = '';
			$stmt_sid= $dbc->prepare("SELECT seq_id_start FROM project_name WHERE project_name = ?");
			$stmt_sid -> bind_param('s', $p_projName);
			if ($stmt_sid->execute()){
	  			$stmt_sid->bind_result($name);
	    		if ($stmt_sid->fetch()){
	        		$seq_id = $name.$new_sample_number;
				}
			} 
			else {
				$initial_error_check = 'true';
	    		die('execute() failed: ' . htmlspecialchars($stmt->error));
					
			}
			$stmt_sid -> close();
			
			
			//if there were no initial errors, try and insert info to db
			if($initial_error_check == 'false'){
			
				//start a transaction to make sure everything gets entered									
				try{
					
					//start transaction
					$dbc->autocommit(FALSE);
						
					//create new entry with note in sample entry about which samples are pooled
					$stmt3 = $dbc -> prepare("INSERT INTO sample (sample_name,
																	sample_sort,
																	collector_name,
																	end_samp_date_time,
																	entered_by,
																	location_name,
																	media_type,
																	notes,
																	orig_time_stamp,
																	part_sens_name,
																	project_name,
																	relt_loc_name,
																	sampling_height,
																	sample_type,
																	seq_id,
																	start_samp_date_time,
																	total_samp_time,
																	sample_num,
																	pooled_flag,
																	flow_rate,
																	flow_rate_eod
																
					) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
								
					if(!$stmt3){		
						#echo "Prepare failed: (" . $dbc->errno . ") " . $dbc->error;
						$error = 'true';
						throw new Exception("Prepared Failure For Sample Insertion");
					}
					else{
						$stmt3 -> bind_param('ssssssssssssdsssdisdd', 
									 						$new_sample_name, 
									 						$new_sample_sort,
									 						$collector_name,
									 						$end_time,
									 						$entered_by,
									 						$location_name,
									 						$media_type,
									 						$notes,
									 						$orig_time_stamp,
									 						$part_sens_name,
									 						$p_projName,
									 						$relt_location_name,
									 						$sampling_height,
									 						$sample_type,
									 						$seq_id,
									 						$start_time,
									 						$total_samp_time,
									 						$new_sample_number,
									 						$pooled_flag,
									 						$s_flow_rate,
									 						$e_flow_rate
															);
									
						
						if(!$stmt3 -> execute()){
							$error = 'true';
							#echo "execute failed!: (" . $dbc->errno . ") " . $dbc->error;
							throw new Exception("Prepared Statement Fail: Unable To Execute For Creation Of New Sample");	
						}
						else{
							$rows_affected3 = $stmt3 ->affected_rows;
							$stmt3 -> close();
										
							//check if add was successful or not. Tell the user
							if($rows_affected3 > 0){
								echo 'Successful Insertion Of New Pooled Sample:'.$new_sample_name.'<br>';
								$original = 'Used,Used';
								$p_orig_sample_exist = 'false';	

								///
								//insert storage_info for new pooled entry. ...but will you know where the new storage space is?
								//user will have to update this. so tell them? You are assuming user will have to update entry with DNA
								//extraction info and storage info...also tell them; or do you really need this part because sample insert will update this
								//for the user?//original sample for a pooled sample would automatically be assumed used up due to the nature of
								//having to pool
								$original = 'Used,Used';
								$p_orig_sample_exist = 'false';
								
								$stmt_si = $dbc -> prepare("INSERT INTO storage_info (sample_name,original, orig_sample_exists) VALUES (?,?,?)");
								$stmt_si -> bind_param('sss',$new_sample_name,$original,$p_orig_sample_exist);
								$stmt_si -> execute();
								$rows_affected_si = $stmt_si ->affected_rows;
								if($rows_affected_si > 0){
									echo "Successfull Insert Of Sample: ".$new_sample_name." Into Storage Info<br>";
								}
								else{
									$error = 'true';
			    					throw new Exception("ERROR: Insert For Storage Info For New Pooled Sample Failed. Please See Admin");
								}
							}
							else{
								$error = 'true';
			    				throw new Exception("ERROR: Insert Of Sample For New Pooled Sample Failed. Please See Admin");
							}
						}
					}
					/***************************************************************************************
					//Insert Samplers !!! (must be after insert of sample to db because of key constraints)
					****************************************************************************************/
					$query_samp = "INSERT INTO sample_sampler (sample_name, sampler_name, start_date_time,end_date_time,total_date_time) VALUES (?,?,?,?,?)";
					$stmt_samp = $dbc -> prepare($query_samp);
					if(!$stmt_samp){
						throw new Exception("Prepare Failure: Unable To Insert Sample Sampler");	
					}
					else{
						$stmt_samp -> bind_param('ssssd', $new_sample_name,$sampler_name,$start_time,$end_time,$total_sampling_time);
						if($stmt_samp -> execute()){
							$rows_affected_samp = $stmt_samp ->affected_rows;
							$stmt_samp -> close();
							//check if add was successful or not. Tell the user
							if($rows_affected_samp <= 0){
								$error = 'true';
								throw new Exception("An Error Occurred: No Sampler Info Added");
							}
						}
						else{
							$error = 'true';
							throw new Exception("Execution Failure: Unable To Insert Sampler");	
						}
					}
					
					/***************************************************************************************
					//add insert into new table for number_of_seq_submissions. No update/edit for this exists 
					****************************************************************************************/
					$stmt_seq_num = $dbc -> prepare("INSERT INTO number_of_seq_submissions (sample_name) VALUES (?)");
					if(!$stmt_seq_num){
						$error = 'true';
						throw new Exception("Prepare Failure: Unable to insert sample into Sequence Number Submission table");	
					}
					else{
						$stmt_seq_num -> bind_param('s', $new_sample_name);
						if(!$stmt_seq_num-> execute()){
							$error = 'true';
							throw new Exception("Execution Failure: Unable to enter sample into Sequence Number Submission table.");	
						}
						else{
							$rows_affected_seq_num = $stmt_seq_num ->affected_rows;
							$stmt_seq_num -> close();
							if($rows_affected_seq_num < 0){
								$error = 'true';
								throw new Exception("Unable to insert sample into Sequence Number Submission table");	
							}
						}
					}
					
					//update all old samples with note for new sample number and pooled samples
					//also add each of sample to the pooled_sample_lookup table
					foreach($pooled_sn_array as $key => $value){
						//grab existing note from sample and append new one to the old one
						$old_notes = '';
						$stmt4 = $dbc->prepare("SELECT sample_name,notes FROM sample WHERE sample_name = ?");
						$stmt4 -> bind_param('s', $value);
			  			if ($stmt4->execute()){
			    			$stmt4->bind_result($name,$o_notes);
							$counter = 0;
							$name_check = 'false';
			    			while ($stmt4->fetch()){
			        			if($name == $value){//you have found your sample in the databse
			        				$old_notes = $o_notes;
								}
							}
						} 
						else {
							$error = 'true';
			    			throw new Exception("execute() failed. Unable to select previous note information");
						};
						$stmt4 -> close();			
		
						$new_notes = $old_notes.' '.$notes;
						$part_of_pool = 'Y';
						//insert the new notes into each of the pooled samples
						$query_sn = 'UPDATE sample SET notes = ?, part_of_pool = ? WHERE sample_name = ?';		
						if($stmt_sn = $dbc ->prepare($query_sn)) {
							$stmt_sn->bind_param('sss', $new_notes,$part_of_pool,$value); 
							$stmt_sn -> execute();
							$rows_affected_sn = $stmt_sn ->affected_rows;
							if($rows_affected_si > 0){
								$value = convert_header_names($value);
								echo 'SUCCESS: Updated Notes For:'.$value.' <br>';	
							}
							elseif($rows_affected_si == 0){
								//for testing purposes only
								#echo "No Need To Update For ".$value.'<br>';
							}
							else{
								$error = 'true';
								throw new Exception('An error has occurred in updating note info for '.$value);
							}
						}
						else{
							$error = 'true';
							throw new Exception("ERROR: Note Update Prepare Failure");
						}

						$stmt_ps = $dbc -> prepare("INSERT INTO pooled_sample_lookup (new_pooled_samp_name, orig_sample_name, date_entered, entered_by) VALUES (?,?,?,?)");
								
						if(!$stmt_ps){		
							$error = 'true';
							throw new Exception("Prepared Failure For Update to Pooled Samples Table");
						}
						else{
							$stmt_ps -> bind_param('ssss',$new_sample_name,$value,$p_mydate,$entered_by);
							if(!$stmt_ps -> execute()){
								$error = 'true';
								throw new Exception("Bind Param Fail: Unable To Execute For Pooled Samples.");	
							}
							else{
								$rows_affected_ps = $stmt_ps ->affected_rows;
								$stmt_ps -> close();
											
								//check if add was successful or not. Tell the user
								if($rows_affected_ps > 0){
									#echo 'Successful Insertion Of New Sample:'.$new_sample_name;
								}
								else{
									$error = 'true';
									throw new Exception('Error: Insert of New Sample Pooling Info Failed For Sample: '.$value);
								}
							}
						}
						//////
					}
	
	
					
					if($error != 'true'){
						$submitted = 'true';
	    				echo '<script>Alert.render("SUCCESS: You Have Made Your New Pooled Sample. Please Update DNA Extraction Info For Sample: ".$new_sample_name.".");</script>';
						$dbc->commit();
					}
					else{
						throw new Exception('ERROR: No Updates Were Made. Please Check Above Errors!');			
					}
				}
				catch (Exception $e) { 
	    			if (isset ($dbc)){
	    				echo '<script>Alert.render("ERROR: Please See Notes. All Changes Have Been Rolled Back. No Changes Have Been Made");</script>';
	       	 			$dbc->rollback ();
	       				echo "Error:  " . $e.'<br>'; 
	    			}
				}
			}
			else{
				echo "ERROR: Not Updates Made. Please See Above ERRORS!";
			}
		}

	
	?>
	<form class="registration" onsubmit="return validate(this)" action="pool_samples.php" method="GET" target="_blank">
	<p><i>* = required field </i><br>
		<a href="<?php echo $root;?>pooling/how_pooled_samples_created.php">How Pooled Samples Are Created In the DB</a>
	</p>
		
		<fieldset>
		<LEGEND><b>Pooling Info:</b></LEGEND>
		<div class="col-xs-6">
		<label class="textbox-label">Date:<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="This is the date that will be given to the new pooled sample created"'></i></label>
		<input type="text" id="datepicker"  name="mydate" class="fields" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>"></p>
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		
		
		<!--Project Name Dropdown-->
		<label class="textbox-label">Select Project Name:<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Only samples belonging to the same project can currently be pooled. Samples selected will be checked that they belong to the project name selected"></i></label>
		<?php
		//url or $_GET name, table name, field name
		dropDown('projName', 'project_name', 'project_name','project_name',$submitted,$root);
		?>
		
		
		<h3 class="checkbox-header">Select Pooling Type:<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Samples can only be pooled for DNA Extraction at this time. No pooling is availible for Sequencing Submission"></i></h3>
 		<div class="vert-checkboxes">
 		<label class="checkbox-label"><input type="radio" name="pool_type" value="DNA" id="pool_type_dna"<?php if((isset($_GET['submit']) && $submitted != 'true')){if($_GET['pool_type'] == 'DNA'){echo 'checked';}} ?>/>Pool Samples for DNA Extraction<br /></label>
		<!--<label class="checkbox-label"><input type="radio" name="pool_type" value="Library" id ="pool_type_library"<?php if((isset($_GET['submit']) && $submitted != 'true')){if($_GET['pool_type']== 'Library'){echo 'checked';}} ?>  data-toggle="popover" title=" Currently Only Able To Pool For DNA Extractions"/>Pool DNA Samples for Library Submission<br /></label>-->
		</div>
		</fieldset>
		<script>
			$(document).ready(function(){
				$('#pool_type_library').click(function(event) {
					$( "#pool_type_dna" ).prop( "checked", true );
				});
    			$('[data-toggle="popover"]').popover({
        			placement : 'right'
    			});
    			
			});
		</script>

		
		<fieldset>
		<LEGEND><b>Samples For Pool:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Pick Number Of Samples To Pool:</label>
		<select id='pool_num' name='pool_num'>
		<option value='0'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['pool_num']) && $_GET['pool_num'] == "0" )){ echo "selected";}}?>>-Select-</option>
		<option value='2'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['pool_num']) && $_GET['pool_num'] == "2" )){ echo "selected";}}?>>-2:Two-</option>
		<option value='3'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['pool_num']) && $_GET['pool_num'] == "3" )){ echo "selected";}}?>>-3:Three-</option>
		<option value='4'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['pool_num']) && $_GET['pool_num'] == "4" )){ echo "selected";}}?>>-4:Four-</option>
		<option value='5'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['pool_num']) && $_GET['pool_num'] == "5" )){ echo "selected";}}?>>-5:Five-</option>
		</select>
		<div id="div_pool">
		</div>
		</p>
		</fieldset>
		<script type="text/javascript">		
			    function validate(from) {
			    	
			    	//if you tried to submit, check the entire page for color?
			    	//return valid is false if you find it
			    	
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Are you sure you want to submit?');
				    }
				}
				
				function check_form(){
					var valid = 'true';
					var index;
					var x = document.getElementById('pool_num').value;
					if(x == 0){
						valid = 'false';
					}
					for (index = 1; index <= x; ++index) {
   	 					var sample_name = 'customerAutocomplte'+index;
   	 					var sample_name_value = document.getElementById(sample_name).value;
   	 					if(sample_name_value == ''){
   	 						document.getElementById(sample_name).style.backgroundColor = 'yellow';
   	 						valid = 'false'
   	 					}
   	 				
					}
					//check project name
					var project_name_value = document.getElementById('projName').value;
   	 				if(project_name_value == 0){
   	 					document.getElementById('projName').style.backgroundColor = 'yellow';
   	 					valid = 'false'
   	 				}
   	 				
   	 				//check date
					var date_value = document.getElementById('datepicker').value;
   	 				if(date_value == ''){
   	 					document.getElementById('datepicker').style.backgroundColor = 'yellow';
   	 					valid = 'false'
   	 				}
					return valid;
				}
			
			</script>
			</div>
			<input type='submit' class="button" id="sub"  name ="submit" value='Update Samples' />
		
		<!--submit button-->
		<!--<p><button class="btn btn-success" type="submit" name="submit" value="1"> Add </button></p>-->
		<p><input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" /></p>
		
	</form>

	</body>
</html>
