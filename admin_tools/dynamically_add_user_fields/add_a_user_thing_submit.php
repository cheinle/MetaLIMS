<?php
		include ('../../database_connection.php');

try{
						
		//start transaction
		$dbc->autocommit(FALSE);
		$insert_check = 'true';
		
		//define variables
		$p_label_name = htmlspecialchars($_GET['label_text']);
		$p_type = htmlspecialchars($_GET['type']);
		$p_select_values = htmlspecialchars($_GET['options']);
		$p_required = htmlspecialchars($_GET['required']);
	
		//formatting function - uppercase first letter and lowercase all other letters
		function myfunction(&$value,$key){
			$value = ucfirst(strtolower($value));
		}
		
		//format
		if($p_type == 'select'){//|| $p_type == 'numeric_select'
	
			//format all option entries 
			$pieces = preg_split("/\s+/", $p_select_values);
			array_walk($pieces,"myfunction");
			$p_select_values = implode(";", $pieces);

		}
		else{
			$p_select_values = NULL;
		}
		//format all labels 
		$pieces_of_label = preg_split("/\s+/", $p_label_name);
		array_walk($pieces_of_label,"myfunction");
		$p_label_name = implode(" ", $pieces_of_label);
		
		//get number of things
		$stmt= $dbc->prepare("SELECT thing_id FROM create_user_things");
		
		$check_number_of_numeric = array();
		$check_number_of_varchars = array();
		if ($stmt->execute()){
	    	$stmt->bind_result($thing_id);
	    	while ($stmt->fetch()){
	    		
				$regrex_check = '/^thing(\d+)$/'; 
				preg_match($regrex_check,$thing_id,$matches);
	        	if($matches[1] < 11 AND $matches[1] > 0){
	        		$check_number_of_varchars[] = $matches[1];
	        	}
				if($matches[1] < 21 AND $matches[1] > 10){
					$check_number_of_numeric[] = $matches[1];
				}
			}
		} 
		else {
			throw new Exception("Execute Failure: Unable To Select from table");
		}
		$stmt -> close();
		
		//sort($number_of_things);
		//$last_element = end($number_of_things);
		$last_element = '';
		if($p_type == 'numeric_input'){
			sort($check_number_of_numeric);
			$last_element = end($check_number_of_numeric);
			if($last_element >=20){
				throw new Exception("Indexing is out of range. Unable to add more numeric fields. Max input fields is 10");	
			}
		}else{
			sort($check_number_of_varchars);
			$last_element = end($check_number_of_varchars);
			
			if($last_element >=10){
				throw new Exception("Indexing is out of range. Unable to add more input fields. Max input fields 10");	
			}
		}

		$new_thing_id = $last_element + 1;
		$new_thing_id = 'thing'.$new_thing_id;
				
		//insert data into db. Use prepared statement 
		$stmt2 = $dbc -> prepare("INSERT INTO create_user_things (thing_id, label_name, type, select_values,required) VALUES (?,?,?,?,?)");
		if(!$stmt2){
			$insert_check = 'false';
			throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
		}
		else{
			$stmt2 -> bind_param('sssss', $new_thing_id,$p_label_name,$p_type,$p_select_values,$p_required);
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
       		//echo "Error:  " . $e; 
       		header('HTTP/1.0 400 Bad error');
    	}	
}
?>