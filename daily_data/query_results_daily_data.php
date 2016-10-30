<?php 
include('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php'); 
include('../functions/build_daily_data_output.php');
?>

<!doctype html>
<html>
<head>
<title>View Daily Sensor Data</title>
	<meta charset="utf-8">
</head>

 
<body>
<div class="page-header">
<h3>View Daily Sensor Data</h3>
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
	
		if($_GET['loc'] != '0'){
			$p_mylocation = htmlspecialchars($_GET['loc']);
			$query_field = 'daily_data2.location = ?';
			$check_field = 'true';
		}
		
		$query_main = "SELECT * FROM daily_data2 JOIN daily_data2_particle_counter ON daily_data2.daily_date = daily_data2_particle_counter.daily_date ";
		$query = "";

		if(isset($_GET['db_content'])){ //display all
			$query = $query_main;
			$stmt = $dbc->prepare($query);
		}
		elseif($check_field == 'true' && $check_date == 'false'){//only location populated
			$query = $query_main." WHERE ".$query_field;
			$query_add = $query_field;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('s', $p_mylocation);
		}
		elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
			$query = $query_main." WHERE ".$query_date;
			$query_add = $query_date;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('ss',$p_smydate , $p_emydate);
		}
		elseif ($check_field == 'true' && $check_date == 'true') {//date and query fields are populated
			$query = $query_main." WHERE ".$query_field.' AND '.$query_date;
			$query_add = $query_field.' AND '.$query_date;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('sss', $p_mylocation, $p_smydate , $p_emydate);
		}
		else{
			echo '<script>Alert.render("ERROR: No Entries Found. Please Check Fields");</script>';
			echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
		}
		
		
		if($stmt){
			build_daily_data_output($stmt);
		}
		else{
			echo '<script>Alert.render2("ERROR: No Entries Found. Please Check Fields");</script>';
			echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
		}
		

}

?>

</body>
</html>
