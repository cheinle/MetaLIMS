<?php	
function find_thing_labels(){ 

	$path = $_SESSION['include_path'];
	include($path.'database_connection.php');
	
	$thing_label_array = array();
	$stmt = $dbc->prepare("SELECT thing_id,label_name FROM create_user_things");
	if(!$stmt){;
		die('prepare() failed: ' . htmlspecialchars($stmt->error));
	}
	//$stmt->bind_param("s",$sample_name);
	if ($stmt->execute()){
		$stmt->bind_result($thing_id,$label_name);

		$array = array();
		while ($stmt->fetch()) {
			
			$thing_label_array[] = $thing_id.'|'.$label_name;
			
		}
	}
	
	return $thing_label_array;
		
}

function find_thing_values($sample_name,$thing_id){ 
	$path = $_SESSION['include_path'];
	include($path.'database_connection.php');
	
	$thing_value = '-';
	$thing_label_array = array();
	$stmt = $dbc->prepare("SELECT thing_value FROM thing_storing WHERE sample_name = ? AND thing_id = ?");
	if(!$stmt){;
		die('prepare() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt->bind_param("si",$sample_name,$thing_id);
	if ($stmt->execute()){
		$stmt->bind_result($new_thing_value);

		while ($stmt->fetch()) {
			
			 $thing_value = $new_thing_value;
			
		}
	}
	return $thing_value;
		
}
?>
