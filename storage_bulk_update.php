<?php
	include ('index.php');
	include ('database_connection.php');
	include_once("functions/unset_session_vars.php");
	
	$error = 'false';
    $sample_array=$_POST['sample_names'];
	$p_sample_type = htmlspecialchars($_POST['sample_type']);
	$p_Store = $_POST['Store_temp'].','.$_POST['Store_name'];
	$p_Store = htmlspecialchars($p_Store);
	
	//store get variables into session variables so you can use them on your back button
	$_SESSION['submitted'] = 'false';
	//$_SESSION['sample_array'] = $sample_array;
	//$_SESSION['sample_type']= $p_sample_type;
	//$_SESSION['Store_temp'] = $_POST['Store_temp'];
	//$_SESSION['Store_name'] = $_POST['Store_name'];
	
	$exists = '';
	

	$query_si;
	
	if($p_sample_type == 'original'){
		$exists = 'true';
		if($p_Store == 'Used,Used'){
			$exists = 'false';
		}
		$query_si = 'UPDATE storage_info SET original = ?, orig_sample_exists = ? WHERE sample_name = ?';		
	}
	else if($p_sample_type == 'dna'){
		$exists = 'one';
		if($p_Store == 'Used,Used'){
			$exists = 'false';
		}
		$query_si = 'UPDATE storage_info SET dna_extr = ?,DNA_sample_exists = ? WHERE sample_name = ?';		
	}
	else if($p_sample_type == 'rna'){
		$exists = 'one';
		if($p_Store == 'Used,Used'){
			$exists = 'false';
		}
		$query_si = 'UPDATE storage_info SET rna_extr = ?, RNA_sample_exists = ? WHERE sample_name = ?';		
	}
	else{
		$error = 'true';
		echo '<script>Alert.render("ERROR: Please Notify Admin");</script>';
	}
	

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){
		echo 'Samples Updated:<br>';
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			
			foreach($sample_array as $sample_name){
				$p_sample_name = htmlspecialchars($sample_name);

			    $getName = $dbc->prepare('SELECT sample_name FROM storage_info WHERE sample_name = ?') or die('Couldn\'t check the name');
				$getName->bind_param('s', $p_sample_name);
				$getName->execute();
				$getName->store_result();
				$countRows = $getName->num_rows;
				$getName->close();

				if($countRows == 1){
						if($stmt_si = $dbc ->prepare($query_si)) {
					    $stmt_si->bind_param('sss', $p_Store,$exists,$p_sample_name); 
	
						$stmt_si -> execute();
						$rows_affected_si = $stmt_si ->affected_rows;
						if($rows_affected_si > 0){
							echo 'SUCCESS: Updated '.$p_sample_name.' in storage info <br>';	
						}
						elseif($rows_affected_si == 0){
							echo "No Update Needed for ".$p_sample_name.'<br>';
						}
						else{
							throw new Exception('An Error Has Occurred In Storing Storage Info For '.$p_sample_name);
						}
					}
					else{
						throw new Exception("ERROR: Storage Insert Prepare Failure");
					}
				}
				else{
					//give warning message instead of inserting here because it should be inserted later. If you see this happening
					//then can check if there is another problem somewhere else
					throw new Exception("ERROR: Sample ".$p_sample_name." Does Not Exist In Storage Info. Please See Admin");
				}
			}
			$dbc->commit();
			unset_session_vars('bulk_storage_update');	
			echo '<p><input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" /></p>';
		}
		catch (Exception $e) { 
    		if (isset ($dbc)){
       	 			$dbc->rollback ();
       				echo "Error:  " . $e; 
    		}
		}
	}
	else{
			echo '<p><input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" /></p>';
	}

?>
