<?php
//cat together field names to be displayed
function check_box_results($column_names){
			
			//check that column name exists in white list
			include_once('functions/white_list.php');

     		$field_names = '';
			//always include sample name and include it at the beginning
			array_unshift($column_names,'sample_name');
     		foreach($column_names as $columns){
        		if(empty($field_names)){
            		$check = whiteList($columns,'column'); 
					if($check == 'true'){
						if($columns == 'iso_coll_temp' || $columns == 'iso_date' || $columns == 'iso_store_date' || $columns == 'closest_hit' || $columns == 'seq_sang' || $columns == 'send_pac_bio' || $columns == 'loc_type'){
							$field_names.="isolates.$columns";
						}
						else{
							$field_names.="sample.$columns";
						}
            			
					}
					//if user choose 'all', exit and return all columns
					if($columns == 'All'){
						$field_names = '*';
						return $field_names;
					}
         		}
         		else{
            		$check = whiteList($columns,'column');
					if($check == 'true'){
            			$field_names.=", $columns";
					}
					//if user choose 'all', exit and return all columns
					if($columns == 'All'){
						$field_names = '*';
						return $field_names;
					}
					
         		}
				if($columns == 'Sampling'){
						$field_names = '*';
						return $field_names;
					}
			}
			
			//return list of field_names
			return $field_names;
	}		
?>

