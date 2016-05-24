<?php 
include('../index.php');
include('../database_connection.php');
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Storage Update</title>	
</head>

<body>
<div class="page-header">
<h3>Update Storage Info</h3>	
</div>	

	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			if(isset($_GET['oStore_name'])){
				$_SESSION['oStore_name'] = $_GET['oStore_name']; //if use back button, select user selected value
			}
			if(isset($_GET['dStore_name'])){
				$_SESSION['dStore_name'] = $_GET['dStore_name'];
			}
			if(isset($_GET['rStore_name'])){
				$_SESSION['rStore_name'] = $_GET['rStore_name'];
			}
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//check that if freezer is set, the drawer is also set
			if($_GET['oStore_temp'] != '0'){
				if($_GET['oStore_name'] == '0'){
					$error = 'true';
					echo "ERROR:Please Select a Storage Drawer";
				}
			}
			if($_GET['dStore_temp'] != '0'){
				if($_GET['dStore_name'] == '0'){
					$error = 'true';
					echo "ERROR:Please Select a Storage Drawer For Your DNA";
				}
			}
			if($_GET['rStore_temp'] != '0'){
				if($_GET['rStore_name'] == '0'){
					$error = 'true';
					echo "ERROR:Please Select a Storage Drawer For Your RNA";
				}
			}
			
			//sanatize user input to make safe for browser
			$p_sample_name = htmlspecialchars($_GET['sample_name']);
			$oStore = $_GET['oStore_temp'].','.$_GET['oStore_name'];
			$p_oSampStore = htmlspecialchars($oStore);
		
			#$p_rSampStore = htmlspecialchars($_GET['rSampStore']);
			
			$dStore = $_GET['dStore_temp'].','.$_GET['dStore_name'];
			#echo "dstore:".$dStore;
			$p_dExtStore = htmlspecialchars($dStore);
			
			$rExtStore = $_GET['rStore_temp'].','.$_GET['rStore_name'];
			#echo "rExtstore:".$rExtStore;
			$p_rExtStore = htmlspecialchars($rExtStore);
			
		
			
			if($p_sample_name == ''){
					echo '<p>You must enter a Sample Name!<p>';
					$error = 'true';
			}
			if($p_oSampStore == ''){
					echo '<p>You must enter the storage location of the original sample!<p>';
					$error = 'true';
			}

			//insert info into db
		    if($error != 'true'){
		    	try{
					//start transaction (if catch error, roll back)
					$dbc->autocommit(FALSE);
					
					//get timestamp
					$current_time = $_GET['transaction_time']; //sent from page 2
					//echo "current_time".$current_time.'<br>';
					
					//set current timestamp, make sure you are older than the exisiting time stamp
					$ts_set_query = 'UPDATE storage_info SET time_stamp = ? WHERE sample_name = ? AND time_stamp <= ?';
					if($ts_stmt = $dbc ->prepare($ts_set_query )) {                 
	                	$ts_stmt->bind_param('sss',$current_time, $p_sample_name,$current_time);
	
	                    $ts_stmt -> execute();
						$ts_rows_affected = $ts_stmt ->affected_rows;
					
						$ts_stmt -> close();
						if($ts_rows_affected >= 0){
							echo "You updated timestamp".'<br>';
						}
						else{	
							throw new Exception("Unable to update sample. Sample may have been modified since your opened this record. Please reload record, review, and try again");	
						}
					}
					
					//if selection or form is not empty, update those fields for this sample in storage info table
					$data = array(
						array('field' => 'sample_name', 'value' => $p_sample_name, 'type' => 's'),
						array('field' => 'original', 'value' => $p_oSampStore, 'type' => 's'),
						array('field' => 'dna_extr', 'value' => $p_dExtStore, 'type' => 's'),
						array('field' => 'rna_extr', 'value' => $p_rExtStore, 'type' => 's'),
					);
					
					$count = count($data);
	            	if($count > 0) {
	            		
						echo "Update for: ".$p_sample_name.'<br>';
	                	$errors = false;
	                	$query = 'UPDATE storage_info SET ';
	                	foreach($data as $id => $values) {
	                		//if no one has updated the file since you looked at it, go ahead and update the sample!	
	                    	$query2 = $query.$values['field'].' = ? WHERE sample_name = ? AND time_stamp = ?';

	                    	if($stmt = $dbc ->prepare($query2)) {                 
	                        	$types = $values['type'].'s'.'s';//ss is for the p_sample_name type and time stamp
								$stmt->bind_param($types, $values['value'], $p_sample_name, $current_time);
	
	                        	$stmt -> execute();
								$rows_affected = $stmt ->affected_rows;
								$stmt -> close();
					
								//check if add was successful or not. Tell the user
			   					if($rows_affected > 0){
										echo "You updated field ".$values['field']." to :".$values['value'].'<br>';
								}
								elseif($rows_affected == 0){
								#	echo "No update needed for field '".$values['field'].".' Contents are the same:".$values['value'].'<br>';
								}
								else{
									throw new Exception("rows affected is <= 0. ERROR: Your sample has been updated by another user since you began your update. Please refresh your page to view updated information and try again.");	
								}
	            	
							}
							else{
								throw new Exception("Unable to prepare query for db");
							}
						 
						}
						//if you haven't thrown an error and exited the script by now, you can go ahead and commit 
						$dbc->commit();
						$submitted = 'true';
						unset($_SESSION['oStore_name']);
						unset($_SESSION['dStore_name']);
					}
					else{
						throw new Exception("An error has occurred: Please populate a field to update");
					}
				}
				catch (Exception $e) { 
    				if (isset ($dbc)){
    					echo '<script>Alert.render("ERROR:All Updates Rolled Back. Not Changes Made. Please See Error Messages");</script>';
       	 				$dbc->rollback ();
       					echo "Error:  " . $e; 
    				}
				}
			}
			echo '</div>';
		}
		
	?>

<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
<button class="button" type=button onClick="parent.location='<?php echo $root;?>update_storage/update_storage_info.php'" value='update'>Update Another Sample</button>
	

</body>
</html>	
