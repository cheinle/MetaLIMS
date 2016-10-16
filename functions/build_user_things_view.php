<?php	

//display table
function build_user_things_view($stmt,$root){
	
	
	//need to change so you grab all the things in the create_user_things and all the labels,

	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'functions/convert_time.php');
	include($path.'functions/convert_header_names.php');
	include($path.'functions/find_samplers.php');
	
	$map_label_values = array();
	if ($stmt->execute()){
		$stmt->bind_result($label_name,$sample_name,$thing_id,$thing_value);
	 			
			    while ($stmt->fetch()){
		    		//echo $label_name."-".$sample_name."-".$thing_id."-".$thing_value."<br>";
					$map_label_values[$sample_name.$thing_id]['id'] = $thing_id;
					$map_label_values[$sample_name.$thing_id]['label'] = $label_name;
					$map_label_values[$sample_name.$thing_id]['value'] = $thing_value;
					$map_label_values[$sample_name.$thing_id]['sample_name'] = $sample_name;
				}

	}	
	$stmt->close();
	
	echo '<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">';
	echo "<thead>";
	echo "<tr>";
	echo "<th>Sample Name</th>";
	$counter = 0;
	foreach($map_label_values as $key => $value){
		//echo $key.'-'.$value['id'].'<br>';
		//if($counter == 0){
			echo "<th>".$value['label']."</th>";
		//}
		//$counter++;
	}
	echo "</tr>";
	echo "</thead>";
	
	echo "<tbody>";
	echo "<tr>";
	echo "<td class=\"reg\">".$value['sample_name']."</td>";
	foreach($map_label_values as $key => $value){
		//echo $key.'-'.$value['id'].'<br>';
		echo "<td class=\"reg\">".$value['value']."</td>";
	}
	echo "</tr>";	
	echo "</tbody>";
	echo "</table>";
}		
?>