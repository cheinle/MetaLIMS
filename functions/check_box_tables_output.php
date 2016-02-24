<?php
//cat together field names to be displayed
function check_box_results($column_names){
			
			//check that column name exists in white list
			include_once('../functions/white_list.php');

     		$field_names = '';
			//always include sample name and include it at the beginning
			array_unshift($column_names,'sample_name');
     		foreach($column_names as $columns){
        		if(empty($field_names)){
            		$check = whiteList($columns,'column'); 
					if($check == 'true'){
						$field_names.="sample.$columns";
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
            			$field_names.=", sample.$columns";
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

