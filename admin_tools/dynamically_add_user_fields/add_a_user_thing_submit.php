<?php
		if(!isset($_SESSION)) { session_start(); }
		$path = $_SESSION['include_path']; //same as $path
		include ($path.'/functions/admin_check.php');
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
	
		//formatting function - uppercase first letter and lowercase all other letters. trim whitespace
		function myfunction(&$value,$key){
			$value = ucfirst(strtolower($value));
			$value = trim($value);
		}
		
		//format
		if($p_type == 'select'){//|| $p_type == 'numeric_select'
	
			//format all option entries 
			//$pieces = preg_split("/\s+/", $p_select_values);
			$pieces = preg_split("/;/", $p_select_values);
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
		
		//$check_label_exists = 'false';
		//Check if label name exists already
		$stmt= $dbc->prepare("SELECT label_name FROM create_user_things WHERE label_name = ?");
		$stmt-> bind_param('s', $p_label_name);
		if ($stmt->execute()){
	    	$stmt->bind_result($label_name_check);
	    	if ($stmt->fetch()){
	    		//$check_label_exists = 'true';
	    		throw new Exception("Label $p_label_name already exists.");
			}
		} 
		else {
			throw new Exception("Execute Failure: Unable To Select from table");
		}
		$stmt -> close();
		

		//insert data into db. Use prepared statement 
		#thing_id will autoincrement
		$stmt2 = $dbc -> prepare("INSERT INTO create_user_things (label_name, type, select_values,required) VALUES (?,?,?,?)");
		if(!$stmt2){
			$insert_check = 'false';
			throw new Exception("Prepare Failure: Unable To Insert Into Table");	
		}
		else{
			$stmt2 -> bind_param('ssss',$p_label_name,$p_type,$p_select_values,$p_required);
			if(!$stmt2 -> execute()){
				$insert_check = 'false';
				throw new Exception("Execution Failure: Unable To Insert Into Table");
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