<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php'); 
include('../index.php');
?>

<!doctype html>
<html>
<head>
<title>Query Pooled Samples Results</title>
	<meta charset="utf-8">
</head>

 
<body>
<div class="page-header">
<h3>Query Pooled Samples</h3>
</div>
<?php

if(isset($_GET['submit'])){
	include('../functions/check_box_tables_output.php');
	include('../functions/white_list.php');
	include('../functions/build_pooled_samp_output.php');
	
	$submit = $_GET['submit'];
	
	//sample
	if($submit == 'p_sample'){
		$check_date = 'false';
		$check_field = 'false';
		$query_date = '';
		$query_field = '';
		$stmt = '';
		if(($_GET['smydate'] != NULL) && ($_GET['emydate'] != NULL)){
		
			//sanatize user input to make safe for browser
			$p_smydate = htmlspecialchars($_GET['smydate']);
			$p_emydate = htmlspecialchars($_GET['emydate']);
			
			//format date
			$regrex_check = '/^(20[0-9][0-9])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
			preg_match($regrex_check,$p_smydate,$matches);
			$p_smydate = $matches[1].'/'.$matches[2].'/'.$matches[3];
			preg_match($regrex_check,$p_emydate,$matches2);
			$p_emydate = $matches2[1].'/'.$matches2[2].'/'.$matches2[3];
			
			$query_date = ' date_entered BETWEEN (?) AND (?)';
			$check_date = 'true';
		}
			
		if(($_GET['sample_name'] != '')){
			$p_sample_name = htmlspecialchars($_GET['sample_name']);
			$query_field = 'new_pooled_samp_name = ? OR orig_sample_name = ?';
			$check_field = 'true';
		}
				
		
		if(isset($_GET['db_content'])){
			$query = "SELECT * FROM pooled_sample_lookup";
			$stmt = $dbc->prepare($query);
			if($stmt){
				build_pooled_samp_output($stmt);
			}
		}
		else{
			$query_main = "SELECT * FROM pooled_sample_lookup WHERE ";
			$query = "";
		
			if($check_field == 'true' && $check_date == 'false'){//only query field populated
				$query = $query_main.$query_field;
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('ss', $p_sample_name,$p_sample_name);
				if($stmt){
					build_pooled_samp_output($stmt);
				}
			}
			elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
				$query = $query_main.$query_date;
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('ss',$p_smydate , $p_emydate);
				if($stmt){
					build_pooled_samp_output($stmt);
				}
			}
			else{
				echo '<script>Alert.render2("ERROR: No entries found. Please check fields");</script>';
				echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
			}
		}
		

		
	}
}

?>

</body>
</html>
