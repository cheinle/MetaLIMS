<?php
		include ('../database_connection.php');

try{
						
		//start transaction
		$dbc->autocommit(FALSE);
		$insert_check = 'true';
		//sanatize user input to make safe for browser
		$p_label_name = htmlspecialchars($_GET['label_text']);
		$p_type = 'text_input';
		$p_select_values = NULL;
		
		//get number of things and increment by one
		$number_of_things = array();
		$stmt= $dbc->prepare("SELECT thing_id FROM create_user_things");
		if ($stmt->execute()){
	    	$stmt->bind_result($thing_id);
	    	while ($stmt->fetch()){
	        	$number_of_things[] = $thing_id;
			}
		} 
		else {
			throw new Exception("Execute Failure: Unable To Select from table");
		}
		$stmt -> close();
		
		sort($number_of_things);
		$last_element = end($number_of_things);
		$new_thing_id = $last_element + 1;
		print_r($number_of_things);
		echo $new_thing_id;
				
		//insert data into db. Use prepared statement 
		$stmt2 = $dbc -> prepare("INSERT INTO create_user_things (thing_id, label_name, type, select_values) VALUES (?,?,?,?)");
		if(!$stmt2){
			$insert_check = 'false';
			throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
		}
		else{
			$stmt2 -> bind_param('isss', $new_thing_id,$p_label_name,$p_type,$p_select_values);
			if(!$stmt2 -> execute()){
				$insert_check = 'false';
				throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
			}
			else{
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
			}
		}
		
		
		
		/*****************************************************************************
		 * Do One Last Check And Commit If You Had No Errors
		* ***************************************************************************/
						
		if($insert_check == 'true'){
			$dbc->commit();
			echo 'Data Submitted Successfully!';
			$submitted = 'true';					
		}
		else {
			throw new Exception("Final Error: Unable To Insert Info To DB. No Changes Made");		
		}
}
catch (Exception $e) { 
		if (isset ($dbc)){
       	 	$dbc->rollback ();
       		echo "Error:  " . $e; 
    	}	
}
?>