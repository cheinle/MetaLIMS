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
<!---testing-->
<body>
<?php 
include('../index.php');
include('../functions/dropDown.php');
?>
<div class="page-header">
<h3>Add Daily Data</h3>
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
			
			//check if date exists
			$stmt1 = $dbc->prepare("SELECT daily_date FROM daily_data2 WHERE daily_date = ?");
			$stmt1 -> bind_param('s', $p_mydate);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			#echo 'Another way:'.print_r($row, true); //won't work with bind_result
        			if($name == $p_mydate){
        				echo $p_mydate." exists. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
			
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
			if($p_co2 != ""){//1-4 digits
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
			if($p_haze != ""){//1-2 digit
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
					
					//insert into daily_data2
					$stmt = $dbc -> prepare("INSERT INTO daily_data2 (daily_date,
																	notes,
																	entered_by,
																	temp,
																	temp_record,
																	hum,
																	hum_record,
																	co2,
																	co2_record,
																	rain,
																	rain_record,
																	wind, 
																	wind_record,
																	haze,
																	haze_record,
																	orig_time_stamp) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
					$stmt -> bind_param('sssdsdsdsdsdsiss', $p_mydate,
															$p_notes,
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
															$p_orig_time_stamp);
					
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;
					
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected >= 0){
						#echo 'You added new Daily Data! :'.$p_mydate.'<br>';
						$submitted = 'true';
					}else{
						$commit_check = "false";
						echo 'An error has occured';
						throw new Exception("An error has occred: No Added Daily Date");
					}
					
					
					//Insert into daily_data2_particle_counter. Wrap in a for loop
					
					for ($x = 1; $x <= $num_of_sens; $x++) {
						$p_part_sens_name = $_GET['sensor'.$x];
						$stmt2 = $dbc -> prepare("INSERT INTO daily_data2_particle_counter (daily_date,part_sens_name,start_time,end_time) VALUES (?,?,?,?)");
						$stmt2 -> bind_param('ssss', $p_mydate,$p_part_sens_name,$p_stime[$x],$p_etime[$x]);
						
						$stmt2 -> execute();
						$rows_affected2 = $stmt2 ->affected_rows;
						
						$stmt2 -> close();
						
						//check if add was successful or not. Tell the user
				   		if($rows_affected2 >= 0){
				   			echo 'You added new Daily Data! :'.$p_mydate.'<br>';
							#echo 'You Added New Sensor Data!<br>';
							$submitted = 'true';
						}else{
							$commit_check = "false";
							echo 'An error has occured';
							throw new Exception("An error has occured: No Added Sensor Info");
							
						}
					}
					//SELECT all samples what are entred for this date
					//Update each sample with a link to this daily date
					
					//make your date span the entire day
					$p_smydate = $p_mydate.' 00:00:00';
					$p_emydate = $p_mydate.' 23:59:00';
					
					$sample_array = array();
					$stmt_sid= $dbc->prepare("SELECT sample_name FROM sample WHERE start_samp_date_time BETWEEN (?) AND (?)");
					$stmt_sid -> bind_param('ss', $p_smydate,$p_emydate);
					if ($stmt_sid->execute()){
						$stmt_sid->bind_result($sample_name);
					    while($stmt_sid->fetch()){
							$sample_array[] = $sample_name;
						}
					} 
					else {
						$commit_check = "false";
						$error = 'true';
					    die('execute() failed: ' . htmlspecialchars($stmt_sid->error));
						throw new Exception("An error has occred: Was Not Able to Find Samples For This Date");
					}
					$stmt_sid -> close();
					
					//check size of sample array. if you didn't return anything, throw error
					if(count($sample_array) == 0){
						echo "Warning: No Samples Were Found With This Date. Please Manually Link Any Samples Later Added<br>";
					}
					$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
					foreach($sample_array as $key => $sample_name){
						echo $sample_name.'<br>';
						$query = 'UPDATE sample SET daily_data = ?,updated_by = ? WHERE sample_name = ?';
					    if($stmt = $dbc ->prepare($query)) {                 
					        $stmt->bind_param('sss', $p_mydate, $p_updated_by,$sample_name);
							$stmt -> execute();
							$rows_affected = $stmt ->affected_rows;
							$stmt -> close();
									
							//check if add was successful or not. Tell the user
							if($rows_affected >= 0){
								/*echo "dbc info".$dbc->info;
								preg_match_all ('/(\S[^:]+): (\d+)/', $dbc->info, $matches);
	    						$info = array_combine ($matches[1], $matches[2]);
								if ($info ['Rows matched'] == 0) {
	        						//echo "This operation did not match any rows.\n";
	        						$commit_check = "false";
									echo '<script>Alert.render("ERROR:Unable to find entry to update. Please contact admin");</script>';
									throw new Exception("ERROR:Unable to find entry to update. Please contact admin");	
								} 
	    						elseif ($info ['Changed'] == 0) {
	    							//for testing only
	        						#echo "This operation matched rows, but none required updating.\n";
	   							}
								else{
									echo "You Update Daily Data For ".$sample_name.'<br>';
								}*/
								
								//check if add was successful or not. Tell the user
								echo 'You Updated Sample Info For Daily Data!:'.$sample_name.'<br>';
								
									
							}
							else{
								$commit_check = "false";
								echo '<script>Alert.render("ERROR:Your sample has been updated by another user since you began your update. Please refresh your page to view updated information and try again");</script>';
								throw new Exception("An error has occred: No Update To Samples");
								echo 'An error has occured';
								mysqli_error($dbc);
										
							}
					            	
						}
						else{
							$commit_check = "false";
							throw new Exception("Prepare Error: No Samples Updated With Daily Data");
						}
					
					}
					
					if($commit_check == 'true'){
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
	<form name="form_name" class="registration" onsubmit="return validate(this)" action="add_daily_data.php" method="GET">
	<p><i>* = required field </i></p>
		<div class="container-fluid">
  		<div class="row">
		
		<fieldset>
		<LEGEND><b>Date:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Daily Data DATE:*</label>
		<input type="text" id="datepicker"  name="mydate" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>"/>
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		</p>
		</div>
		</fieldset>

		
		<fieldset>
		<LEGEND><b>Sensor Data:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Pick Number Of Sensors Used:</label><br>
		<select id='sens_num' name='sens_num' class='fields'>
		<option value='0'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "0" )){ echo "selected";}}?>>-Select-</option>
		<option value='1'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "1" )){ echo "selected";}}?>>-1:One-</option>
		<option value='2'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "2" )){ echo "selected";}}?>>-2:Two-</option>
		<option value='3'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "3" )){ echo "selected";}}?>>-3:Three-</option>
		<option value='4'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "4" )){ echo "selected";}}?>>-4:Four-</option>
		<option value='5'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "5" )){ echo "selected";}}?>>-5:Five-</option>
		<option value='6'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "6" )){ echo "selected";}}?>>-6:Six-</option>
		</select>
		<div id="div1">
		</div>
		</p>
		</div>
		</fieldset>
		
		<fieldset>
		<LEGEND><b>Weather Data:(Optional)</b></LEGEND>
		<div class="col-xs-6">
		
		<div id = 'inline1'>
		<p>
		<label class="textbox-label">Average Temperature:</label><br>
		<input type="text" name="temp" id = "temp" class="fields" placeholder="Enter An Avg Temp in Celsius" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_temp;}?>">
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Temperature:</label><br>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$submitted
		dropDown('temp_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		
		<div id = 'inline2'>
		<p>
		<label class="textbox-label">Average Relative Humidity:</label><br>
		<input type="text" name="hum" id = "hum" class="fields" placeholder="Enter An Avg Humidity" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_hum;}?>"/>
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Humidity:</label><br>
		<?php
		dropDown('hum_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		
		<div id = 'inline3'>
		<p>
		<label class="textbox-label">Average CO2:</label><br>
		<input type="text" name="co2" id="co2" class="fields" placeholder="Enter An Avg CO2" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_co2;}?>"/>
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input CO2:</label><br>
		<?php
		dropDown('co2_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		
		<div id = 'inline4'>
		<p>
		<label class="textbox-label">Average Windspeed:</label><br>
		<input type="text" name="wind" id="wind" class="fields" placeholder="Enter An Avg Windspeed (m/s)" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_wind;}?>"/>
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Windspeed:</label><br>
		<?php
		dropDown('wind_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		
		<div id = 'inline5'>
		<p>
		<label class="textbox-label">Average Rainfall:</label><br>
		<input type="text" name="rain" id="rain" class="fields" placeholder="Enter An Avg Rainfall (mm)" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_rain;}?>"/>
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Rain:</label><br>
		<?php
		dropDown('rain_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		
		<div id = 'inline6'>
		<p>
		<label class="textbox-label">Average Haze:</label><br>
		<input type="text" name="haze" id="haze" class="fields" placeholder="Enter An Avg Haze (PSI)" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_haze;}?>"/>
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Haze:</label><br>
		<?php
		dropDown('haze_record', 'records', 'records','records',$submitted);
		?>
		</p>
		</div>
		</div>
		</fieldset>
		
		
		<fieldset>
		<LEGEND><b>Daily Notes:(Optional)</b></LEGEND>
		<p>
		<label class="textbox-label">Sample Notes:</label>
		<textarea class="form-control" from="sample_form" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_notes;} ?></textarea>
		</p>
		</fieldset>
		<script type="text/javascript">
		
			    function validate(from) {
			    	
			    	//if you tried to submit, check the entire page for color?
			    	//return valid is false if you find it
			    	var date = document.getElementById('datepicker').value;
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Sure You Want To Add: '+date+'??? Action Cannot Be Easily Undone');
				    }
				}
				
				function check_form(){
					var index;
					var valid = 'true';
					var x = document.getElementById('sens_num').value;
					if(x == 0){
						valid = 'false';
						document.getElementById('sens_num').style.backgroundColor = 'blue';
					}
					else{
						//create a contains method to check if sensor is entered twice
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
							var seen = [];
						//validate sensor data
						for (index = 1; index <= x; ++index) {
	   	 					var sensor_name = 'sensor'+index;
	   	 					//check that sensor is picked 
	   	 					var sensor_name_value = document.getElementById(sensor_name).value;
	   	 					//alert(sensor_name_value);
	   	 					if(sensor_name_value == '0' || sensor_name_value == 'Needs to be added' || sensor_name_value == 'N/A' || sensor_name_value == '(pooled)' || sensor_name_value == 'test_sensor'){
	   	 						alert("Whoops! Sensor Name Is Not Valid");
	   	 						document.getElementById(sensor_name).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						//check to see if sensor name is already input
	   	 						if(seen.contains(sensor_name_value)){
	   	 							document.getElementById(sensor_name).style.backgroundColor = 'blue';
	   	 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
	   	 							valid = 'false';
	   	 						}
	   	 					    else{
	   	 							seen.push(sensor_name_value);
	   	 							document.getElementById(sensor_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}
	   	 					//check that start and end date are entered
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						alert("Whoops! Please Enter A Start Date");
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
	   	 					else{
	   	 						document.getElementById(end_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					//check that start time is earlier than end time
	   	 					if(start_time_value > end_time_value){
	   	 						alert("Whoops! Please Check Start And End Times");
	   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
	   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 						document.getElementById(end_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
						}
					}
						//validate other info
						
						//check date
	   	 				var date = 'datepicker';
	   	 				var date_value = document.getElementById(date).value;
	   	 				if(date_value == ''){
	   	 					alert("Whoops! Please Enter Daily Date");
	   	 					document.getElementById(date).style.backgroundColor = 'blue';
	   	 					valid = 'false'
	   	 				}
	   	 				else{
	   	 					document.getElementById(date).style.backgroundColor = 'white';
	   	 				}
	   	 				
	   	 				//now check the input/dropdown pairs
	   	 				var num_divs = '6';
	   	 				for (index_div = 1; index_div <= num_divs; ++index_div) {
		   	 				var divs = document.getElementById('inline'+index_div);
		   	 				
		   	 				var inputs = divs.getElementsByTagName('input');
		   	 				var selects = divs.getElementsByTagName('select');
	
	
		   	 				for (index = 0; index < inputs.length; ++index) {
	    						//deal with inputs[index] element
	    						//assume same number of input and select fields.
	    						input_value = inputs[index].value
	    						select_value = selects[index].value
	    						
	    						//check if the dropdown exists, the input exists also (and vice versa)
	    						if((input_value == '' && select_value != '0') || ((input_value != '' && select_value == '0'))){
		   	 						inputs[index].style.backgroundColor = 'blue';
		   	 						selects[index].style.backgroundColor = 'blue';
		   	 						valid = 'false'
		   	 					}
		   	 					else if(input_value != '' &&  select_value != '0'){
		   	 						//check if input is there it is the correct format
		   	 						var input_check = input_value;
		   	 						var input_id = inputs[index].id;
		   	 						//alert(input_id);
		   	 						if(input_id == 'temp'){
		   	 							if(!input_check.match(/^\s*(?=.*[0-9])\d{0,2}(?:\.\d{1,3})?\s*$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! Temperature Should Be Up To 2 Decimal Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 							
		   	 						}
		   	 						
		   	 						else if(input_id == 'co2'){
		   	 							if(!input_check.match(/^\d{1,4}$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! CO2 Should Be A Whole Number Up To 4 Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 						
		   	 						else if(input_id == 'haze'){
		   	 							if(!input_check.match(/^[0-9]{1,2}$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! Haze Should Be A Whole Number Up To 2 Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 
		   	 						else{
		   	 							if(!input_check.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,3})?\s*$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! "+input_check+"Should Be A Decimal Up To 3 Decimal Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 						
		   	 					}
		   	 					else{//if there is no input for either input or dropdown
		   	 						inputs[index].style.backgroundColor = 'white';
		   	 						selects[index].style.backgroundColor = 'white';
		   	 					}
							}
						}
					   
						
					return valid;
				}
			
			
			</script>
			<input type='submit'class="button" id="sub"  name ="submit" value='Add Daily Data' />
			<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</div>
		</div>
	</form>
	
	

	
</body>
	
</html>
