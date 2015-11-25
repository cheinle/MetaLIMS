<?php
	include ('index.php');
	include ('database_connection.php');
	include ('functions/text_insert_update_storage_info.php');
	include_once("functions/unset_session_vars.php");
	
    //sample names
	//id
	//date
	//submitter
	//db
	//experiment type
	
	$sample_array=$_POST['sample'];
	//$p_id = htmlspecialchars($_POST['dExtKit']);
	$p_date = htmlspecialchars($_POST['subm_date']);
	$p_submitter= htmlspecialchars($_POST['submitter']);
	$p_db= htmlspecialchars($_POST['subm_db']);
	$p_experiment_type= htmlspecialchars($_POST['type_of_experiement']);

	
	//store get variables into session variables so you can use them on your back button
	////
	$_SESSION['submitted'] = 'false';
	$_SESSION['date_submitted'] = $p_date;
	$_SESSION['sample_array'] = $sample_array;
	$_SESSION['subm_date'] = $p_date;
	$_SESSION['submitter'] = $p_submitter;
	$_SESSION['subm_db'] = $p_db;
	$_SESSION['type_of_experiment'] = $p_experiment_type;
	
	
	/////
	
	
	$error = 'false';
	//check that fields have been set
	if($p_date == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Date Not Entered.");</script>';
	}
	if($p_db == '0'){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Submission DB Not Entered.");</script>';
	}
	if($p_submitter == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Submitter Not Entered.");</script>';
	}
	if($p_experiment_type == '0'){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Type Of Experiment Submitted Not Entered.");</script>';
	}
	

	//if there are no errors, proceed to update the samples choosen
	$insert_error = 'false';
	if($error == 'false'){
		echo 'Samples Updated:<br>';
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			
			foreach($sample_array as $sample_name => $subm_id){
				$p_sample_name = htmlspecialchars($sample_name);
				$checkbox = 'false';
				if(isset($subm_id['checkbox'])){
					$checkbox = 'true';
				}
				//if checkbox is checked/true, then go ahead and update this sample :D
				if($checkbox == 'true'){
					$p_subm_id = $subm_id['id'];
					//insert read submission table with sample name, subm_id,subm_db,subm_date,submitter,type_exp
					echo "id:".$p_subm_id.'<br>';
					$stmt = $dbc -> prepare("INSERT INTO read_submission (sample_name,subm_id,subm_db,subm_date,submitter,type_exp) VALUES (?,?,?,?,?,?)");
					$stmt -> bind_param('ssssss',$p_sample_name,$p_subm_id,$p_db,$p_date,$p_submitter,$p_experiment_type);
					
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected >= 0){
						echo 'You Added New Read Submission Info For: '.$p_sample_name.'<br>';
					}else{
						throw new Exception("ERROR: Sample Insert Failure ");
						$insert_error = 'true';
						echo 'An error has occured';
						mysqli_error($dbc);
						
					}
					
				}
			}
			if($insert_error == 'false'){
				$dbc->commit();
				unset_session_vars('bulk_read_insert');
			}
			
		}
		catch (Exception $e) { 
    		if (isset ($dbc)){
       	 		$dbc->rollback ();
       			echo "Error:  " . $e; 
    		}
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
		}
		
	}
	else{
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
	}

?>
