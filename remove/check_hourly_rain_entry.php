<?php

//this is a funciton to check if hourly rain entry names are valid
//copied quickly off of collector function, so some names have not been changed yet
function check_hourly_rain_entry($arr,$true,$regrex_check){
	$cat_name='';
	$cat_count = 0;
					
	$array = array_filter($arr);
	if(empty($array) && $true == 'false'){//ok to be empty on update
		return array(
			'boolean'    => 'true',
	    	'cat_name' => $cat_name,
	    	'cat_count' => $cat_count
  		);
	}
	elseif (empty($array) && $true == 'true') {
		return array(
			'boolean'   => 'false',
			'cat_name' => $cat_name,
			'cat_count' => $cat_count
		);
	}
	else{
		foreach($array as $collector){
			$p_collector = htmlspecialchars($collector);
			
			#check if valid entry
			if (!preg_match("$regrex_check", $p_collector)){
				echo "coll:".$p_collector.'<br>';		
				 return array(
					'boolean'    => 'false',
					'cat_name' => $cat_name,
					'cat_count' => $cat_count
					);
			} 
			else {
				if(strlen($p_collector)<=0){
					return array(
						'boolean'    => 'false',
						'cat_name' => $cat_name,
						'cat_count' => $cat_count
					);
				}
				else{
					if($cat_count == 0){
						$cat_name = $p_collector;
						$cat_count++;
					}
					else{
						$cat_name .= ','.$p_collector;
						$cat_count++;
					}
				} 
			}
		}
		#echo "cat names:".$cat_name; 
		return array(
				'boolean'    => 'true',
				'cat_name' => $cat_name,
				'cat_count' => $cat_count
		);
	}
}

?>
