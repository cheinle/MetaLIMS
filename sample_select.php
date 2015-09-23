<?php
	 include('database_connection.php');

	$project_name = $_GET['projName'];
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
	
	$stmt1 = $dbc->prepare("SELECT sample_name,sample_sort,seq_id FROM sample WHERE project_name = ? AND start_samp_date_time BETWEEN (?) AND (?)");
	$stmt1 -> bind_param('sss', $project_name, $start_date,$end_date);
  	if ($stmt1->execute()){
   		$stmt1->bind_result($sample_name,$sample_sort,$seq_id);
		while ($stmt1->fetch()){
			echo '<label class="checkbox-label"><input type="checkbox" name="sample_names[]" value="'.$sample_name.':'.$sample_sort.':'.$seq_id.'">'.$sample_name.'</label><br>';
		}
	}
	echo "</p>";
?>




