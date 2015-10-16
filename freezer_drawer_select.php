<?php

	echo "<option value='0'>-Select-</option>";
	$freezer_id = $_GET['Store_temp'];
 	$select_id = $_GET['Store_name'];
	$p_select_id = htmlspecialchars($select_id);
    	
    include('database_connection.php');
	$stmt = $dbc->prepare("SELECT freezer_drawer.drawer_id,freezer_drawer.visible_flag FROM freezer_drawer LEFT JOIN drawer ON (drawer.drawer_id = freezer_drawer.drawer_id) WHERE (freezer_drawer.freezer_id = ?)");
  	$stmt -> bind_param('s', $freezer_id);
  	if ($stmt->execute()){
		$stmt->bind_result($drawer_id,$visible);
		while ($stmt->fetch()){
    		if($visible == 1){
    			echo '<option value="'.$drawer_id.'">'.$drawer_id.'</option>';    		
			}
		}
	} 
	else {
		die('execute() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt -> close();


	
?>