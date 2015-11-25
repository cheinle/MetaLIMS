<?php
	include ('index.php');
	include ('database_connection.php');
	#include ('functions/text_insert_update_storage_info.php');
	include_once("functions/unset_session_vars.php");
	
	$sample_array=$_POST['sample'];

	
	//store get variables into session variables so you can use them on your back button
	////seems to be working on back button without setting these
	/*$_SESSION['sample_array']['submitted'] = 'false';
	$_SESSION['sample_array']['date_submitted'] = $p_date;
	$_SESSION['sample_array']['sample_array'] = $sample_array;
	$_SESSION['sample_array']['subm_date'] = $p_date;
	$_SESSION['sample_array']['submitter'] = $p_submitter;
	$_SESSION['sample_array']['subm_db'] = $p_db;
	$_SESSION['sample_array']['type_of_experiment'] = $p_experiment_type;
	
	*/
	/////
	
	
	//if there are no errors, proceed to update the samples choosen
	$insert_error = 'false';
		echo 'Samples Updated:<br>';
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			$update_delete_error = 'false';
			foreach($sample_array as $sample_name => $subm_id){
				#print_r($subm_id);
				$p_sample_name = htmlspecialchars($sample_name);
				$p_subm_id = $subm_id['id'];
				$old_id = $_SESSION[$p_sample_name]['old_id'];//test
				#echo $old_id;
				
				$explode = explode(",",$sample_name);
				$p_sample_name = $explode[0];
				
				if(isset($subm_id['checkbox']) && $subm_id['checkbox'] == 'update'){
					$p_date = htmlspecialchars($subm_id['subm_date']);
					$p_submitter= htmlspecialchars($subm_id['submitter']);
					$p_db= htmlspecialchars($subm_id['subm_db']);
					$p_experiment_type= htmlspecialchars($subm_id['type_of_experiement']);
					#echo $p_date.'<br>'.$p_db.'<br>'.$p_submitter.'<br>'.$p_experiment_type.'<br>';
					
					//check that fields have been set
					if($p_date == ''){
						echo '<script>Alert.render("ERROR: Date Not Entered.");</script>';
						throw new Exception("ERROR: Date Not Entered");
					}
					if($p_db == '0'){
						echo '<script>Alert.render("ERROR: Submission DB Not Entered.");</script>';
						throw new Exception("ERROR: Submission DB Not Entered.");
					}
					if($p_submitter == ''){
						echo '<script>Alert.render("ERROR: Submitter Not Entered.");</script>';
						throw new Exception("ERROR: Submitter Not Entered");
					}
					if($p_experiment_type == '0'){
						echo '<script>Alert.render("ERROR: Type Of Experiment Submitted Not Entered.");</script>';
						throw new Exception("ERROR: Type Of Experiment Submitted Not Entered");
					}

					$stmt = $dbc -> prepare("UPDATE read_submission SET 
																	subm_id = ?,
																	subm_db = ?,
																	subm_date = ?,
																	submitter = ?,
																	type_exp = ?
																	WHERE sample_name = ? AND subm_id = ? ");
					$stmt -> bind_param('sssssss', $p_subm_id,													
															$p_db,
															$p_date,
															$p_submitter,
															$p_experiment_type,
															$p_sample_name,
															$old_id
															);
					
					$stmt -> execute();
					$rows_affected = $stmt ->affected_rows;					
					$stmt -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected >= 0){
						echo "Updated: ".$p_sample_name.'<br>';
					}else{
						$update_delete_error == 'true';
						echo 'An error has occured';
						throw new Exception("An Error Has Occured: No Update/Delete");
					}
				
				}
				elseif(isset($subm_id['checkbox']) && $subm_id['checkbox'] == 'delete'){
					
					$stmt_d = $dbc -> prepare("DELETE FROM read_submission WHERE sample_name = ? AND subm_id = ?");
					$stmt_d->bind_param('ss',$p_sample_name,$old_id);
					$stmt_d->execute();
					$rows_affected_d = $stmt_d ->affected_rows;
					//check if add was successful or not. Tell the user
					if($rows_affected_d >= 0){
						echo "Deleted: ".$p_sample_name.'<br>';
					}
					else{
						$update_delete_error == 'true';
						echo 'An error has occured';
						throw new Exception("An error has occred: No Added Sensor Info");		
					}
					$stmt_d->close();
					
				}
				else{
					#echo "Noting To Do For:".$p_sample_name.'<br>';
				}
				
					
			}
			if($update_delete_error == 'false'){
				$dbc->commit();
				unset_session_vars('bulk_read_insert');
				echo '<p><input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" /></p>';
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
		

?>
