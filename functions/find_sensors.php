<?php	
function find_sensors($sample_name,$table_type){ 
	//2015/09/02test_projectA109
	$path = $_SESSION['include_path'];
	include($path.'database_connection.php');
	
	//$stmt = $dbc->prepare("SELECT sampler_name, start_date_time,end_date_time FROM sample_sampler WHERE sample_name = ?");
	
	$stmt = $dbc->prepare("SELECT daily_data2_particle_counter.part_sens_name,daily_data2_particle_counter.start_time,daily_data2_particle_counter.end_time,daily_data2_particle_counter.avg_measurement,record_source FROM sample JOIN daily_data2_particle_counter ON DATE(sample.start_samp_date_time) = daily_data2_particle_counter.daily_date and sample.location_name = daily_data2_particle_counter.location WHERE sample.sample_name =?");
	
	if(!$stmt){;
		die('prepare() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt->bind_param("s",$sample_name);
	if ($stmt->execute()){
		$stmt->bind_result($sensor_name,$start,$end,$avg,$record);

		$array = array();
		while ($stmt->fetch()) {
			//$find_start_time = explode(" ",$start);
			//$start_time = $find_start_time[1];
		
			//$find_end_time = explode(" ",$end);
			//$end_time = $find_end_time[1];

			array_push($array,"$sensor_name|$avg|$start-$end|$record");
		}
		natsort($array); //do not loop with index since natsort maintains original indexing
		
		if($table_type == 'display'){ //view as dropdown
			$dropdown = '<select>'."\n";
		  	//$dropdown .= '<option>-empty-</option>'."\n";
			foreach($array as $key=>$option) {
	   			  $dropdown .= '<option value="'.$key.'">'.$option.'</option>'."\n";
			}
			$dropdown .= '</select>'."\n";
			return $dropdown;
		}
		else{//view as one string
			return $string_of_sensors = implode(",", $array);
		}
	}
	else{
		die('execution() failed: ' . htmlspecialchars($stmt->error));
	}
}
?>
