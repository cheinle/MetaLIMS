<?php 
include('../index.php');
include('../database_connection.php');
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
<div class="page-header">
<h3>Update Daily Data</h3>
</div>

<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';

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
				//don't forget to set up a way to retrieve this info
				//set up into a transaction?
				$commit_check = "true"; //assume you are going to commit unless you see an error
				try{
					$dbc->autocommit(FALSE);
					
					//update daily_data2
					$stmt = $dbc -> prepare("UPDATE daily_data2 SET notes = ?,entered_by = ?,orig_time_stamp = ? WHERE daily_date = ? AND location = ?");
					$stmt -> bind_param('sssss', $p_notes,$p_entered_by,$p_orig_time_stamp,$p_mydate,$p_mylocation);
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected < 0){
						$commit_check = "false";
						throw new Exception("An Error Has Occurred: No Updated Daily Date");
					}
					
					
					//Insert into daily_data2_particle_counter. Wrap in a for loop
					for ($x = 1; $x <= $num_of_sens; $x++) {
						$p_part_sens_name = $_GET['sensor'.$x];
						if(isset($_GET['delete'.$x])){
							//delete the daily data for this date/sensor primary key...can you do this?
							//check if it exists...if it does, delete it
							if(! $stmt_d = $dbc -> prepare("DELETE FROM daily_data2_particle_counter WHERE daily_date = ? AND part_sens_name = ? AND location = ?")){
								throw new Exception("Delete Sensor Prepare Failure: No Added Sensor Info");
							}
							$stmt_d->bind_param('sss',$p_mydate,$p_part_sens_name,$p_mylocation);
							$stmt_d->execute();
							$rows_affected_d = $stmt_d ->affected_rows;
							//check if add was successful or not. Tell the user
					   		if($rows_affected_d >= 0){
					   			echo 'You DELETED Sensor Info For Daily Data! :'.$p_part_sens_name.'<br>';
							}
							else{
								$commit_check = "false";
								throw new Exception("An Error Has Occurred: No Added Sensor Info");
								
							}
							$stmt_d->close();
						}
						else{
							//search to see if exists,if it exists, update the entry
							//if it does not, insert it
							$exists = 'false';
							$stmt_sid= $dbc->prepare("SELECT part_sens_name FROM daily_data2_particle_counter WHERE daily_date = ? AND part_sens_name = ? AND location = ?");
							$stmt_sid -> bind_param('sss', $p_mydate,$p_part_sens_name,$p_mylocation);
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
								$stmt2 = $dbc -> prepare("INSERT INTO daily_data2_particle_counter (daily_date,part_sens_name,start_time,end_time,avg_measurement,record_source,location) VALUES (?,?,?,?,?,?,?)");
								$stmt2 -> bind_param('sssssss', $p_mydate,$p_part_sens_name,$p_stime[$x],$p_etime[$x],$p_measurement[$x],$p_record[$x],$p_mylocation);
								$stmt2 -> execute();
								$rows_affected2 = $stmt2 ->affected_rows;
								$stmt2 -> close();
								
								//check if add was successful or not. Tell the user
						   		if($rows_affected2 >= 0){
						   			echo 'You Added New Sensor Info For Daily Data! :'.$p_part_sens_name.'<br>';
								}
								else{
									$commit_check = "false";
									throw new Exception("An Error Has Occurred: No Added Sensor Info");
								}
							}	
						
							else{
								$stmt3 = $dbc -> prepare("UPDATE daily_data2_particle_counter SET start_time = ?, end_time = ?, avg_measurement = ?, record_source = ? WHERE daily_date = ? AND part_sens_name =? AND location = ?");
								$stmt3 -> bind_param('sssssss',$p_stime[$x],$p_etime[$x],$p_measurement[$x],$p_record[$x],$p_mydate,$p_part_sens_name,$p_mylocation);
								$stmt3 -> execute();
								$rows_affected3 = $stmt3 ->affected_rows;
								$stmt3 -> close();
									
								//check if add was successful or not. Tell the user
							   	if($rows_affected3 >= 0){
									echo 'You Updated Sensor Info For Daily Data! : '.$p_part_sens_name.'<br>';
								}else{
									$commit_check = "false";
									throw new Exception("An Error Has Occurred: No Added Sensor Info");
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
<<<<<<< HEAD
			echo "</div>";
=======
			echo '</div>';
>>>>>>> 6e9a045afaca3313c567d518397313af9c1d2d90
		}

	
	?>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
</body>
	
</html>
