<?php 
if(!isset($_SESSION)) { session_start(); }
include($_SESSION['include_path'].'database_connection.php');

		$submitted = 'false';
		//error checking 
		//if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input and check for required fields
			$p_mydate = htmlspecialchars($_GET['mydate']);
			$p_mylocation = htmlspecialchars($_GET['loc']);
			$p_notes = htmlspecialchars($_GET['notes']);

			//arrays			
			$my_sensors = $_GET['my_sensors'];
			$start_times = $_GET['start_times'];
			$end_times = $_GET['end_times'];
			$measurement = $_GET['measurement'];
			$record = $_GET['record'];

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
				
				$p_stime[$x] = htmlspecialchars($start_times[$x]);
				$p_etime[$x] = htmlspecialchars($end_times[$x]);
				$p_measurement[$x] = htmlspecialchars($measurement[$x]);
				$p_record[$x] = htmlspecialchars($record[$x]);	
						
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
						//$p_part_sens_name = $my_sensors['sensor'.$x];
						$p_part_sens_name = $my_sensors[$x];
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
						throw new Exception("An Error Has Occurred: Was Not Able to Find Samples For This Date");
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
			
		//}
		
?>
