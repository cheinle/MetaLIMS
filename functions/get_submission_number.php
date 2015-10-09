<?php
function get_submission_num($sample_name,$seq_type_abbrev){
	
				include ('database_connection.php');
				$query = '';
				if($seq_type_abbrev == 'A'){
					$query = 'SELECT A FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'C'){
					$query = 'SELECT C FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'E'){
					$query = 'SELECT E FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'MP'){
					$query = 'SELECT MP FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'M'){
					$query = 'SELECT M FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'MT'){
					$query = 'SELECT MT FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'S'){
					$query = 'SELECT S FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'T'){
					$query = 'SELECT T FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'G'){
					$query = 'SELECT G FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				elseif($seq_type_abbrev == 'P'){
					$query = 'SELECT P FROM number_of_seq_submissions WHERE sample_name = ?';
				}
				else{
					return "ERROR";
				}
				
				$stmt= $dbc->prepare("$query");
				$stmt-> bind_param('s', $sample_name);
					
	  			if ($stmt->execute()){
	    			$stmt->bind_result($num);
	    			if ($stmt->fetch()){
	        			RETURN $num;
					}else{
						RETURN "ERROR";
					}
				} 
				else {
					RETURN "ERROR";
				}
				$stmt -> close();
}
?>