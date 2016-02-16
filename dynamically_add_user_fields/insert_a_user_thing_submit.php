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
				
		//insert data into db. Use prepared statement 
		$stmt2 = $dbc -> prepare("INSERT INTO create_user_things (label_name,type,select_values) VALUES (?,?,?)");
		if(!$stmt2){
			$insert_check = 'false';
			throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
		}
		else{
			$stmt2 -> bind_param('sss', $p_label_name,$p_type,$p_select_values);
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
			echo 'Data Submitted Successfully!<br>';
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