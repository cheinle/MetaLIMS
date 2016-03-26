<?php

function check_box(){
		include ($_SESSION['include_path'].'database_connection.php');
		//include_once('../convert_header_names.php');
		$array = array();
		$stmt = $dbc->prepare("SELECT * FROM sample");//note: this is not actually protecting anything because no 'data' and no bind result
		if ($stmt->execute()){
		
			if($stmt->fetch()){
				$meta = $stmt->result_metadata(); 
			   	while ($field = $meta->fetch_field()){ 
			        $params[] = &$row[$field->name]; 
			    } 
				sort($params);
				call_user_func_array(array($stmt, 'bind_result'), $params); 

		
				$counter=0;
				$counter1=0;
				echo '<div style="display:inline;padding-right:2px">';
			    
				
				$row2 = array();
				foreach($row as $key => $value){
							$ckey = convert_header_names($key);
							$row2[$ckey] = $key;
				}
				
				ksort($row2);//don't sort names, use order in db
				$row2 = array('All' => 'All') + $row2;
				
				$row2['All'] = 'All';
			    while ($stmt->fetch()) {
			    	if($counter1 == 0){
						foreach($row2 as $key => $value){
							$id = htmlspecialchars($value);
							#$key = convert_header_names($key);	
							if($key == 'false'){ //skip certain columns
								continue;
							}	
							$p_name = htmlspecialchars($key);
							if($counter < 6){
								?>
								<label class="sm-checkbox"><input type="checkbox" class="sm-checkbox" name="column_names[]" value="<?php echo $id; ?>"><?php echo $p_name; ?></label>
								<?php
								$counter++;
							}
							else{
								?>						
								<label class="sm-checkbox"><input type="checkbox" class="sm-checkbox" name="column_names[]" value="<?php echo $id; ?>"><?php echo $p_name; ?></label>
								<?php
								$counter = 0;
							}		
						}
						$counter1++;
					}
				}

			}		
			$stmt->close();
			echo '</div>';
	}
}
?>
		
