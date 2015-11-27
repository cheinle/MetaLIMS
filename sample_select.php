<?php
	include('database_connection.php');
	 
	$project_name = $_GET['projName'];
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];

	$sort_the_samples = array();
	$sample_map = array();
	$stmt1 = $dbc->prepare("SELECT sample_name,sample_sort,seq_id FROM sample WHERE project_name = ? AND start_samp_date_time BETWEEN (?) AND (?)");
	$stmt1 -> bind_param('sss', $project_name, $start_date,$end_date);
  	if ($stmt1->execute()){
   		$stmt1->bind_result($sample_name,$sample_sort,$seq_id);
		while ($stmt1->fetch()){
			$sort_the_samples[$sample_sort] = $sample_name;
			$sample_map[$sample_name] = $sample_name.':'.$sample_sort.':'.$seq_id;
		}
	}
	
	ksort($sort_the_samples);
	foreach ($sort_the_samples as $sorted_name => $sname) {
		echo '<label class="checkbox-label"><input type="checkbox" name="sample_names[]" value="'.$sample_map[$sname].'">'.$sname.'</label><br>';
	}
	
	echo "</p>";
?>









