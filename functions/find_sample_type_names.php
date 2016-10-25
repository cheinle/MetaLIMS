<?php	

function find_sample_type_name($sample_type_id,$dbc){ 
	
	$return_name = '';
	$stmt = $dbc->prepare("SELECT sample_type_name FROM sample_type WHERE sample_type_id = ?");
	if(!$stmt){;
		die('prepare() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt->bind_param("s",$sample_type_id);
	if ($stmt->execute()){
		$stmt->bind_result($name);

		$array = array();
		while ($stmt->fetch()) {
			
			$return_name = $name;
			
		}
	}
	
	return $return_name;
		
}
?>
