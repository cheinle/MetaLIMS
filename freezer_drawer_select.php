<?php

	#echo "<select id='$p_select_id' name='$p_select_id' class='fields'>";
	echo "<option value='0'>-Select-</option>";
	$freezer_id = $_GET['Store_temp'];
 	$select_id = $_GET['Store_name'];
	$p_select_id = htmlspecialchars($select_id);
    	
    include('database_connection.php');
	
	$stmt = $dbc->prepare("SELECT freezer_drawer.drawer_id FROM freezer_drawer LEFT JOIN drawer ON (drawer.drawer_id = freezer_drawer.drawer_id) WHERE (freezer_drawer.freezer_id = ?)");
  	$stmt -> bind_param('s', $freezer_id);
  	if ($stmt->execute()){
		if($stmt->fetch()){
			$meta = $stmt->result_metadata(); 
		   	while ($field = $meta->fetch_field()){ 
		        $params[] = &$row[$field->name]; 
		    } 
		
		    call_user_func_array(array($stmt, 'bind_result'), $params); 
		
			$stmt->execute();
			$header_ct = 0;
			
			$array = array();
		    while ($stmt->fetch()) {
				foreach($row as $key => $value){		
					$p_value = htmlspecialchars($value);
					#echo "pvalue:".$p_value.'<br>';
					
					
						echo '<option value='.$p_value.'>'.$p_value.'</option>';
						
				
					$array[] = $p_value;
				}			
			}			
		    $stmt->close();
			
		} 
		else{
			#echo "fetch not working";
		}
	}
	else{
		#echo "boo";
	}
	#echo '</select>';
	
?>