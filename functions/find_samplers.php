<?php	
function find_samplers($sample_name,$table_type){ 
	//2015/09/02test_projectA109
	include('config/path.php');
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'database_connection.php');
	
	$stmt = $dbc->prepare("SELECT sampler_name, start_date_time,end_date_time FROM sample_sampler WHERE sample_name = ?");
	if(!$stmt){;
		die('prepare() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt->bind_param("s",$sample_name);
	if ($stmt->execute()){
		$stmt->bind_result($sampler_name,$start,$end);

		$array = array();
		while ($stmt->fetch()) {
			$find_start_time = explode(" ",$start);
			$start_time = $find_start_time[1];
		
			$find_end_time = explode(" ",$end);
			$end_time = $find_end_time[1];

			array_push($array,"$sampler_name $start_time-$end_time");
		}
		natsort($array); //do not loop with index since natsort maintains original indexing
		
		if($table_type == 'display'){ //view as dropdown
			$dropdown = '<select>'."\n";
		  	$dropdown .= '<option>-empty-</option>'."\n";
			foreach($array as $key=>$option) {
	   			  $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
			}
			$dropdown .= '</select>'."\n";
			return $dropdown;
		}
		else{//view as one string
			echo "<td class = 'reg'>";
			echo $string_of_samplers = implode("\n", $array);
			echo "</td>";
		}
	}
	else{
		die('execution() failed: ' . htmlspecialchars($stmt->error));
	}
}
?>
