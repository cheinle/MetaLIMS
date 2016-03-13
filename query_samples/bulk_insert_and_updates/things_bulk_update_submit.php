<?php
	include ('../../index.php');
	include ('../../database_connection.php');
	
	//contains sample names and field values
	//you need your thing id
    $sample_array=$_POST['sample'];
	$thing_id = htmlspecialchars($_POST['thing_id']);
	echo '<div class="page-header">
			<h3>Samples Updated</h3>	
			</div>';

	echo '<div class = border>';
	$error = 'false';

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){

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
					$whole_thing = 'thing'.$thing_number;
					
					$thing_set_query = "UPDATE store_user_things SET thing1 = ? WHERE sample_name = ?";
					//$thing_set_query = "UPDATE store_user_things SET ".$whole_thing." = ? WHERE sample_name = ?";

					if($thing_stmt = $dbc ->prepare($thing_set_query)) {                 
	                	if($thing_number > 10 && $thing_number < 16){
							$thing_stmt->bind_param('si',$p_sample_name,$p_thing_value);
						}else{
							$thing_stmt->bind_param('ss',$p_thing_value,$p_sample_name);
						}
						
						if($thing_stmt -> execute()){
						
							$thing_rows_affected = $thing_stmt ->affected_rows;
							$thing_stmt -> close();
							if($thing_rows_affected >= 0){
								echo $p_sample_name."<br>";
							}
							else{
								throw new Exception("Unable To Update User Created Info");	
							}
						}
						else{
							throw new Exception("Execution Error: Unable To Update User Created Info");	
						}
					}else{
						throw new Exception("Unable To Prepare User Created Info");	
					}
				}
			}
			echo '<p><input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" /></p>';
			$dbc->commit();
			echo "Update Complete!";
			
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
	echo '</div>';

?>
