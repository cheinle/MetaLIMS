<?php	

//display table
function get_earliest_date($num_of_air_samplers,$get_array){ //table types are 'dislapy' and 'xls'
	$earliest_start_date;
	$counter = 0;

	for ($x = 1; $x <= $num_of_air_samplers; $x++) {
			
			$start_date = htmlspecialchars($get_array['sdate'.$x]);
			
			if(!(isset($get_array['delete'.$x]))){
				$counter++;
				if($counter == 1){
					$earliest_start_date = $start_date;	
				}else{
					if($start_date < $earliest_start_date){
						$earliest_start_date = $start_date;	
					}	
					
				}
			}
			else{
				continue;
			}
			
	}
	return $earliest_start_date;
}
?>