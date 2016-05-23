<?php
		include ('../database_connection.php');
		//sample not yet submitted
		$submitted = 'false';
		//error checking

				$error_check = 'false';

				//sanatize user input to make safe for browser
				$p_sample_number = htmlspecialchars($_GET['sample_number']);
				$p_projName = htmlspecialchars($_GET['projName']);
				$p_loc = htmlspecialchars($_GET['loc']);
				$p_rloc = htmlspecialchars($_GET['rloc']);
				$p_partSamp = NULL;
				$p_poolEx = '0';//pooling of extracts has been moved to another page
				$p_dExtKit = htmlspecialchars($_GET['dExtKit']);
				$p_rExtKit = htmlspecialchars($_GET['rExtKit']);
				$p_seqInfo = '';
				$p_anPipe = htmlspecialchars($_GET['anPipe']);
				$p_barcode = htmlspecialchars($_GET['barcode']);
				$p_sType = htmlspecialchars($_GET['sType']);
				$p_path = ''; //removing for now
				$p_dConc = htmlspecialchars($_GET['dConc']);
   				$p_dInstru = htmlspecialchars($_GET['dInstru']);
   				$p_dVol = htmlspecialchars($_GET['dVol']);
				$p_dVol_quant = htmlspecialchars($_GET['dVol_quant']);
				$p_d_extr_date = htmlspecialchars($_GET['d_extr_date']);
				$p_rConc = htmlspecialchars($_GET['rConc']);				
				$p_rInstru = htmlspecialchars($_GET['rInstru']);	
				$p_rVol = htmlspecialchars($_GET['rVol']);	
				$p_rVol_quant = htmlspecialchars($_GET['rVol_quant']);
				$p_r_extr_date = htmlspecialchars($_GET['r_extr_date']);
				$p_notes = htmlspecialchars($_GET['notes']);
				$p_fRate = htmlspecialchars($_GET['fRate']);
				$p_fRate_eod = htmlspecialchars($_GET['fRate_eod']);
				$p_dData = '0';//removing for now
				$p_dWeather = '0';//removing for now
				$p_media = htmlspecialchars($_GET['media']);
				$p_sampling_height = htmlspecialchars($_GET['sampling_height']);
				$my_samplers = $_GET['my_samplers'];
				$start_dates = $_GET['start_dates'];
				$end_dates = $_GET['end_dates'];
				$start_times = $_GET['start_times'];
				$end_times = $_GET['end_times'];
				
				//user things
				if(isset($_GET['user_things'])){
					$p_user_things = $_GET['user_things'];
				}
				else{
					$p_user_things = NULL;
				}
				
				
				$sample_type_regrex = '/^B.*/';//if you are a blank then your flow rate is zero. so is your time
				$sample_type_check = preg_match($sample_type_regrex,$p_sType);
				if($sample_type_check == true){
						$p_fRate = '0';
						$p_fRate_eod = '0';	
				}
				
				//check and process collector info
				include_once("../functions/check_collector_names.php");
				$array=$_GET['collector'];
				$check = check_collector_names($array,'true');
				if($check['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_collName = $check['cat_name'];
				}
				
				//check and process DNA Extractor names
				$array2=$_GET['dExtrName'];
				$check2 = check_collector_names($array2,'false');
				if($check2['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_dExtrName = $check2['cat_name'];
				}
				
				//check and process RNA Extractor names
				$array3=$_GET['rExtrName'];
				$check3 = check_collector_names($array3,'false');
				if($check3['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
						$p_rExtrName = $check3['cat_name'];
				}
				
				//check if original sample still exists
				if(isset($_GET['orig_sample_exist'])){
					$p_orig_sample_exist = htmlspecialchars($_GET['orig_sample_exist']);
				}
				else{
					$p_orig_sample_exist = 'true';
				}
				
				//check if DNA sample still exists
				if(isset($_GET['DNA_sample_exist'])){
					$p_DNA_sample_exist = htmlspecialchars($_GET['DNA_sample_exist']);
				}
				else{
					$p_DNA_sample_exist = 'two';
				}
				
				//check if RNA sample still exists
				if(isset($_GET['RNA_sample_exist'])){
					$p_RNA_sample_exist = htmlspecialchars($_GET['RNA_sample_exist']);
				}
				else{
					$p_RNA_sample_exist = 'two';
				}
				
				//grab abbreviated project name to create new ID for sequencing submission
				$seq_id = '';
				$stmt_sid= $dbc->prepare("SELECT seq_id_start FROM project_name WHERE project_name = ?");
				$stmt_sid -> bind_param('s', $p_projName);
					
	  			if ($stmt_sid->execute()){
	    			$stmt_sid->bind_result($name);
	    			if ($stmt_sid->fetch()){
	        			$seq_id = $name.$p_sample_number;
					}
					else {
						$error_check = 'true';
	    				die('fetch() failed: ' . htmlspecialchars($stmt_sid->error));
					}
				} 
				else {
					$error_check = 'true';
	    			die('execute() failed: ' . htmlspecialchars($stmt_sid->error));
				}
				$stmt_sid -> close();
				
				//insert info into db
				if ($error_check == 'false') {
					
					try{
						
						/***************************************************************************************
						//Define variables based on what is entered. If nothing is entered for optional entries, enter NULL
						****************************************************************************************/
						//start transaction
						$dbc->autocommit(FALSE);
						
						//format sample name and sample sort name
						$date = htmlspecialchars($start_dates[1]); //just using start date for sampling date of the first sampler
						$regrex_check = '/^(20[0-9][0-9])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
						preg_match($regrex_check,$date,$matches);
						$date = $matches[1].'/'.$matches[2].'/'.$matches[3];
						$p_sample_name = $date.$p_projName.$p_sType.$p_sample_number;
						$sample_sort = $p_projName.$p_sample_number;
						
						//get current time stamp. This will be original timestamp
						$p_orig_time_stamp = date_default_timezone_set("Asia/Singapore");
						$p_orig_time_stamp = date("Y-m-d H:i:s");
					
						//get username and update entered by with
						$p_entered_by = $_GET['enteredBy']; 
						
						//set to null any non-required fields that are not populated
						if ($p_poolEx == '0') {$p_poolEx = NULL;}
						if ($p_dExtKit == '0') {$p_dExtKit = NULL;} 
						if ($p_rExtKit == '0') {$p_rExtKit = NULL;}
						if ($p_seqInfo == '0') {$p_seqInfo = NULL;}
						if ($p_anPipe == '0') {$p_anPipe = NULL;}
						if ($p_barcode == '') {$p_barcode = NULL;}
						if ($p_path == '') {$p_path = NULL;}
						if ($p_dConc == '') {$p_dConc = NULL;} 
		   				if ($p_dInstru == '0') {$p_dInstru = NULL;} 
		   				if ($p_dVol == '') {$p_dVol = NULL;} 
						if ($p_dVol_quant == '') {$p_dVol_quant = NULL;} 
						if ($p_d_extr_date == '') {$p_d_extr_date = NULL;} 
						if ($p_rConc == '') {$p_rConc = NULL;} 				
						if ($p_rInstru == '0') {$p_rInstru = NULL;} 
						if ($p_rVol == '') {$p_rVol = NULL;} 
						if ($p_rVol_quant == '') {$p_rVol_quant = NULL;}
						if ($p_r_extr_date == '') {$p_r_extr_date = NULL;} 
						if ($p_dData == '0') {$p_dData = NULL;}
						if ($p_dWeather == '0') {$p_dWeather = NULL;}

						
						$insert_check = 'true';
						/***************************************************************************************
						//Insert Into Main Sample Table. If successful, enter storage info and populate other table
						****************************************************************************************/
						
							//insert data into db. Use prepared statement 
						$stmt2 = $dbc -> prepare("INSERT INTO sample (sample_name,
																	  location_name,
																	  relt_loc_name, 
																	  part_sens_name, 
																	  collector_name, 
																	  dna_extract_kit_name,
																	  rna_extract_kit_name,
																	  sequencing_info,
																	  analysis_name,
																	  barcode,
																	  sample_type,
																	  particle_ct_csv_file,
																	  project_name,
																	  d_conc,
																	  d_conc_instrument,
																	  d_volume,
																	  d_volume_quant,
																	  d_extraction_date,
																	  r_conc,
																	  r_conc_instrument,
																	  r_volume,
																	  r_volume_quant,
																	  r_extraction_date,
																	  notes,
																	  flow_rate,
																	  flow_rate_eod,
																	  daily_data,
																	  sample_num,
																	  entered_by,
																	  sample_sort,
																	  orig_time_stamp,
																	  media_type,
																	  sampling_height,
																	  dExtrName,
																	  rExtrName,
																	  seq_id
																	  ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
						
						if(!$stmt2){
							$insert_check = 'false';
							throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
						}
						
						else{
							 $stmt2 -> bind_param('sssssssssssssdsiisdsiissddsissssdsss', $p_sample_name, $p_loc,$p_rloc, $p_partSamp, $p_collName, $p_dExtKit, $p_rExtKit, $p_seqInfo, $p_anPipe, $p_barcode, $p_sType, $p_path, $p_projName, $p_dConc,$p_dInstru,$p_dVol,$p_dVol_quant,$p_d_extr_date,$p_rConc,$p_rInstru,$p_rVol,$p_rVol_quant,$p_r_extr_date,$p_notes,$p_fRate,$p_fRate_eod,$p_dData,$p_sample_number,$p_entered_by,$sample_sort,$p_orig_time_stamp,$p_media,$p_sampling_height,$p_dExtrName,$p_rExtrName,$seq_id);
							 if(!$stmt2 -> execute()){
							 	$insert_check = 'false';
							 	throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
							}	
							else{
								$rows_affected2 = $stmt2 ->affected_rows;
								$stmt2 -> close();
								if($rows_affected2 > 0){
									
									
									//insert sample_name into storage_info table  with 'original' storage info
									$p_oStore = $_GET['oStore_temp'].','.$_GET['oStore_name'];
									$p_dStore = $_GET['dStore_temp'].','.$_GET['dStore_name'];
									$p_rStore = $_GET['rStore_temp'].','.$_GET['rStore_name'];
									$stmt3 = $dbc -> prepare("INSERT INTO storage_info (sample_name,original,orig_sample_exists,dna_extr,rna_extr,DNA_sample_exists,RNA_sample_exists) VALUES (?,?,?,?,?,?,?)");
									$stmt3 -> bind_param('sssssss', $p_sample_name,$p_oStore,$p_orig_sample_exist, $p_dStore,$p_rStore,$p_DNA_sample_exist,$p_RNA_sample_exist);		
									$stmt3 -> execute();
									$rows_affected3 = $stmt3 ->affected_rows;		
									$stmt3 -> close();
												
									//check if add was successful or not. Tell the user
									if($rows_affected3 < 0){
										$insert_check = 'false';
										throw new Exception("Unable to insert sample in storage info");	
									}
								}
								else{
									$insert_check = 'false';
									throw new Exception("Unable to insert sample into db");
									echo 'An error has occurred';
									mysqli_error($dbc);
								}
							}
							
							
						}
						
						/***************************************************************************************
						//Insert Samplers !!! (must be after insert of sample to db because of key constraints)
						****************************************************************************************/
						$num_of_my_samplers = $_GET['sampler_num'];
						
						$earliest_start;
						$latest_end;
						$counter = 0;
					
						for ($x = 1; $x <= $num_of_my_samplers; $x++) {
							$counter++;
							$p_my_samp_name = htmlspecialchars($my_samplers[$x]);
							$start = $start_dates[$x].' '.$start_times[$x];
							$end = $end_dates[$x].' '.$end_times[$x];
							
							//check if you are a blank. If you are then make sampling time zero
							//if($p_sType == 'B' || $p_sType == 'BR' || $p_sType == 'BFR' || $p_sType == 'BMF'){
							if($sample_type_check == true){
								$end = $start;
							}
							
							if($counter == 1){
								$earliest_start = $start;
								$latest_end = $end;
							}
							else{
								//check starts
								if($start < $earliest_start){
									$earliest_start = $start;
								}
								
								//check ends
								if($end > $latest_end){
									$latest_end = $end;
								}
							}
							
							//format date/time
							$p_time;
							if(($start) && ($end)){
								$ts1 = strtotime($start);
								$ts2 = strtotime($end);
			
								$seconds_diff = $ts2 - $ts1;
								
								$time = ($seconds_diff/3600);
								$p_time = round($time,2);
							}
							$query_my_samp = "INSERT INTO sample_sampler (sample_name, sampler_name, start_date_time,end_date_time,total_date_time) VALUES (?,?,?,?,?)";
							$stmt_my_samp = $dbc -> prepare($query_my_samp);
							if(!$stmt_my_samp){
								throw new Exception("Prepare Failure: Unable To Insert Sample Sampler");	
							}
							else{
								$stmt_my_samp -> bind_param('ssssd', $p_sample_name,$p_my_samp_name,$start,$end,$p_time);
								if($stmt_my_samp -> execute()){
									$rows_affected_my_samp = $stmt_my_samp ->affected_rows;
									$stmt_my_samp -> close();
									//check if add was successful or not. Tell the user
							   		if($rows_affected_my_samp < 0){
										$insert_check = 'false';
										throw new Exception("An Error Occurred: No Sampler Info Added");
									}
								}
								else{
									$insert_check = 'false';
									throw new Exception("Execution Failure: Unable To Insert Sampler");	
								}
							}
						}
						//echo 'earliest and latest'.$earliest_start.' '.$latest_end.'<br>';
						//format largest sampling period for samplers run at the same time period
						//update sample table with this new time
						$p_biggest_time;
						if(($start) && ($end)){
								$bts1 = strtotime($earliest_start);
								$bts2 = strtotime($latest_end);
			
								$big_seconds_diff = $bts2 - $bts1;
								
								$big_time = ($big_seconds_diff/3600);
								$p_biggest_time = round($big_time,2);
						}
						
						$time_query = "UPDATE sample SET start_samp_date_time = ?, end_samp_date_time = ?, total_samp_time = ? WHERE sample_name = ?";
						if($time_stmt = $dbc ->prepare($time_query)) {                 
		                	$time_stmt->bind_param('ssds',$earliest_start,$latest_end,$p_biggest_time,$p_sample_name);
					
		                    if($time_stmt -> execute()){
								$time_rows_affected = $time_stmt ->affected_rows;
							
								$time_stmt -> close();
								if($time_rows_affected < 1){	
									$insert_check = 'false';
									throw new Exception("Insert Failure: Unable To Insert Sampler");
								}
							}
							else{
								$insert_check = 'false';
								throw new Exception("Execution Failure: Unable To Insert Sampler");
							}
						}
						else{
							$insert_check = 'false';
							throw new Exception("Prepare Failure: Unable To Insert Sampler");
						}
						
						
						/***************************************************************************************
						//add insert into new table for number_of_seq_submissions. No update/edit for this exists 
						****************************************************************************************/
						$stmt_seq_num = $dbc -> prepare("INSERT INTO number_of_seq_submissions (sample_name) VALUES (?)");
						if(!$stmt_seq_num){
								$insert_check = 'false';
								throw new Exception("Prepare Failure: Unable to insert sample into Sequence Number Submission table");	
						}
						else{
							$stmt_seq_num -> bind_param('s', $p_sample_name);
							if(!$stmt_seq_num-> execute()){
								$insert_check = 'false';
								throw new Exception("Execution Failure: Unable to enter sample into Sequence Number Submission table.");	
							}
							else{
								$rows_affected_seq_num = $stmt_seq_num ->affected_rows;
								$stmt_seq_num -> close();
								if($rows_affected_seq_num < 0){
									$insert_check = 'false';
									throw new Exception("Unable to insert sample into Sequence Number Submission table");	
								}
							}
						}
						
						/***************************************************************************************
						//Insert Admin Created Things
						****************************************************************************************/
						$thing1 = NULL;					
						if(isset($p_user_things[1])){
							$thing1 = $p_user_things[1];
						}
						$thing2 = NULL;					
						if(isset($p_user_things[2])){
							$thing2 = $p_user_things[2];
						}
						$thing3 = NULL;					
						if(isset($p_user_things[3])){
							$thing3 = $p_user_things[3];
						}
						$thing4 = NULL;					
						if(isset($p_user_things[4])){
							$thing4 = $p_user_things[4];
						}
						$thing5 = NULL;					
						if(isset($p_user_things[5])){
							$thing5 = $p_user_things[5];
						}
						$thing6 = NULL;					
						if(isset($p_user_things[6])){
							$thing6 = $p_user_things[6];
						}
						$thing7 = NULL;					
						if(isset($p_user_things[7])){
							$thing7 = $p_user_things[7];
						}
						$thing8 = NULL;					
						if(isset($p_user_things[8])){
							$thing8 = $p_user_things[8];
						}
						$thing9 = NULL;					
						if(isset($p_user_things[9])){
							$thing9 = $p_user_things[9];
						}
						$thing10 = NULL;					
						if(isset($p_user_things[10])){
							$thing10 = $p_user_things[10];
						}
						$thing11 = NULL;					
						if(isset($p_user_things[11])){
							$thing11 = $p_user_things[11];
						}
						$thing12 = NULL;					
						if(isset($p_user_things[12])){
							$thing12 = $p_user_things[12];
						}
						$thing13 = NULL;					
						if(isset($p_user_things[13])){
							$thing13 = $p_user_things[13];
						}
						$thing14 = NULL;					
						if(isset($p_user_things[14])){
							$thing14 = $p_user_things[14];
						}
						$thing15 = NULL;					
						if(isset($p_user_things[15])){
							$thing15 = $p_user_things[15];
						}
						$thing16 = NULL;					
						if(isset($p_user_things[16])){
							$thing16 = $p_user_things[16];
						}
						$thing17 = NULL;					
						if(isset($p_user_things[17])){
							$thing17 = $p_user_things[17];
						}
						$thing18 = NULL;					
						if(isset($p_user_things[18])){
							$thing18 = $p_user_things[18];
						}
						$thing19 = NULL;					
						if(isset($p_user_things[19])){
							$thing19 = $p_user_things[19];
						}
						$thing20 = NULL;					
						if(isset($p_user_things[20])){
							$thing20 = $p_user_things[20];
						}
						//}
						
						$stmt_things = $dbc -> prepare("INSERT INTO store_user_things (sample_name, thing1,thing2,thing3,thing4,thing5,thing6,thing7,thing8,thing9,thing10,thing11,thing12,thing13,thing14,thing15,thing16,thing17,thing18,thing19,thing20) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
						if(!$stmt_things){
								$insert_check = 'false';
								throw new Exception("Prepare Failure: Unable to insert user created fields");	
						}
						else{
							$stmt_things -> bind_param('sssssssssssiiiiiiiiii', $p_sample_name,$thing1,$thing2,$thing3,$thing4,$thing5,$thing6,$thing7,$thing8,$thing9,$thing10,$thing11,$thing12,$thing13,$thing14,$thing15,$thing16,$thing17,$thing18,$thing19,$thing20);
							if(!$stmt_things-> execute()){
								$insert_check = 'false';
								throw new Exception("Execution Failure: Unable to enter user created fields.");	
							}
							else{
								$rows_affected_things = $stmt_things ->affected_rows;
								$stmt_things -> close();
								if($rows_affected_things< 0){
									$insert_check = 'false';
									throw new Exception("Unable to insert sample into Sequence Number Submission table");	
								}
							}
						}
						
						/*****************************************************************************
						 * Do One Last Check And Commit If You Had No Errors
						 * ***************************************************************************/
						
						if($insert_check == 'true'){
							$dbc->commit();
							//echo 'Data Submitted Successfully! You added a new Sample '.$p_sample_name.'<br>';
							echo 'Data Submitted Successfully! You added a new Sample '.$p_sample_name;
							$submitted = 'true';
							
						}
						else{
							throw new Exception("Final Error: Unable To Insert Info To DB. No Changes Made");		
						}
					}
					catch (Exception $e) { 
						if (isset ($dbc)){
       	 					$dbc->rollback ();
       						//echo "Error:  " . $e;
       						header('HTTP/1.0 400 Bad error'); 
    					}	
					}
			}
?>