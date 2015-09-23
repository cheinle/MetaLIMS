

<?php	//function to check fields have appropriate info
function field_check($get_array,$check_for){
				include ('database_connection.php');
				include("functions/check_collector_names.php");

				$error = 'false';
				//check that all date/times exist
				/*if((!$get_array['sdate']) || (!$get_array['stime']) || (!$get_array['edate']) || (!$get_array['etime'])){
					echo "<p>ERROR: Check Date/Time(s)".'</p><br>';
					$error = 'true';
				}*/
				
				//check that storage locations are picked 
					//original storage
				if ($get_array['oStore_temp'] == '0') {//check this makes sense
					echo '<p>ERROR: You must select a Sample Storage Temperature!<p>';
					$error = 'true';
				}
				if ($get_array['oStore_name'] == '' || $get_array['oStore_name'] == '0') {
					echo '<p>ERROR: You must select a Sample Storage Location!<p>';
					$error = 'true';
				}
				 if(($get_array['oStore_name'] == 'Used' && $get_array['oStore_temp'] != 'Used') ||(($get_array['oStore_temp'] == 'Used' && $get_array['oStore_name'] != 'Used'))){
				 	echo '<p>ERROR: Please check Sample Storage Location!<p>';
					$error = 'true';
				 }
				
				
				//add other storage locations?
				
				
				
				//if you have a storage freezer, you must have a storage drawer
				//check that if freezer is set, the drawer is also set
				if($_GET['oStore_temp'] != '0'){
					if($_GET['oStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer</p>";
						$error = 'true';
					}
				}
				if($_GET['dStore_temp'] != '0'){
					if($_GET['dStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer For Your DNA</p>";
						$error = 'true';
					}
				}
				if($_GET['rStore_temp'] != '0'){
					if($_GET['rStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer For Your RNA</p>";
						$error = 'true';
					}
				}
				
				//check if required fields are entered
				$name_check = 'false';
				
				if ($get_array['sample_number'] == '0') {
					echo '<p>ERROR: You must enter a Sample Number!<p>';
					$error = 'true';
				}
				
				if ($get_array['projName']== '0') {
					echo '<p>ERROR: You must select Project Name!<p>';
					$error = 'true';
				}
				if ($get_array['loc'] == '0') {
					echo '<p>ERROR: You must select a Location Name!<p>';
					$error = 'true';
				}
				if ($get_array['rloc'] == '0') {
					echo '<p>ERROR: You must select a Relative Location Name!<p>';
					$error = 'true';
				}
				//if ($get_array['airSamp'] == '0') {
				//	echo '<p>ERROR: You must select an Air Sampler Name!<p>';
				//	$error = 'true';
				//}
				if ($get_array['media'] == '0') {
					echo '<p>ERROR: You must select a Media Type!<p>';
					$error = 'true';
				}
	
				#if ($get_array['partSamp'] == '0') {
				#	echo '<p>ERROR: You must select a Particle Counter Name!<p>';
				#	$error = 'true';
				#}
				if ($get_array['sType'] == '0') {
					echo '<p>ERROR: You must enter a Sample Type!<p>';
					$error = 'true';
				}
				
				//check flow rate if sample is an air sample
				//check if flow rate is a number
				if($get_array['sType'] == 'A'){
					if(($get_array['fRate'] == '') || ($get_array['fRate_eod'] == '')){
						echo '<p>ERROR: You must enter a Flow Rate!<p>';
						$error = 'true';
					}
					else{
						#$regrex_check_fr  = '/(^[0-9]{0,3}\.[0-9][0-9]|[0-9]{0,3})$/';//flow rate should be between 0 and 3 digit number allowing for two decimal places
						$regrex_check_fr  = '/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*$/';
						if (!preg_match("$regrex_check_fr", $get_array['fRate'])){
							echo '<p>ERROR: You Must Enter Valid Flow Rate. Please Check Your Number.<p>';
							$error = 'true';
						}
						if (!preg_match("$regrex_check_fr", $get_array['fRate_eod'])){
							echo '<p>ERROR: You Must Enter Valid EOD Flow Rate. Please Check Your Number.<p>';
							$error = 'true';
						}	
					}
					
					//if sample type is P-pool, then restrict user from choosing
					if(!isset($get_array['pooled_flag']) || $get_array['pooled_flag'] == 'NP'){//if you are not a pooled sample
						if($get_array['sType'] == 'P'){
							echo '<p>ERROR: Unable To Choose Sample For Pooling Here. Please Use Sample Pooling Page<p>';
							$error = 'true';	
						}
					}
					
					
					//check sampling height if air sample
					if ($get_array['sampling_height'] == '') {
						echo '<p>ERROR: You must enter a Sampling Height!<p>';
						$error = 'true';
					}
					elseif($get_array['sampling_height'] == '0.00'){
						echo '<p>Warning: Sampling Height Is Set To 0.00 cm Above The Ground. Please Check This Is True<p>';
					}
					else{
						//check that sampling height is a number
						$regrex_check_sh  = '/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/'; //this can be zero
						if (!preg_match("$regrex_check_sh", $get_array['sampling_height'])){
							echo '<p>ERROR: You Must Enter Valid Sampling Height. Please Check Your Number.<p>';
							$error = 'true';
						}
					}
					//these fields should not be filled out for non isolates
					if($get_array['iso_store_method'] != '0' 
						|| $get_array['iso_coll_temp'] != '0' 
						|| $get_array['iso_date'] != ''
						|| $get_array['sang_seq'] != ''
						|| $get_array['closest_hit'] != ''
						|| $get_array['send_pac_bio'] != '0'
						|| $get_array['iso_loc_type'] != '0'){
	        				echo '<p>ERROR: Fields Only Valid For Isolate Sample Types. Please Check Fields<p>';
							$error = 'true';
	        		}
							
				}
				else{
					//if you are a fungal or bacteria isolate
					if($get_array['sType'] == 'F' || $get_array['sType'] == 'BC' ||$get_array['sType'] == 'UI'){
						
						//check that required fields are entered
						if($get_array['iso_store_method'] == '0'){
							echo '<p>ERROR: You Must Enter Valid Isolate Storing Method. Please Check Your Entry.<p>';
							$error = 'true';
						}
        			
	        			if($get_array['iso_coll_temp'] == '0'){
	        				echo '<p>ERROR: You Must Enter Valid Isolate Collection Temp. Please Check Your Entry.<p>';
							$error = 'true';
	        			}
        		
	        			if($get_array['iso_date'] == ''){
	        				echo '<p>ERROR: You Must Enter Valid Isolate  Storage Date. Please Check Your Number.<p>';
							$error = 'true';
	        			}
						
						if($get_array['iso_loc_type'] == '0'){
	        				echo '<p>ERROR: You Must Enter Valid Isolate Location Type. Please Check Your Entry.<p>';
							$error = 'true';
	        			}
						
						//check that 16S Sanger seq is all alphabet
						if(isset($get_array['seq_sang'])){
							$regrex_check_ss  = '/^[A-Za-z]+$/';
							if (!preg_match("$regrex_check_ss", $get_array['sang_seq'])){
								echo '<p>ERROR: You Must Enter Valid Sanger Sequence. Try Only A-Z characters.<p>';
								$error = 'true';
							}
						}	
					}
					else{
						//these fields should not be filled out for non isolates
						if($get_array['iso_store_method'] != '0' 
						|| $get_array['iso_coll_temp'] != '0' 
						|| $get_array['iso_date'] != ''
						|| $get_array['sang_seq'] != ''
						|| $get_array['closest_hit'] != ''
						|| $get_array['send_pac_bio'] != '0'
						|| $get_array['iso_loc_type'] != '0'){
	        				echo '<p>ERROR: Fields Only Valid For Isolate Sample Types. Please Check Fieldssss<p>';
							$error = 'true';
	        			}
	        			#if(!isset($get_array['iso_store_method']) || !isset($get_array['iso_coll_temp']) || !isset($get_array['iso_date'])|| !isset($get_array['seq_sang' != ''])|| !isset($get_array['closest_hit' != ''])|| !isset($get_array['send_pac_bio' != '0'])){
	        			#	echo '<p>ERROR: Fields Only Valid For Isolate Sample Types. Please Check Fields<p>';
						#	$error = 'true';
	        			#}
	        			
	        			if(($get_array['fRate'] != '0') || ($get_array['fRate_eod'] != '0')){//if update page
							echo '<p>ERROR: Flow Rate for Samples Other Than Air Samples Should Be Zero!<p>';
							$error = 'true';
						}
					}
        			
							
					//anything that is not an air sample
					/*if((isset($get_array['submit']) && $get_array['submit'] == '1')|| (isset($get_array['copy']))){//if insert page
						if(($get_array['fRate'] != '0') || ($get_array['fRate_eod'] != '0')){
							echo '<p>ERROR: Flow Rate for Samples Other Than Air Samples Should Be Left Blank!<p>';
							$error = 'true';
						}
					}
					else{
						if(($get_array['fRate'] != '0') || ($get_array['fRate_eod'] != '0')){//if update page
							echo '<p>ERROR: Flow Rate for Samples Other Than Air Samples Should Be Zero!<p>';
							$error = 'true';
						}
					}*/
				}
				
				//check collector names
				if(isset($get_array['collector'])){
					$array=$get_array['collector'];
					$check = check_collector_names($array,'true');
					if($check['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid Collector Name(s). Please Check Name(s)<p>';
						$error = 'true';
					}
				}
				
				//check DNA Extractor Name(s)
				if(isset($get_array['dExtrName'])){
					$array2=$get_array['dExtrName'];
					$check2 = check_collector_names($array2,'false');
					if($check2['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid DNA Extractor Name(s). Please Check Names(s)<p>';
						$error = 'true';
					}
				}

				//check RNA Extractor Name(s)
				if(isset($get_array['rExtrName'])){
					$array3=$get_array['rExtrName'];
					$check3 = check_collector_names($array3,'false');
					if($check3['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid RNA Extractor Name(s). Please Check Names(s)<p>';
						$error = 'true';
					}
				}

				//cat together sample name here and then check if it exists
				//also cat together a sort name
				$regrex_check_sn  = '/^[0-9]{3}$/';
				if (!preg_match("$regrex_check_sn", $get_array['sample_number'])){
					echo '<p>ERROR: You Must Enter Valid Sample Number. Please Check Your Number.<p>';
					$error = 'true';
				}
				
				
				//New check name based on project name and sample number
				if($check_for == 'insert_sample'){
					$p_sample_name = $get_array['sdate1'].$get_array['projName'].$get_array['sType'].$get_array['sample_number'];
					if(($get_array['projName'] && $get_array['sample_number']) != NULL){
						$param1 = "%{$get_array['projName']}%";
						$param2 = "%{$get_array['sample_number']}";
						$stmt1 = $dbc->prepare("SELECT sample_name FROM sample WHERE sample_name LIKE ? AND sample_name LIKE ?");
						$stmt1->bind_param("ss", $param1,$param2);
					
						$stmt1->bind_result($col1);
											
						if ($stmt1->execute()){
							   $stmt1->bind_result($name);
							   if ($stmt1->fetch()){
							   		#echo "Name: {$name}<br>";
							   		echo "<p>ERROR: ".htmlspecialchars($p_sample_name)." cannot be added. Sample number for this project already exisits. Please check name.".'</p><br>';
									$error = 'true';	
								}
						} 
						else {
							$error = 'true';
							echo "new error";
							die('execute() failed: ' . htmlspecialchars($stmt1->error));
											
						}
						$stmt1 -> close();
					}
				}

				//if you have a start and end date/time, calculate total sampling time
				/*$p_time;
				$p_start = $get_array['sdate'].' '.$get_array['stime'];
				$p_end = $get_array['edate'].' '.$get_array['etime'];
				if(($p_start) && ($p_end)){
					
					$ts1 = strtotime($p_start);
					$ts2 = strtotime($p_end);

					$seconds_diff = $ts2 - $ts1;
					$time = ($seconds_diff/3600);
					$p_time = round($time,2);
					if($p_time < 0){
						echo "<p>ERROR: Check Start and End Times</p>";
						$error = 'true';
					}
				}
				*/
				
				
				//check that if coriolis, the sampling time should be 6 and 1/2hrs or less
				//(6hr max run time plus 10 minute loss rate check and 20 mintues for wait time)
				/*$air_sampler = $get_array['airSamp'];
				$regrex_check_as  = '/^Coriolis[0-9]$/';
				if (preg_match("$regrex_check_as", $air_sampler)){
					if($p_time > 6.5){
						echo '<p>ERROR: Your Sampling Time Is Greater Than 6 1/2 Hrs. Please Check That Your Sampling Times Are Correct.<p>';
						$error = 'true';
					}
				}*/
				
				
				//check that if your air sampler is a coriolis, that you don't already have 
				//this same coriolis for the same time frame
				
				// b_start <= a_end AND b_end >= a_start 
				//2014-12-01 10:30:00  2014-12-01 18:00:00  Vaccum Pump 5
				//SELECT * FROM `sample` WHERE `air_sampler_name`='Vaccum Pump 5' AND `start_samp_date_time` <= '2014-12-01 18:00:00' AND `end_samp_date_time`>= '2014-12-01 10:30:00'
				
				/*$air_sampler = $get_array['airSamp'];
				if (preg_match("$regrex_check_as", $air_sampler)){
					$start = $get_array['sdate'].' '.$get_array['stime'];
					$end = $get_array['edate'].' '.$get_array['etime'];
					
					$query2 = "SELECT sample_name FROM sample WHERE air_sampler_name = (?) AND start_samp_date_time < (?) AND end_samp_date_time > (?)";
					$stmt2 = $dbc->prepare($query2);
					$stmt2 -> bind_param('sss', $air_sampler,$end,$start);
			  		if ($stmt2->execute()){
			    		$stmt2->bind_result($name2);
			    		if ($stmt2->fetch()){
			    			//format sample name for check
							/*
							$date = htmlspecialchars($get_array['sdate']); //just using start date for sampling date
							$regrex_check = '/^(201[4-5])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
							preg_match($regrex_check,$date,$matches);
							$date = $matches[1].'/'.$matches[2].'/'.$matches[3];
							$p_projName=htmlspecialchars($get_array['projName']);
							$p_sType=htmlspecialchars($get_array['sType']);
							$p_sample_number=htmlspecialchars($get_array['sample_number']);
							$p_sample_name = $date.$p_projName.$p_sType.$p_sample_number;
							echo $p_sample_name.' '.$name2.' '.$get_array['sample_name'];
							 * 
							 */
			    /*			if($get_array['sample_name'] != $name2){//allows you to update a sample if the same names is the same. compare to original sample name 
			    				echo '<p>ERROR: Sampler '.$air_sampler.' Is Already Being Used During This Time. Please Check Air Sampler And Start/End Times<p>';
								$error = 'true';
			    			}
						}
					} 
					else {
						$error = 'true';
			    		die('execute() failed: ' . htmlspecialchars($stmt2->error));
					} 
					$stmt2 -> close();
					
				}*/			

				return $error;
				
				
			
}	
?>

</body>
</html>