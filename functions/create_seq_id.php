<?php
//assumes numbers in project name are always single digit
//grabs first 2 letters of name and then all capital letters. truncates name to be 5 letters (4 if there is a digit)
function create_seq_id($project_name){
		
		$number_check = 'false';
		
		//grep the first two letters of the name
		$regrex_check = '/^([A-Za-z][A-Za-z])/'; 
	    preg_match($regrex_check,$project_name,$matches);
		
		//grep lagging numbers in name
		$regrex_check2 = '/([1-9]*)$/'; 
	    preg_match($regrex_check2,$project_name,$matches2);
		if($matches2[1]){
			$number_check = 'true';
		}
		
		//removes all lowercaase letters and leaves you with just the uppercase
		$cap1 = preg_replace('/[^A-Z]+/', '', $project_name);

		
		$id_start = $matches[1].$cap1.$matches2[1];
	
		//count to make sure length is valid
		$length = strlen($id_start);
		if($length > 5){
			if($number_check == 'true'){
				$id_start = substr($id_start,0, 4); // returns first four characters plus the number
				$id_start = $id_start.$matches2[1];
			}
			else{
				$id_start = substr($id_start,0, 5); // returns first five characters
			}
			
		}
		
		//make them all capital
		$cap_id_start = strtoupper($id_start);
		return $cap_id_start;
	
}
?>