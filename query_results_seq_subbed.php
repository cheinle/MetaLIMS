<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php'); 
include('index.php');
include('functions/build_seq_subbed_output.php');
?>

<!doctype html>
<html>
<head>
<title>Query Sequence Submission Results</title>
	<meta charset="utf-8">
</head>

 
<body>
<div class="page-header">
<h3>Past Sequencing Submission</h3>
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
			
			$query_date = 'sequencing2.date_submitted BETWEEN (?) AND (?)';
			$check_date = 'true';
		}
			
		if(($_GET['projName'] != '0')){
			$p_project_name = htmlspecialchars($_GET['projName']);
			$query_field = 'sample.project_name = ?';
			$check_field = 'true';
		}
				
		#$query_main = "SELECT sequencing2.sequencing_info FROM sample JOIN sequencing2 ON sample.sequencing_info = sequencing2.sequencing_info WHERE ";
		$query_main = "SELECT sequencing2.sequencing_info,sequencing2.sequencer_name,sequencing2.sequencing_type,sequencing2.date_submitted,sequencing2.library_prep_kit,sample.sample_name,sample.seq_id FROM sequencing2 JOIN sample ON sequencing2.sequencing_info = sample.sequencing_info";
		$query = "";
		
		if(isset($_GET['db_content'])){
			$query = $query_main;
			$stmt = $dbc->prepare($query);
			if($stmt){
				build_seq_subbed_output($stmt);
			};
		}
		else{
			if($check_field == 'true' && $check_date == 'false'){//only query field populated
				$query = $query_main.' WHERE '.$query_field;
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('s', $p_project_name);
				if($stmt){
					build_seq_subbed_output($stmt);
				}
			}
			elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
				$query = $query_main.' WHERE '.$query_date;
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('ss',$p_smydate , $p_emydate);
				if($stmt){
					build_seq_subbed_output($stmt);
				}
			}
			elseif ($check_field == 'true' && $check_date == 'true') {//both date and project name are populated
				$query = $query_main.' WHERE '.$query_field.' AND '.$query_date;
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('sss',$p_project_name, $p_smydate , $p_emydate);
				if($stmt){
					build_seq_subbed_output($stmt);
				}
			}
			else{
				echo '<script>Alert.render2("ERROR: No entries found. Please check fields");</script>';
				echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
			}
		}

}

?>

</body>
</html>
