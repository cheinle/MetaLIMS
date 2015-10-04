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
<h3>Add Daily Sensor Data</h3>
</div>
<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input and check for required fields
			$p_mydate = htmlspecialchars($_GET['mydate']);
			$p_mylocation = htmlspecialchars($_GET['loc']);
			$p_notes = htmlspecialchars($_GET['notes']);
			
		
			if($p_mydate == ''){
					echo '<p>You Must Enter A Daily Data Date!<p>';
					$error = 'true';
			}
			if($p_mylocation == '0'){
					echo '<p>You Must Enter A Location!<p>';
					$error = 'true';
			}
			
	
			
			//repeat for x amount of sensors 
			$num_of_sens = $_GET['sens_num'];
			for ($x = 1; $x <= $num_of_sens; $x++) {
				$p_stime[$x] = htmlspecialchars($_GET['stime'.$x]);
				$p_etime[$x] = htmlspecialchars($_GET['etime'.$x]);
				$p_measurement[$x] = htmlspecialchars($_GET['measurement'.$x]);
				$p_record[$x] = htmlspecialchars($_GET['record'.$x]);		
						
						
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
			
			//check if daily data already exists
			$stmt1 = $dbc->prepare("SELECT daily_date,location FROM daily_data2 WHERE daily_date = ? AND location = ?");
			$stmt1 -> bind_param('ss', $p_mydate,$p_mylocation);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name,$location);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";

        			if($name == $p_mydate && $location == $p_mylocation){
        				echo "Daily Data For ".$p_mydate." And Location ".$p_mylocation." exists. Please check name.";
						$error = 'true';
					}
				}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
			}
			$stmt1 -> close();
			
			//get username and update entered by with
			$p_entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
			
			//get current time
			$p_orig_time_stamp = date_default_timezone_set("Asia/Singapore");
			$p_orig_time_stamp = date("Y-m-d H:i:s");
			
			//insert info into db
		    if($error != 'true'){
		    	
				//insert data into db. 

				//first enter date (+ notes, +entered by) into daily data2
				//second for each sensor you have enter into daily_data2_particle_counter
				//a date-sensor pair with start and end time

				$commit_check = "true"; //assume you are going to commit unless you see an error
				try{
					$dbc->autocommit(FALSE);
					
					//insert into daily_data2
					$stmt = $dbc -> prepare("INSERT INTO daily_data2 (daily_date,location,notes,entered_by,orig_time_stamp) VALUES (?,?,?,?,?)");
					$stmt -> bind_param('sssss', $p_mydate,$p_mylocation,$p_notes,$p_entered_by,$p_orig_time_stamp);
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;
					
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected < 0){
						$commit_check = "false";
						throw new Exception("An Error Has Occured: No Added Daily Data");
					}
					
					
					//Insert into daily_data2_particle_counter. Wrap in a for loop
					for ($x = 1; $x <= $num_of_sens; $x++) {
						$p_part_sens_name = $_GET['sensor'.$x];
						$stmt2 = $dbc -> prepare("INSERT INTO daily_data2_particle_counter (daily_date,part_sens_name,start_time,end_time,avg_measurement,record_source,location) VALUES (?,?,?,?,?,?,?)");
						$stmt2 -> bind_param('sssssss', $p_mydate,$p_part_sens_name,$p_stime[$x],$p_etime[$x],$p_measurement[$x],$p_record[$x],$p_mylocation);
						
						$stmt2 -> execute();
						$rows_affected2 = $stmt2 ->affected_rows;
						
						$stmt2 -> close();
						
						//check if add was successful or not. Tell the user
				   		if($rows_affected2 >= 0){
				   			echo 'You Added New Daily Data! :'.$p_mydate.' For Location '.$p_mylocation.'<br>';
							$submitted = 'true';
						}else{
							$commit_check = "false";
							throw new Exception("An Error Has Occured: No Added Sensor Info");
							
						}
					}
					//SELECT all samples what are entred for this date
					//Update each sample with a link to this daily date
					
					//make your date span the entire day
					$p_smydate = $p_mydate.' 00:00:00';
					$p_emydate = $p_mydate.' 23:59:00';
					
					$sample_array = array();
					$stmt_sid= $dbc->prepare("SELECT sample_name FROM sample WHERE start_samp_date_time BETWEEN (?) AND (?) AND location_name = ?");
					$stmt_sid -> bind_param('sss', $p_smydate,$p_emydate,$p_mylocation);
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
						echo "Notice: No Samples Were Found With This Date And Location. No Samples Have Been Updated With This Information<br>";
					}
					$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
					foreach($sample_array as $key => $sample_name){
						echo $sample_name.'<br>';
						$daily_data_entry = $p_emydate.' '.$p_mylocation;
						$query = 'UPDATE sample SET daily_data = ?,updated_by = ? WHERE sample_name = ? AND location = ?';
					    if($stmt = $dbc ->prepare($query)) {                 
					        $stmt->bind_param('sss', $daily_data_entry, $p_updated_by,$sample_name,$p_mylocation);
							$stmt -> execute();
							$rows_affected = $stmt ->affected_rows;
							$stmt -> close();
									
							//check if add was successful or not. Tell the user
							if($rows_affected >= 0){
								echo 'You Updated Sample Info For Daily Data!:'.$sample_name.'<br>';	
							}
							else{
								$commit_check = "false";
								throw new Exception("An Error Has Occured: No Update To Samples");	
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
		
		
		<fieldset>
		<LEGEND><b>Location/Date:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Daily Data DATE:*</label>
		<input type="text" id="datepicker"  name="mydate" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>"/>
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		</p>
		
		
		<!--location dropdown-->
		<p>
		<label class="textbox-label">Select Location:*</label>
		<?php

		//url or $_GET name, table name, field name
		dropDown('loc', 'location', 'loc_name','loc_name',$submitted);
		?>
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
		<LEGEND><b>Daily Notes:(Optional)</b></LEGEND>
		<p>
		<label class="textbox-label">Sample Notes:</label>
		<textarea class="form-control" from="sample_form" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_notes;} ?></textarea>
		</p>
		</fieldset>
		<script type="text/javascript">
		
			    function validate(form) {
			    	
			    	//if you tried to submit, check the entire page
			    	//return valid is false if you find erro
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
					var valid = 'true';
					var x = document.getElementById('sens_num').value;
					
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
   	 				
   	 				//check selects are selected for required data
					var selects = document.getElementsByTagName("select");
		            var i2;
		             for (i2 = 0; i2 < selects.length; i2++) {
		                 selected = selects[i2].value;
		                 var name2 = selects[i2].getAttribute("name");
		                
			                 if(selected == '0'){
			                 	selects[i2].style.backgroundColor = "blue";
			                    valid = 'false';
			                 }
			                 else{
			                 	selects[i2].style.backgroundColor = "white";
			                 }

					}

					 //grab all inputs
		             var inputs = document.getElementsByTagName("input");
		             var txt = "";
		             var i;
		             for (i = 0; i < inputs.length; i++) {
		                 txt = inputs[i].value;
		                 var name = inputs[i].getAttribute("name");
		                 //check if your input is empty
			             var n = txt.length;
			             if(n == 0){
			             	inputs[i].style.background = "blue";
			                valid = 'false';
		                 }else{
							inputs[i].style.background = "white";
						}
					}
				
					if(valid == 'true'){ //if your form is still valid, go ahead and do some more checks
						//create a contains method to check if sensor is entered more than once
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
						for (var index = 1; index <= x; index++) {
	   	 					var sensor_name = 'sensor'+index;
	   	 					//check that sensor is picked 
	   	 					var sensor_name_value = document.getElementById(sensor_name).value;
	   	 					
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
	   	 					
	   	 					//check that start time is earlier than end time
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;

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
		   	 				
		   	 				//check avg sensor measurement is a 2 digit decimal
		   	 				var measurement = 'measurement'+index;
	   	 					var measurement_value = document.getElementById(measurement).value;
	

 							if(!measurement_value.match(/^\s*(?=.*[0-9])\d{0,4}(?:\.\d{1,2})?\s*$/)){
 								document.getElementById(measurement).style.backgroundColor = 'blue';
 								valid = 'false'
 								alert("Whoops! Measurement Should Be No More Than 2 Decimal Places And 6 Digits");
 							}
 							else{
 								document.getElementById(measurement).style.backgroundColor = 'white';
 							}
	   	 					
						}
					}
					
					return valid;
				
				}
			
			
			</script>
			<input type='submit'class="button" id="sub"  name ="submit" value='Add Daily Data' />
			<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		
	</form>
	
	

	
</body>
	
</html>
