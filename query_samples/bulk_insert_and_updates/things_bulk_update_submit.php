<?php
	include ('../../index.php');
	include ('../../database_connection.php');
	
	//contains sample names and field values
	//you need your thing id
    $sample_array=$_POST['sample'];
	$thing_id = htmlspecialchars($_POST['thing_id']);


	$error = 'false';

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){
		echo 'Samples Updated:<br>';
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			
			foreach($sample_array as $sample_name => $value){
				
				$checkbox = 'false';
				if(isset($value['checkbox'])){
					$checkbox = 'true';
				}
				//if checkbox is checked/true, then go ahead and update this sample :D
				if($checkbox == 'true'){
					
					//$p_sample_name = htmlspecialchars($value['checkbox']);
					$p_sample_name = $sample_name;
					//echo $p_sample_name;
					$p_thing_value = htmlspecialchars($value['thing']);
					
					//grab the thing number
					//build thing query and then depending on number, set the type
					$regrex_check = '/^thing([0-9]{1,2})$/'; 
					$preg_match = preg_match($regrex_check,$thing_id,$matches);
					if($preg_match == false){
						echo "Matching Error. Please Notify Admin";
						throw new Exception("Matching Error. Please Notify Admin");	
					}
					$thing_number = $matches[1];
					
					$thing_set_query = 'UPDATE store_user_things SET thing'.$thing_number.' = ? WHERE sample_name = ?';
					//echo $thing_set_query;

					//$thing_set_query = 'UPDATE store_user_things SET sample_name = ?, thing1 = ?, thing2 = ?,thing3 = ?, thing4 = ?,thing5 = ?,thing6 =?, thing7 = ?, thing8 =?, thing9 = ?, thing10 =?, thing11 = ?, thing12 = ?, thing13 = ?, thing14 = ?, thing15 = ? WHERE sample_name = ?';
					if($thing_stmt = $dbc ->prepare($thing_set_query)) {                 
	                	//$thing_stmt->bind_param('sssssssssssiiiiis',$p_sample_name,$p_thing1,$p_thing2,$p_thing3,$p_thing4,$p_thing5,$p_thing6,$p_thing7,$p_thing8,$p_thing9,$p_thing10,$p_thing11,$p_thing12,$p_thing13,$p_thing14,$p_thing15,$p_sample_name);
						if($thing_number > 10 && $thing_number < 16){
							$thing_stmt->bind_param('si',$p_sample_name,$p_thing_value);
						}else{
							$thing_stmt->bind_param('ss',$p_sample_name,$p_thing_value);
						}
						
						if($thing_stmt -> execute()){
						
							$thing_rows_affected = $thing_stmt ->affected_rows;
							$thing_stmt -> close();
							if($thing_rows_affected >= 0){
								echo $thing_set_query.$p_sample_name.$p_thing_value."<br>";
							}
							else{
								throw new Exception("Unable To Update User Created Info");	
							}
						}else{
							throw new Exception("Execution Error: Unable To Update User Created Info");	
						}
					}else{
						throw new Exception("Unable To Prepare User Created Info");	
					}
				}
			}
			echo '<p><input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" /></p>';
		    echo "</div>";
			$dbc->commit();
			echo "Update Complete!";
			//unset_session_vars('bulk_thing_update');
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
