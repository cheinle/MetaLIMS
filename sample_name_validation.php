<?php
	 include('database_connection.php');

	$sample_name_check = 'false';
	$project_name = htmlspecialchars($_GET['proj']);
	$sample_number = htmlspecialchars($_GET['sa']);
	if(isset($_GET['current_name'])){
		$sample_name = htmlspecialchars($_GET['current_name']);
		$sample_name_check = 'true';
	}
	
	$found = 'false'; //assume you have not found the name
	if($project_name == 0 || $sample_number == ''){
		$found = 'true';	
	}

	if($sample_name_check == 'true'){
		$stmt = $dbc->prepare("SELECT sample_name FROM sample WHERE project_name = ? AND sample_num = ? AND sample_name <> ?");
		$stmt->bind_param("sss", $project_name,$sample_number,$sample_name);		
		if ($stmt->execute()){

			$stmt->store_result();
	    	if($stmt->num_rows > 0){
	    		$found = 'true';	
	    	}else{
	    		$found = 'false';
	    	}
			
		} 
		$stmt -> close();
	}
	else{
		$stmt = $dbc->prepare("SELECT sample_name FROM sample WHERE project_name = ? AND sample_num = ?");
		$stmt->bind_param("ss", $project_name,$sample_number);		
		if ($stmt->execute()){
			$stmt->store_result();
	    	if($stmt->num_rows > 0){
	    		$found = 'true';	
	    	}else{
	    		$found = 'false';
	    	}
		} 
	}
	
	echo $found;


?>




