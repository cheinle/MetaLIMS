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
		if($p_type == 'text_select'){//|| $p_type == 'numeric_select'
	
			//format all option entries 
			$pieces = preg_split("/\s+/", $p_select_values);
			array_walk($pieces,"myfunction");
			$p_select_values = implode(";", $pieces);
	
			/*if($p_type == 'numeric_select'){
				//check that numbers are correct format
				//your type will not be a decimal though...?
			}*/
		}
		else{
			$p_select_values = NULL;
			/*if($p_type == 'numeric_input'){//would not need to check this...only if select numeric or when user enteres
				//check that numbers are correct format
				$regrex_check_fr  = '/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*$/';
				if (!preg_match("$regrex_check_fr", $get_array['fRate'])){
					echo '<p>ERROR: You Must Enter Valid Flow Rate. Please Check Your Number.<p>';
					$error = 'true';
				}
			}*/
			
		}
		//format all labels 
		$pieces_of_label = preg_split("/\s+/", $p_label_name);
		array_walk($pieces_of_label,"myfunction");
		$p_label_name = implode(" ", $pieces_of_label);
		
		//get number of things and increment by one
		//$number_of_things = array();
		$stmt= $dbc->prepare("SELECT thing_id FROM create_user_things");
		
		$check_number_of_numeric = array();
		$check_number_of_varchars = array();
		if ($stmt->execute()){
	    	$stmt->bind_result($thing_id);
	    	while ($stmt->fetch()){
	    		
				#echo $thing_id;
				$regrex_check = '/^thing(\d+)$/'; //remove dashes
				preg_match($regrex_check,$thing_id,$matches);
	        	//$number_of_things[] = $matches[1];
	        	if($matches[1] < 11 AND $matches[1] > 0){
	        		$check_number_of_varchars[] = $matches[1];
	        	}
				if($matches[1] < 16 AND $matches[1] > 10){
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
			
			if($last_element > 15){
				throw new Exception("Indexing is out of range. Unable to add more numeric fields. Max input fields is 5");	
			}
		}else{
			sort($check_number_of_varchars);
			$last_element = end($check_number_of_varchars);
			if($last_element > 10){
				throw new Exception("Indexing is out of range. Unable to add more input fields. Max input fields 10");	
			}
		}

		$new_thing_id = $last_element + 1;
		$new_thing_id = 'thing'.$new_thing_id;
		#print_r($number_of_things);
		#echo $new_thing_id;
				
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
		
		//double check that thingid  is really just 'thing#'
		/*$thing_check_regrex = '/^thing(\d+)$/'; //remove dashes
		$thing_type_check = preg_match($thing_check_regrex,$new_thing_id);
		if($thing_type_check == true && $insert_check == 'true'){
			//check which type of table you have
			$field_type = 'varchar(150)'; //default
			if($p_type == 'numeric_insert'){
				$field_type = 'decimal(5,2)';
			}
			
			//create column in store_user_things
			$stmt3 = $dbc -> prepare("ALTER TABLE store_user_things ADD $new_thing_id $field_type");
			if(!$stmt3){
				$insert_check = 'false';
				throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
			}
			else{
				if(!$stmt3 -> execute()){
					$insert_check = 'false';
					throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
				}
				else{
					$rows_affected3 = $stmt3 ->affected_rows;
					$stmt3 -> close();
				}
			}
		}
		else{
			$insert_check = 'false';
		}
		*/
				
		
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