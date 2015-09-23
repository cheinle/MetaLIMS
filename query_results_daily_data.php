<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php'); 
include('index.php');
include('functions/build_daily_data_output.php');
?>

<!doctype html>
<html>
<head>
<title>Query Daily Data</title>
	<meta charset="utf-8">
</head>

 
<body>
<div class="page-header">
<h3>View Daily Data</h3>
</div>

<?php

if(isset($_GET['submit'])){
	
	
		$check_date = 'false';
		$check_field = 'false';
		$query_date = '';
		$query_field = '';
		$stmt = '';
		if(($_GET['smydate'] != NULL) && ($_GET['emydate'] != NULL)){
		
			//sanatize user input to make safe for browser
			$p_smydate = htmlspecialchars($_GET['smydate']);
			$p_emydate = htmlspecialchars($_GET['emydate']);
			
			$query_date = 'daily_data2.daily_date BETWEEN (?) AND (?)';
			$check_date = 'true';
			
		}
		else{
			if(!isset($_GET['db_content'])){
				echo '<script>Alert.render2("ERRORss: No entries found. Please check fields");</script>';
			}
		}
			
		$query_main = "SELECT * FROM daily_data2 JOIN daily_data2_particle_counter ON daily_data2.daily_date = daily_data2_particle_counter.daily_date";
		$query = "";
		//only date is populated
		if(isset($_GET['db_content'])){
			$query = $query_main;
			$stmt = $dbc->prepare($query);
		}
		else{
			$query = $query_main.' WHERE '.$query_date;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('ss',$p_smydate , $p_emydate);
		}
		
		
		if($stmt){
			build_daily_data_output($stmt);
		}
		else{//does this work? 
			echo '<script>Alert.render2("ERROR: No entries found. Please check fields");</script>';
			echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
		}
		

}

?>

</body>
</html>
