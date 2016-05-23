<?php

function convert_time($keys,$val){
	$hours = '0';
	$minutes = '0';
	if($val){
		$regrex_check = '/^(\d+)(\.+\d{1}(?:\d{1})?)\d+$/'; //remove dashes
		$regrex_check2 = '/^(\d+)$/'; //remove dashes
		if(preg_match($regrex_check,$val,$matches)){
			$hours = $matches[1];
			$minutes = $matches[2] * 60;
			$regrex_check3 = '/^(\d+)\..*?$/';
			if(preg_match($regrex_check3,$minutes,$matches)){
				$minutes = $matches[1];
			}
							
		}
		elseif(preg_match($regrex_check2,$val,$matches)){
			$hours = $matches[1];					
		}
		else{
			/*******************/					
		}

		$time = $hours.'h'.$minutes.'m';
		return $time;
	}
	else{
		$time = $hours.'h'.$minutes.'m';
		return $time;
	}
				
	
}
					

?>
