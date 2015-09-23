<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('database_connection.php');
include ('index.php');
include('/config/check_form_insert_js.php');
include('/config/check_sample_name.php');
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Form Insert</title>
		<!--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>-->
	
	</head>
 
	<body>
	<div class="page-header">
	<h3>Sample Insert Form</h3>
	<?php
		//sample not yet submitted
		$submitted = 'false';
		//error checking
		if (isset($_GET['submit']) || isset($_GET['copy'])) {
				//print_r($_GET);
				$error_check = 'false';
				$get_array = $_GET;
				
				//check that all fields are entered properly
				include('functions/field_check.php');
				$error_check = field_check($get_array,'insert_sample');
				
				if($error_check == 'true'){
					echo '<script>Alert.render("ERROR: Sample Not Entered. Please check error messages");</script>';
				}
 
 				//format date and time
				//$start = $_GET['sdate'].' '.$_GET['stime'];
				//$end = $_GET['edate'].' '.$_GET['etime'];
				
				
				//sanatize user input to make safe for browser
				$p_sample_number = htmlspecialchars($_GET['sample_number']);
				$p_projName = htmlspecialchars($_GET['projName']);
				$p_loc = htmlspecialchars($_GET['loc']);
				$p_rloc = htmlspecialchars($_GET['rloc']);
				//$p_airSamp = htmlspecialchars($_GET['airSamp']);
				$p_partSamp = NULL;
				$p_poolEx = '0';//pooling of extracts has been moved to another page
				$p_dExtKit = htmlspecialchars($_GET['dExtKit']);
				$p_rExtKit = htmlspecialchars($_GET['rExtKit']);
				$p_seqInfo = htmlspecialchars($_GET['seqInfo']);
				$p_anPipe = htmlspecialchars($_GET['anPipe']);
				$p_barcode = htmlspecialchars($_GET['barcode']);
				//$p_start = htmlspecialchars($start);
				//$p_end = htmlspecialchars($end);
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
				
				//for isolates
				$p_iso_coll_temp = htmlspecialchars($_GET['iso_coll_temp']);
				$p_iso_date = htmlspecialchars($_GET['iso_date']);
				$p_iso_store_method = htmlspecialchars($_GET['iso_store_method']);
				$p_sang_seq= htmlspecialchars($_GET['sang_seq']);
				$p_closest_hit= htmlspecialchars($_GET['closest_hit']);
				$p_send_pac_bio= htmlspecialchars($_GET['send_pac_bio']);
				$p_iso_loc_type= htmlspecialchars($_GET['iso_loc_type']);
				
				if($p_sType != 'A'){
						//set flow rate to 0 and set sampling time to zero
						$p_fRate = '0';
						$p_fRate_eod = '0';
						//$p_end = $p_start;	
				}
				
		
				//check and process collector info
				include_once("functions/check_collector_names.php");
				$array=$_GET['collector'];
				$check = check_collector_names($array,'true');
				//$p_collName = $check['cat_name'];
				if($check['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_collName = $check['cat_name'];
				}
				
				//check and process DNA Extractor names
				$array2=$_GET['dExtrName'];
				$check2 = check_collector_names($array2,'false');
				//$p_dExtrName = $check2['cat_name'];
				if($check2['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_dExtrName = $check2['cat_name'];
				}
				
				//check and process RNA Extractor names
				$array3=$_GET['rExtrName'];
				$check3 = check_collector_names($array3,'false');
				//$p_rExtrName = $check3['cat_name'];
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
	    				die('execute() failed: ' . htmlspecialchars($stmt->error));
					}
				} 
				else {
					$error_check = 'true';
	    			die('execute() failed: ' . htmlspecialchars($stmt->error));
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
						$date = htmlspecialchars($_GET['sdate1']); //just using start date for sampling date of the first air sampler
						$regrex_check = '/^(201[4-5])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
						preg_match($regrex_check,$date,$matches);
						$date = $matches[1].'/'.$matches[2].'/'.$matches[3];
						$p_sample_name = $date.$p_projName.$p_sType.$p_sample_number;
						$sample_sort = $p_projName.$p_sample_number;
						
						//get current time stamp. This will be original timestamp
						$p_orig_time_stamp = date_default_timezone_set("Asia/Singapore");
						$p_orig_time_stamp = date("Y-m-d H:i:s");
					
						//get username and update entered by with
						$p_entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
						
						//set to null any non-required fields that are not populated
						if ($p_poolEx == '0') {$p_poolEx = NULL;}
						if ($p_dExtKit == '0') {$p_dExtKit = NULL;} 
						if ($p_rExtKit == '0') {$p_rExtKit = NULL;}
						if ($p_seqInfo == '0') {$p_seqInfo = NULL;}
						if ($p_anPipe == '0') {$p_anPipe = NULL;}
						if ($p_barcode == '') {$p_barcode = NULL;}
						//if ($p_start == '') {$p_start = NULL;}
						//if ($p_end == '') {$p_end = NULL;}
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
						if ($p_fRate_eod == '') {$p_fRate_eod = NULL;}
						if ($p_dData == '0') {$p_dData = NULL;}
						if ($p_dWeather == '0') {$p_dWeather = NULL;}
						if ($p_iso_coll_temp == '0') {$p_iso_coll_temp = NULL;}
						if ($p_iso_date == '') {$p_iso_date = NULL;}
						if ($p_iso_store_method == '0') {$p_iso_store_method = NULL;}
						if ($p_sang_seq== '') {$p_sang_seq = NULL;}
						if ($p_closest_hit == '') {$p_closest_hit = NULL;}
						if ($p_send_pac_bio == '0') {$p_send_pac_bio = NULL;}
						if ($p_iso_loc_type == '0') {$p_iso_loc_type = NULL;}
						
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
																	  pool_extracts_id,
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
																	  daily_weather,
																	  sample_num,
																	  entered_by,
																	  sample_sort,
																	  orig_time_stamp,
																	  media_type,
																	  sampling_height,
																	  dExtrName,
																	  rExtrName,
																	  seq_id
																	  ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
						
						if(!$stmt2){
							$insert_check = 'false';
							throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
							//echo "Prepare failed: (" . $dbc->errno . ") " . $dbc->error;
						}
						
						else{//ssssssssssssssdsssdsiisdsiissddssissssdsss
							//echo $p_sample_name.'<br>'.$p_loc.'<br>'.$p_rloc.'<br>'.$p_partSamp.'<br>'.$p_collName.'<br>'.$p_poolEx.'<br>'.$p_dExtKit.'<br>'.$p_rExtKit.'<br>'.$p_seqInfo.'<br>'.$p_anPipe.'<br>'.$p_barcode.'<br>'.$p_sType.'<br>'.$p_path.'<br>'.$p_projName.'<br>'.$p_dConc.'<br>'.$p_dInstru.'<br>'.$p_dVol.'<br>'.$p_dVol_quant.'<br>'.$p_d_extr_date.'<br>'.$p_rConc.'<br>'.$p_rInstru.'<br>'.$p_rVol.'<br>'.$p_rVol_quant.'<br>'.$p_r_extr_date.'<br>'.$p_notes.'<br>'.$p_fRate.'<br>'.$p_fRate_eod.'<br>'.$p_dData.'<br>'.$p_dWeather.'<br>'.$p_sample_number.'<br>'.$p_entered_by.'<br>'.$sample_sort.'<br>'.$p_orig_time_stamp.'<br>'.$p_media.'<br>'.$p_sampling_height.'<br>'.$p_dExtrName.'<br>'.$p_rExtrName.'<br>'.$seq_id.'<br>';
							 $stmt2 -> bind_param('ssssssssssssssdsiisdsiissddssissssdsss', $p_sample_name, $p_loc,$p_rloc, $p_partSamp, $p_collName, $p_poolEx, $p_dExtKit, $p_rExtKit, $p_seqInfo, $p_anPipe, $p_barcode, $p_sType, $p_path, $p_projName, $p_dConc,$p_dInstru,$p_dVol,$p_dVol_quant,$p_d_extr_date,$p_rConc,$p_rInstru,$p_rVol,$p_rVol_quant,$p_r_extr_date,$p_notes,$p_fRate,$p_fRate_eod,$p_dData,$p_dWeather,$p_sample_number,$p_entered_by,$sample_sort,$p_orig_time_stamp,$p_media,$p_sampling_height,$p_dExtrName,$p_rExtrName,$seq_id);
							 if(!$stmt2 -> execute()){
							 	$insert_check = 'false';
							 	throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
								//echo "execute failed!: (" . $dbc->errno . ") " . $dbc->error;
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
									if($rows_affected3 > 0){
										//$dbc->commit();
										//echo 'You added a new Sample '.$p_sample_name.' with storage info<br>';
										//$submitted = 'true';														
									}
									else{
										$insert_check = 'false';
										throw new Exception("Unable to insert sample in storage info");	
									}
								}
								else{
									$insert_check = 'false';
									throw new Exception("Unable to insert sample into db");
									echo 'An error has occured';
									mysqli_error($dbc);
								}
							}
							
							
						}
						
						/***************************************************************************************
						//Insert Air Samplers !!!move this, you must insert in after you put into sample db...same with isolate db?
						****************************************************************************************/
						$num_of_air_samplers = $_GET['air_samp_num'];
						for ($x = 1; $x <= $num_of_air_samplers; $x++) {
							$p_air_samp_name = htmlspecialchars($_GET['airSamp'.$x]);
							
							$start = $_GET['sdate'.$x].' '.$_GET['stime'.$x];
							$end = $_GET['edate'.$x].' '.$_GET['etime'.$x];

							//format date/time
							$p_time;
							if(($start) && ($end)){
								$ts1 = strtotime($start);
								$ts2 = strtotime($end);
			
								$seconds_diff = $ts2 - $ts1;
								
								$time = ($seconds_diff/3600);
								$p_time = round($time,2);
							}
							$query_air_samp = "INSERT INTO sample_air_sampler (sample_name, air_sampler_name, start_date_time,end_date_time,total_date_time) VALUES (?,?,?,?,?)";
							$stmt_air_samp = $dbc -> prepare($query_air_samp);
							if(!$stmt_air_samp){
								throw new Exception("Prepare Failure: Unable To Insert Sample Air Sampler");	
							}
							else{
								$stmt_air_samp -> bind_param('ssssd', $p_sample_name,$p_air_samp_name,$start,$end,$p_time);
								if($stmt_air_samp -> execute()){
									$rows_affected_air_samp = $stmt_air_samp ->affected_rows;
									$stmt_air_samp -> close();
									//check if add was successful or not. Tell the user
							   		if($rows_affected_air_samp > 0){
							   			//echo 'You Added New Air Sampler Info :'.$p_air_samp_name.'<br>';
									}else{
										$insert_check = 'false';
										throw new Exception("An Error Occured: No Air Sampler Info Added");
									}
								}
								else{
									$insert_check = 'false';
									throw new Exception("Execution Failure: Unable To Insert Air Sampler");	
								}
							}
						}
						/***************************************************************************************
						//Insert Sample Info Into Isolates Table
						****************************************************************************************/
						$stmt_iso = $dbc -> prepare("INSERT INTO isolates (sample_name,iso_coll_temp,iso_date,iso_store_method,seq_sang,closest_hit,send_pac_bio,loc_type) VALUES (?,?,?,?,?,?,?,?)");
						if(!$stmt_iso){
							throw new Exception("Prepare Failure: Unable To Insert Sample Isolate Info ");	
						}
						else{
							$stmt_iso -> bind_param('ssssssss', $p_sample_name, $p_iso_coll_temp,$p_iso_date,$p_iso_store_method,$p_sang_seq,$p_closest_hit,$p_send_pac_bio,$p_iso_loc_type);
							
							if(!$stmt_iso-> execute()){
								$insert_check = 'false';
								throw new Exception("Executino Failure: Unable To Insert Sample Isolate Info");	
								echo "execute failed!: (" . $dbc->errno . ") " . $dbc->error;
							}
							
							else{
								$rows_affected_iso = $stmt_iso ->affected_rows;
								$stmt_iso -> close();
								if($rows_affected_iso > 0){
									//echo 'You added a new Sample '.$p_sample_name.' to isolate db<br>';								
								}
								else{
									$insert_check = 'false';
									throw new Exception("Unable to insert sample into isolate db");	
								}
							}
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
						/*****************************************************************************
						 * Do One Last Check And Commit If You Had No Errors
						 * ***************************************************************************/
						
						if($insert_check == 'true'){
							$dbc->commit();
							echo 'You added a new Sample '.$p_sample_name.'<br>';
							$submitted = 'true';
							
						}
						else{
							throw new Exception("Final Error: Unable To Insert Info To DB. No Changes Made");		
						}
					}
					catch (Exception $e) { 
						if (isset ($dbc)){
       	 					$dbc->rollback ();
       						echo "Error:  " . $e; 
    					}	
					}
				}
			}
	?>
</div>		
<!-----------------------------------------------FORM-------------------------------------------------------
------------------------------------------------------------------------------------------------------------->
		<!--<form class="registration" id="sample_form" action="form_insert.php" method="GET">-->
		<form  class="registration" onsubmit="return validate(this)" action="form_insert.php" method="GET">
	
			* = required field   + = required for air samples<br>
			
			<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>

			<!--table insert form-->
			<?php include ("functions/dropDown.php"); ?>

				<div id='samplename_availability_result'></div>  
				<fieldset>
				<LEGEND><b>Step One: Sample Collection Info</b></LEGEND>
				<!--sample-->
				<div class="container-fluid">
  				<div class="row">
  				<div class="col-xs-6">
				<p>
				<label class="textbox-label">Sample Number:*</label>
			
				<input type="text" name="sample_number" id = "sample_number" placeholder="[001]" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_sample_number;} ?>">
				</p>
				
				<!--Barcode insert field-->
				<p>
				<label class="textbox-label">Barcode:(optional)</label>
				<br>
				<input type="text" name="barcode" id="barcode" placeholder="Enter A Barcode" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_barcode;}?>"
				</p>

				<p>
				<!--Project Name Dropdown-->
				<label class="textbox-label">Select Project Name:*</label>
				<?php
				//url or $_GET name, table name, field name
				dropDown('projName', 'project_name', 'project_name','project_name',$submitted);
				?>
				</p>
				
				<!--location dropdown-->
				<p>
				<label class="textbox-label">Select Location:*</label>
				<?php

				//url or $_GET name, table name, field name
				dropDown('loc', 'location', 'loc_name','loc_name',$submitted);
				?>
				</p>
				
				<!--rel location dropdown-->
				<p>
				<label class="textbox-label">Select Relative Location:*</label>
				<?php
				//url or $_GET name, table name, field name
				dropDown('rloc', 'relt_location', 'loc_name','loc_name',$submitted);
				?>
				</p>
				
				<p>
				<!--media type dropdown-->
				<label class="textbox-label">Media Type:*</label>
				<?php
				//url or $_GET name, table name, field name
				dropDown('media', 'media_type', 'media_type','media_type',$submitted);
				?>
				</p>

				<p>
				<!--Collector Name input-->
				<label class="textbox-label">Enter Collector Name(s):*</label>
				<p class="clone"> <input type="text" name="collector[]" id="collector" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_collName;} ?>"/></p>
				</p>
				</div>
				
				  <div class="col-xs-6">
				<!--Sampling Type insert field-->
				<p>
				<label class="textbox-label">Sample Type:*</label>
				<?php
				//url or $_GET name, table name, field name, value
				dropDown('sType', 'sample_type', 'sample_type_name','sample_type_id',$submitted);
				?>
				</p>
				
				<!--Flow Rate-->
				<p>
				<label class="textbox-label">Flow Rate-Start/End of Day:+</label><br>
				<input type="text" class = "shrtfields" name="fRate" id="fRate"  placeholder="Rate(L/min)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_fRate;} ?>">
				<input type="text" class = "shrtfields" name="fRate_eod" id="fRate_eod"  placeholder="Rate(L/min)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_fRate_eod;} ?>">
				</p>

				<!--Sample Storage-->
				<label class="textbox-label">Storage Location:* (pick freezer and drawer owner)</label><br>
				<p>
				<?php
				//url or $_GET name, table name, field name
				dropDown('oStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted);
				?>
				
				<select id="oStore_name" name ="oStore_name" >
 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
 			
 						echo '<option value='.$_GET["oStore_name"].'  echo "selected";}} ?>'.$_GET["oStore_name"].' </option>';
 					}else{
 						echo '<option value="0">-Select-</option>';
 					}?>
 				</select>	
				
				<p>
				<label class="textbox-label">Height Above Floor:+</label><br>
				<input type="text" name="sampling_height" id="sampling_height"  placeholder="Enter A Height Above Floor (cm)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_sampling_height;} ?>">
				</p>
				
				<p>
				<!--air sampler dropdown-->
				<label class="textbox-label">Select Number of Air Samplers:*</label>
				<?php
				//url or $_GET name, table name, field name
				//dropDown('airSamp', 'air_sampler', 'air_sampler_name','air_sampler_name',$submitted);
				?>
				<select id='air_samp_num' name='air_samp_num'>
				<option value='0'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "0" )){ echo "selected";}}?>>-Select-</option>
				<option value='1'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "1" )){ echo "selected";}}?>>-1:One-</option>
				<option value='2'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "2" )){ echo "selected";}}?>>-2:Two-</option>
				<option value='3'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "3" )){ echo "selected";}}?>>-3:Three-</option>
				<option value='4'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "4" )){ echo "selected";}}?>>-4:Four-</option>
				<option value='5'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "5" )){ echo "selected";}}?>>-5:Five-</option>
				<option value='6'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
					if((isset($_GET['air_samp_num']) && $_GET['air_samp_num'] == "6" )){ echo "selected";}}?>>-6:Six-</option>
				</select>
				<div id="div_air_samp_num"></div>
				</p>
				</div>
				
				</fieldset>
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->
				
				<p>
				<button type="button"  data-toggle="collapse" data-target="#isolates" aria-expanded="true" aria-controls="demo" class='buttonLength'>For Isolate Collection Info</button>
				<div id="isolates" class="collapse">
				</p>
				<div class="col-xs-6">
					<fieldset>
					<LEGEND><b>Isolate Collection Info</b></LEGEND>
					<p>
					<!--<h3 class = 'form-header'>(For Isolate Collection Only)</h3>-->
					<label class="textbox-label">Select Isolate Collection Temperature:*</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown('iso_coll_temp', 'isolate_collection_temp', 'temp','temp',$submitted);
					?>
					</p>
					
					<p>
					<label class="textbox-label">Isolate Storage Date:*</label><br>
					<input type="text" id="datepicker3"  name="iso_date"  value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['iso_date']);} ?>"/>
					</p>
					<script>
					$('#datepicker3').datepicker({ dateFormat: 'yy-mm-dd' }).val();
					</script>
					
					<p>
					<label class="textbox-label">Isolate Storing Method:*</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown('iso_store_method', 'isolate_storing_method', 'storing_method','storing_method',$submitted);
					?>
					</p>
					
					<p>
					<label class="textbox-label">Isolate Location Type:*</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown('iso_loc_type', 'isolate_location_type', 'loc_type','loc_type',$submitted);
					?>
					</p>
					
					<p>
					<label class="textbox-label">16S Sequence (Sanger)</label><br>
					<input type="text" id="sang_seq" name="sang_seq"  placeholder="Enter A Sequence" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_sang_seq;} ?>">
					</p>
					
					<p>
					<label class="textbox-label">Closest Hit</label><br>
					<input type="text" id="closest_hit" name="closest_hit"  placeholder="Enter A Closest Hit" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_closest_hit;} ?>">
					</p>
					
					<p>
					<label class="textbox-label">Send For PacBio Sequencing:</label>
					<br/>
					<select id='send_pac_bio' name='send_pac_bio';'>
					<option value='0'<?php if((isset($_GET["send_pac_bio"]) && $_GET["send_pac_bio"] == "0" )){ echo "selected";} ?>>-Select-</option>
					<option value='Y'<?php if((isset($_GET["send_pac_bio"]) && $_GET["send_pac_bio"] == "Y" )){ echo "selected";} ?>>Yes</option>
					<option value='N'<?php if((isset($_GET["send_pac_bio"]) && $_GET["send_pac_bio"] == "N" )){ echo "selected";} ?>>No</option>
					</select>
					</p>
					
	
					</fieldset>
					</div>
				</div> 
				<!--</div>--> <!--end of col2-->
				<!--</div>--> <!--end of wrap-->
				<p>
				<button type="button"  data-toggle="collapse" data-target="#demo" aria-expanded="true" aria-controls="demo" class='buttonLength'>Add More Info to Sample</button>
				<div id="demo" class="collapse">
				</p>
				
				<div class="col-xs-6">
					<div id="dna_extraction">
						<fieldset>
						<LEGEND><b>Step Two: DNA Extraction Info</b></LEGEND>
							
						<p>
						<label class="textbox-label">DNA Extraction Date:</label><br>
						<input type="text" id="d_extr_date"  name="d_extr_date" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['d_extr_date']);} ?>"/>
						<script>
						$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
						</script>
		
						<p>
						<!--DNA Extraction Kit dropdown-->
						<label class="textbox-label">Select DNA Extraction Kit:</label>
						<br/>
						<?php
						//url or $_GET name, table name, field name
						dropDown('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name',$submitted);
						?>
						</p>
						
						<!--DNA Concentration-->
						<p>
						<label class="textbox-label">DNA Concentration (ng/ul):</label><br>
						<input type="text" name="dConc" id="dConc" placeholder="Enter A DNA Concentration" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dConc;} ?>">
						</p>
		
						<!--Volume of DNA-->
						<p>
						<label class="textbox-label">Volume of DNA Elution (ul):</label><br>
						<input type="text" name="dVol"  id="dVol" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dVol;} ?>">
						</p>
						
						<!--Instrument used to measure DNA concentration-->
						<p>
						<label class="textbox-label">Instrument/Kit Used to Measure DNA Concentration:</label><br>
						<?php
						//url or $_GET name, table name, field name
						dropDown('dInstru', 'quant_instruments', 'kit_name','kit_name',$submitted);
						?>
						</p>
						
						<!--Volume of DNA to measure DNA conc-->
						<p>
						<label class="textbox-label">Volume of DNA Used for Measure DNA Concentration(ul):</label><br>
						<input type="text" name="dVol_quant" id="dVol_quant"  placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dVol_quant;} ?>">
						</p>
						
						<!--DNA -->
						<p>
						<label class="textbox-label">Location of DNA Extract:(pick freezer and drawer owner)</label><br>
						<p>
						<?php
						//url or $_GET name, table name, field name
						dropDown('dStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted);
						?>
						</p>
						<select id="dStore_name" name ="dStore_name">
		 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
		 						echo '<option value='.$_GET["dStore_name"].'  echo "selected";}} ?>'.$_GET["dStore_name"].' </option>';
		 					}else{
		 						echo '<option value="0">-Select-</option>';
		 					}?>
		 				</select>	
		 				
		 				<p>
						<!--DNA Extractor Name input-->
						<label class="textbox-label">Enter Name(s) of Persons Who Extracted DNA:</label>
						<p class="clone2"> <input type="text" name="dExtrName[]" id = "dExtrName" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_dExtrName;} ?>"/></p>

		 				<p>
						<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3>
		 				<div class="vert-checkboxes">
		 				<label class="checkbox-label"><input type="checkbox" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="false" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_orig_sample_exist == 'false'){echo 'checked';}} ?>/>No
						</div>
						</p>
						
						<p>
						<h3 class="checkbox-header">Does DNA Extraction Sample Still Exist?</h3>
		 				<div class="vert-checkboxes">
		 				<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="one" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'one'){echo 'checked';}} ?>/>Yes,DNA Sample Exisits</label><br />
						<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="two" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'two'){echo 'checked';}} ?>/>No, DNA Has Not Been Extracted</label><br />
						<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="three" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'three'){echo 'checked';}} ?>/>No, DNA Sample Is Used Up</label><br />
						</div>
						</p>
						
						</fieldset>
					</div>
				</div>
				
				<div class="col-xs-6">
					<div id="rna_extraction">
						<fieldset>
						<LEGEND><b>Step Three: RNA Extraction Info</b></LEGEND>
						
						<p>
						<label class="textbox-label">RNA Extraction Date:</label><br>
						<input type="text" id="r_extr_date"  name="r_extr_date" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['r_extr_date']);} ?>"/>
						<script>
						$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
						</script>
						
						<p>
						<!--RNA Extraction dropdown-->
						<label class="textbox-label">Select RNA Extraction Kit:</label>
						<br/>
						<?php
						//url or $_GET name, table name, field name
						dropDown('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name',$submitted);
						?>
						</p>
						
						<!--RNA Concentration-->		
						<p>
						<label class="textbox-label">RNA Concentration (ng/ul):</label><br>
						<input type="text" name="rConc" id="rConc" placeholder="Enter an RNA Concentration" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rConc;} ?>">
						</p>
						
						<!--RNA Volume-->
						<p>
						<label class="textbox-label">Volume of RNA Elution (ul):</label><br>
						<input type="text" name="rVol" id="rVol" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rVol;} ?>">
						</p>
				
						<!--Instrument used to measure RNA concentration-->
						<p>
						<label class="textbox-label">Instrument/Kit Used to Measure RNA Concentration:</label><br>
						<?php
						//url or $_GET name, table name, field name
						dropDown('rInstru', 'quant_instruments', 'kit_name','kit_name',$submitted);
						?>
						</p>
						
						<!--RNA Volume-->
						<p>
						<label class="textbox-label">Volume of RNA for Quantification(ul):</label><br>
						<input type="text" name="rVol_quant" id="rVol_quant" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rVol_quant;} ?>">
						</p>
						
						
						<p>
						<label class="textbox-label">Location of RNA Extract:(pick freezer and drawer owner)</label><br>
						<p>
						<?php
						//url or $_GET name, table name, field name
						dropDown('rStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted);
						?>
						</p>
				
						<select id="rStore_name" name ="rStore_name">
		 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
		 						echo '<option value='.$_GET["rStore_name"].'  echo "selected";}} ?>'.$_GET["rStore_name"].' </option>';
		 					}else{
		 						echo '<option value="0">-Select-</option>';
		 					}?>
		 				</select>
		 				
		 				<p>
						<!--RNA Extractor Name input-->
						<label class="textbox-label">Enter Name(s) of Persons Who Extracted RNA:</label>
						<p class="clone3"> <input type="text" name="rExtrName[]" id="rExtrName" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_rExtrName;} ?>"/></p>
					
						
						<p>
						<h3 class="checkbox-header">Does Original RNA Sample Still Exist?:</h3><br>
						<div class="vert-checkboxes">
		 				<label class="checkbox-label"><input type="checkbox" class="orig_sample_exist" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_orig_sample_exist == 'false'){echo 'checked';}} ?>/>No<br />
						</div>
						</p>
						
						<p>
						<h3 class="checkbox-header">Does RNA Extraction Sample Exist?:</h3><br>
		 				<div class="vert-checkboxes">
		 				<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="one" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'one'){echo 'checked';}} ?>/>Yes,RNA Sample Exisits<br />
						<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="two" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'two'){echo 'checked';}} ?>/>No, RNA Has Not Been Extracted<br />
						<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="three" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'three'){echo 'checked';}} ?>/>No, RNA Sample Is Used Up<br />
						</div>
						</p>
						
						</fieldset>
					</div>
				</div>
				
				<div class="col-xs-6">
					<fieldset>
					<LEGEND><b>Step Four: Sequencing Info</b></LEGEND>
					<p><a href="/series/dynamic/airmicrobiomes/update_tables/update_seq_info.php">Fill Out Sequencing Submission Info</a></p>
					<p>
					<!--Sequencing2 Dropdown-->
					<label class="textbox-label">Select Sequencing Submission Info:</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown('seqInfo', 'sequencing2', 'sequencing_info','sequencing_info',$submitted);
					?>
					</p>
					</fieldset>
				</div>
				
				<div class="col-xs-6">
					<fieldset>
					<LEGEND><b>Step Five: Downstream Analysis Info</b></LEGEND>
					<p>
					<!--Analysis Pipeline Name Dropdown-->
					<label class="textbox-label">Select Analysis Pipeline:</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown('anPipe', 'analysis', 'analysis_name','analysis_name',$submitted);
					?>
					</p>
					</fieldset>
				</div>
				</div>
				</div>
				</div>
			
				
				<div class="col-md-12">
				<fieldset>
				<LEGEND><b>Notes</b></LEGEND>
				<p>
				<label class="textbox-label">Sample Notes:(optional)</label>
				<textarea class="form-control" from="sample_form" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {echo $p_notes;} ?></textarea>
				</p>
				</fieldset>
	
	
				<!--submit button-->
				<button class="button" type="submit" name="submit" value="1">Add </button>
				<button class="button" type="submit" name="copy" value="1">Add and Copy </button>
					
		</form>
		<script type="text/javascript">
				var name_check = 'true';
			    function validate(from) {

			       var valid = 'true';
				   // if(check_form() == 'false'){
				   if(check_form() == 'false'){
				    	valid = 'false';
				   }
				   if(check_form_required() == 'false'){
				    	valid = 'false';
				   }	
				   check_sample_name_insert();
				   if(name_check == 'false'){
				   		alert('Sample Name Not Valid. Please Check Project Name And Sample Number');
				    	valid = 'false';
				    }

				   if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				   }
				   else{
				   		return confirm('Sure You Want To Submit?');
				   }
				}
				
				function check_form(){
					var index;
					var valid = 'true';
					var x = document.getElementById('air_samp_num').value;
					if(x == 0){
						valid = 'false';
						document.getElementById('air_samp_num').style.backgroundColor = 'blue';
					}
					else{
						//create a contains method to check if airSamp is entered twice
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						var seen = [];
						//validate airSamp data
						for (index = 1; index <= x; ++index) {
	   	 					var airSamp_name = 'airSamp'+index;
	   	 					//check that airSamp is picked 
	   	 					var airSamp_name_value = document.getElementById(airSamp_name).value;
	   	 					if(airSamp_name_value == '0' || airSamp_name_value == 'Needs to be added'){
	   	 						alert("Whoops! Sensor Name Is Not Valid");
	   	 						document.getElementById(airSamp_name).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						//check to see if airSamp name is already input
	   	 						if(seen.contains(airSamp_name_value)){
	   	 							document.getElementById(airSamp_name).style.backgroundColor = 'blue';
	   	 							alert("You Have Chosen More Than One Air Sampler With The Same Name. Please Check Names");
	   	 							valid = 'false';
	   	 						}
	   	 					    else{
	   	 							seen.push(airSamp_name_value);
	   	 							document.getElementById(airSamp_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}
	   	 				
	   	 					//check start and end date/times are entered and make sense
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						alert("Whoops! Please Enter A Start Time");
	   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;
	   	 					if(end_time_value == ''){
	   	 						alert("Whoops! Please Enter An End Time");
	   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					
	   	 					var start_date = 'sdate'+index;
	   	 					var start_date_value = document.getElementById(start_date).value;
	   	 					if(start_date_value == ''){
	   	 						alert("Whoops! Please Enter An Starting Date");
	   	 						document.getElementById(start_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					var end_date = 'edate'+index;
	   	 					
	   	 					var end_date_value = document.getElementById(end_date).value;
	   	 					if(end_date_value.length == '0'){
	   	 						alert("Whoops! Please Enter An End Date");
	   	 						document.getElementById(end_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 				
							
							if(start_time_value != '' && start_date_value != '' && end_time_value != '' && end_date_value != ''){
								
								//first check if date time values make sense
								var p_start = start_date_value+' '+start_time_value;
								var p_end = end_date_value+' '+end_time_value;

								if((p_start) && (p_end)){
									
									var ts1 = Date.parse(p_start);
									var ts2 = Date.parse(p_end);
									var seconds_diff = ts2 - ts1;
									var time = (seconds_diff/3600);
									time = (time/1000); 

									var p_time = time.toFixed(2);
									var airSamp_check = airSamp_name.match(/^Coriolis.*/);

									if(p_time < 0){
										valid = 'false';
										alert("Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else if(p_time > 6.5 && airSamp_check  == null){//check if coriolis sampling is greater than 6 hours
										valid = 'false';
										alert("Sampling Is Greater Than 6 Hours For Coriolis Sampling. Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else{
										document.getElementById(start_time).style.backgroundColor = 'white';
										document.getElementById(end_time).style.backgroundColor = 'white';
										document.getElementById(start_date).style.backgroundColor = 'white';
										document.getElementById(end_date).style.backgroundColor = 'white';
									}
								}
							}
						}
					}	
					return valid;
					
				}
			</script>
			
	</body>
	
</html>
