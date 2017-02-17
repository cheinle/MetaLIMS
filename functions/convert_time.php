<?php

function convert_time($keys,$val){
	$hours = '0';
	$minutes = '0';
	if($val){
		
		$time_array = explode(".",$val);
		$hours = $time_array[0];
		if(isset($time_array[1])){
			$minutes = round($time_array[1] /100 * 60);
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
