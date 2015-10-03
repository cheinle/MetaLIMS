<?php

function convert_time($keys,$val){
	#echo "keys:".$keys." vals ".$val.'<br>';
	$hours = '0';
	$minutes = '0';
	if($val){
		$regrex_check = '/^(\d+)(\.+\d{1}(?:\d{1})?)\d+$/'; //remove dashes
		$regrex_check2 = '/^(\d+)$/'; //remove dashes
		if(preg_match($regrex_check,$val,$matches)){
			#echo "Matches1: ".$matches[1].' '.$matches[2].'<br>';
			$hours = $matches[1];
			$minutes = $matches[2] * 60;
			#echo "mintues:".$minutes.'<br>';
			$regrex_check3 = '/^(\d+)\..*?$/';
			if(preg_match($regrex_check3,$minutes,$matches)){
				#echo "Matches3:".$matches[1].'<br>';
				$minutes = $matches[1];
			}
							
		}
		elseif(preg_match($regrex_check2,$val,$matches)){
			#echo "Matches2: ".$matches[1].'<br>';
			$hours = $matches[1];					
		}
		else{
			/*******************/					
		}
		#echo '<td class = "reg">'.$hours.'h'.$minutes.'m'.'</td>';
		#echo $hours.'hrs'.$minutes.'m'.'<br>';
		//echo '<td class = "reg">'.$hours.'h'.$minutes.'m'.'</td>';
		$time = $hours.'h'.$minutes.'m';
		return $time;
	}
	else{
		#echo '<td class = "reg">'.$hours.'h'.$minutes.'m'.'</td>';
		#echo $hours.'h'.$minutes.'m'.'<br>';
		//echo '<td class = "reg">'.$hours.'h'.$minutes.'m'.'</td>';
		$time = $hours.'h'.$minutes.'m';
		return $time;
	}
				
	
}
					

?>
