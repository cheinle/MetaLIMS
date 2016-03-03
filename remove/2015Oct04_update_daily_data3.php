<?php include('../database_connection.php');
error_reporting(E_ALL); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Data Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/themes/redmond/jquery-ui.css" />
		
</head>

<body>
<?php 
include('../index.php');
include('../functions/dropDown_update_for_daily_data.php');
include('../functions/text_insert_update_storage_info.php');
 ?>
<div class="page-header">
<h3>Update Daily Data</h3>
</div>

<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input and check for required fields
			$p_mydate = htmlspecialchars($_GET['mydate']);
			$p_notes = htmlspecialchars($_GET['notes']);
			
			$p_temp = htmlspecialchars($_GET['temp']);
			$p_temp_record = htmlspecialchars($_GET['temp_record']);
			$p_hum = htmlspecialchars($_GET['hum']);
			$p_hum_record = htmlspecialchars($_GET['hum_record']);
			$p_co2 = htmlspecialchars($_GET['co2']);
			$p_co2_record = htmlspecialchars($_GET['co2_record']);
			$p_rain = htmlspecialchars($_GET['rain']);
			$p_rain_record = htmlspecialchars($_GET['rain_record']);
			$p_wind= htmlspecialchars($_GET['wind']);
			$p_wind_record = htmlspecialchars($_GET['wind_record']);
			$p_haze= htmlspecialchars($_GET['haze']);
			$p_haze_record= htmlspecialchars($_GET['haze_record']);
			
			
			if($p_mydate == ''){
					echo '<p>You Must Enter A Daily Data Date!<p>';
					$error = 'true';
			}
			
			if(($p_temp != '' && $p_temp_record == '0') || $p_temp == '' && $p_temp_record != '0'){
				echo '<p>You Must Enter Both A Temperature And A Record!<p>';
				$error = 'true';
			}
			if(($p_hum != '' && $p_hum_record == '0') || $p_hum == '' && $p_hum_record != '0'){
				echo '<p>You Must Enter Both A Humidity And A Record!<p>';
				$error = 'true';
			}
			if(($p_co2 != '' && $p_co2_record == '0') || $p_co2 == '' && $p_co2_record != '0'){
				echo '<p>You Must Enter Both A CO2 Amt And A Record!<p>';
				$error = 'true';
			}
			if(($p_wind != '' && $p_wind_record == '0') || $p_wind == '' && $p_wind_record != '0'){
				echo '<p>You Must Enter Both A Wind Speed And A Record!<p>';
				$error = 'true';
			}
			if(($p_rain != '' && $p_rain_record == '0') || $p_rain == '' && $p_rain_record != '0'){
				echo '<p>You Must Enter Both A Rainfall Amount And A Record!<p>';
				$error = 'true';
			}
			if(($p_haze != '' && $p_haze_record == '0') || $p_haze == '' && $p_haze_record != '0'){
				echo '<p>You Must Enter Both A Haze Amount And A Record!<p>';
				$error = 'true';
			}
			
			
			
			//repeat for x amount of sensors 
			$num_of_sens = $_GET['sens_num'];
			for ($x = 1; $x <= $num_of_sens; $x++) {
				$p_stime[$x] = htmlspecialchars($_GET['stime'.$x]);
				$p_etime[$x] = htmlspecialchars($_GET['etime'.$x]);
				
			
				//check to see that particle counter is a valid selection (no 'N/A' or 'Needs to be Added)
				if (preg_match('/^N\/A/', $_GET['sensor'.$x])){
					echo '<p>You Must Select A Valid Sensor! Please Check Sensor Name(s):'.$_GET['sensor'.$x].'<p>';
					$error = 'true';
				}
				if (preg_match('/^Needs to be added/', $_GET['sensor'.$x])){
					echo '<p>You Must Select A Valid Sensor! Please Sensor Name(s):'.$_GET['sensor'.$x].'<p>';
					$error = 'true';
				}
				if (preg_match('/^test_sensor/', $_GET['sensor'.$x])){
					echo '<p>You Must Select A Valid Sensor! Please Sensor Name(s):'.$_GET['sensor'.$x].'<p>';
					$error = 'true';
				}		
						
						
				//check to see if time pairs exist
				if(((!$p_stime[$x]) || (!$p_etime[$x]))){
					echo '<p>You Must Enter Both A Start And Stop Time! Please Check Times!<p>';
					$error = 'true';
				}

				//check to see if end time is after begin date
				if(($p_stime[$x]) > ($p_etime[$x])){
					echo '<p>Please Check Your Start And End Times:'.$p_stime[$x].','.$p_etime[$x].'!<p>';
					$error = 'true';
				}
			}
			
			
			
			//get username and update entered by with
			$p_entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
			
			//get current time
			$p_orig_time_stamp = date_default_timezone_set("Asia/Singapore");
			$p_orig_time_stamp = date("Y-m-d H:i:s");
			
			//check that each of your entries has the correct decimal places
			if($p_temp != ""){//00.000
				$regrex_check_temp  = '/^\s*(?=.*[0-9])\d{0,2}(?:\.\d{1,3})?\s*$/';
				if (!preg_match("$regrex_check_temp", $p_temp)){
					echo '<p>ERROR: You Must Enter Valid Temperature. Number must have no more than 3 decimal places.<p>';
					$error = 'true';
				}
			}
			if($p_hum != ""){//000.000
				$regrex_check_hum  = '/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,3})?\s*$/';
				if (!preg_match("$regrex_check_hum", $p_hum)){
					echo '<p>ERROR: You Must Enter Valid Humidity. Number must have no more than 3 decimal places.<p>';
					$error = 'true';
				}
			}
			if($p_co2 != ""){//0000
				$regrex_check_co2  = '/^\d{1,4}$/';
				if (!preg_match("$regrex_check_co2", $p_co2)){
					echo '<p>ERROR: You Must Enter Valid CO2. Number must be a 1-4 digit whole number<p>';
					$error = 'true';
				}
			}
			if($p_rain != ""){//000.000
				$regrex_check_rain  = '/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,3})?\s*$/';
				if (!preg_match("$regrex_check_rain", $p_rain)){
					echo '<p>ERROR: You Must Enter Valid Rain Amt (mm). Number must have no more than 3 decimal places.<p>';
					$error = 'true';
				}
			}
			if($p_wind != ""){//000.000
				$regrex_check_wind  = '/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,3})?\s*$/';
				if (!preg_match("$regrex_check_wind", $p_wind)){
					echo '<p>ERROR: You Must Enter Valid Wind Speed (m/s). Number must have no more than 3 decimal places.<p>';
					$error = 'true';
				}
			}
			if($p_haze != ""){//000.000
				$regrex_check_haze  = '/^[0-9]{1,2}/';
				if (!preg_match("$regrex_check_haze", $p_haze)){
					echo '<p>ERROR: You Must Enter Valid Haze Info (PSI). Expecting a 2 digit number<p>';
					$error = 'true';
				}
			}
			

			//check to see if non-required fields exist. 
			if ($p_temp == '') {$p_temp = NULL;}
			if ($p_hum == '') {$p_hum = NULL;} 
			if ($p_co2 == '') {$p_co2 = NULL;}
			if ($p_rain == '') {$p_rain = NULL;}
			if ($p_wind == '') {$p_wind = NULL;}
			if ($p_haze == '') {$p_haze = NULL;}
			
			if ($p_temp_record == '0') {$p_temp_record = NULL;}
			if ($p_hum_record == '0') {$p_hum_record = NULL;} 
			if ($p_co2_record == '0') {$p_co2_record = NULL;}
			if ($p_rain_record == '0') {$p_rain_record = NULL;}
			if ($p_wind_record  == '0') {$p_wind_record = NULL;}
			if ($p_haze_record  == '0') {$p_haze_record = NULL;}
		
			//insert info into db
		    if($error != 'true'){
		    	
				//insert data into db. 

				//first enter date (+ notes, +entered by) into daily data2
				//second for each sensor you have enter into daily_data2_particle_counter
				//a date-sensor pair with start and end time
				//don't forget to set up a way to retrieve this info
				//set up into a transaction?
				$commit_check = "true"; //assume you are going to commit unless you see an error
				try{
					$dbc->autocommit(FALSE);
					
					//update daily_data2
					$stmt = $dbc -> prepare("UPDATE daily_data2 SET notes = ?,
																	entered_by = ?,
																	temp = ?,
																	temp_record = ?,
																	hum = ?,
																	hum_record = ?,
																	co2 = ?,
																	co2_record = ?,
																	rain = ?,
																	rain_record = ?,
																	wind = ?, 
																	wind_record = ?,
																	haze = ?,
																	haze_record = ?,
																	orig_time_stamp = ? WHERE daily_date = ? ");
					$stmt -> bind_param('ssdsdsdsdsdsisss', $p_notes,
															$p_entered_by,
															$p_temp,
															$p_temp_record,
															$p_hum,
															$p_hum_record,
															$p_co2,
															$p_co2_record,
															$p_rain,
															$p_rain_record,
															$p_wind,
															$p_wind_record,
															$p_haze,
															$p_haze_record,
															$p_orig_time_stamp,
															$p_mydate);
					
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;
					
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected >= 0){
						echo 'You Updated Daily Data For :'.$p_mydate.'<br>';
					}else{
						$commit_check = "false";
						echo 'An error has occured';
						throw new Exception("An error has occured: No Updated Daily Date");
					}
					
					
					//Insert into daily_data2_particle_counter. Wrap in a for loop
					for ($x = 1; $x <= $num_of_sens; $x++) {
						$p_part_sens_name = $_GET['sensor'.$x];
						if(isset($_GET['delete'.$x])){
							//delete the daily data for this date/sensor primary key...can you do this?
							//check if it exists...if it does, delete it
							if(! $stmt_d = $dbc -> prepare("DELETE FROM daily_data2_particle_counter WHERE daily_date = ? AND part_sens_name = ?")){
								throw new Exception("Delete Sensor Prepare Failure: No Added Sensor Info");
							}
							$stmt_d->bind_param('ss',$p_mydate,$p_part_sens_name);
							$stmt_d->execute();
							$rows_affected_d = $stmt_d ->affected_rows;
							//check if add was successful or not. Tell the user
					   		if($rows_affected_d >= 0){
					   			echo 'You DELETED Sensor Info For Daily Data! :'.$p_part_sens_name.'<br>';
							}
							else{
								$commit_check = "false";
								echo 'An error has occured';
								throw new Exception("An error has occurred: No Added Sensor Info");
								
							}
							$stmt_d->close();
						}
						else{
							//search to see if exists,if it exists, update the entry
							//if it does not, insert it
							$exists = 'false';
							$stmt_sid= $dbc->prepare("SELECT part_sens_name FROM daily_data2_particle_counter WHERE daily_date = ? AND part_sens_name = ? ");
							$stmt_sid -> bind_param('ss', $p_mydate,$p_part_sens_name);
							if ($stmt_sid->execute()){
								$stmt_sid->bind_result($sample_name);
							    while($stmt_sid->fetch()){
									if($sample_name == $p_part_sens_name){
										$exists = 'true';
									}
								}
							} 
							$stmt_sid -> close();
							
							//if you did not see this entry in the db, insert it. else, update
							if($exists == 'false'){
								$stmt2 = $dbc -> prepare("INSERT INTO daily_data2_particle_counter (daily_date,part_sens_name,start_time,end_time) VALUES (?,?,?,?)");
								$stmt2 -> bind_param('ssss', $p_mydate,$p_part_sens_name,$p_stime[$x],$p_etime[$x]);
								$stmt2 -> execute();
								$rows_affected2 = $stmt2 ->affected_rows;
								$stmt2 -> close();
								
								//check if add was successful or not. Tell the user
						   		if($rows_affected2 >= 0){
						   			echo 'You Added New Sensor Info For Daily Data! :'.$p_part_sens_name.'<br>';
								}
								else{
									$commit_check = "false";
									echo 'An error has occured';
									throw new Exception("An error has occred: No Added Sensor Info");
									
								}
							}	
						
							else{
								$stmt3 = $dbc -> prepare("UPDATE daily_data2_particle_counter SET start_time = ?, end_time = ? WHERE daily_date = ? AND part_sens_name =?");
								$stmt3 -> bind_param('ssss',$p_stime[$x],$p_etime[$x], $p_mydate,$p_part_sens_name);
								$stmt3 -> execute();
								$rows_affected3 = $stmt3 ->affected_rows;
								$stmt3 -> close();
									
								//check if add was successful or not. Tell the user
							   	if($rows_affected3 >= 0){
									echo 'You Updated Sensor Info For Daily Data! :'.$p_part_sens_name.'<br>';
								}else{
									$commit_check = "false";
									echo 'An error has occured';
									throw new Exception("An error has occred: No Updated Sensor Info");
								}
							}
						}
					}
					if($commit_check == 'true'){
						$submitted = 'true';
						$dbc->commit();
					}
					else{
						throw new Exception("Unable To Commit. No Changes Made");
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
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
</body>
	
</html>
