<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../index.php');
include('../../database_connection.php'); 
include('../../functions/build_seq_subbed_output_new.php');
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
		if(($_GET['smydate'] != NULL) && ($_GET['emydate'] != NULL)){
		
			//sanatize user input to make safe for browser
			$p_smydate = htmlspecialchars($_GET['smydate']);
			$p_emydate = htmlspecialchars($_GET['emydate']);
			
			//check is a date
			if ((DateTime::createFromFormat('Y-m-d', $p_smydate) !== FALSE) && (DateTime::createFromFormat('Y-m-d', $p_emydate) !== FALSE) ) {
				$check_date = 'true';

			}
		}
			
		if(($_GET['projName'] != '0')){
			$p_project_name = htmlspecialchars($_GET['projName']);
			$check_field = 'true';
		}
				

		$query_main = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
		JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
		JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info";

		$query = "";
		$stmt = '';
		if(isset($_GET['db_content'])){
			$query = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
					  JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
					  JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info";
			$stmt = $dbc->prepare($query);
			if($stmt){
				build_seq_subbed_output_new($stmt,$root);
			};
		}
		else{
			if($check_field == 'true' && $check_date == 'false'){//only query field populated
				$query = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
						  JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
						  JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info 
						  WHERE sample.project_name = ? ORDER BY sample_sequencing2.seq_id ASC";
						  
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('s', $p_project_name);
				if($stmt){
					build_seq_subbed_output_new($stmt,$root);
				}
			}
			elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
				$query = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
					     JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
					     JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info
					     WHERE sequencing2.date_submitted BETWEEN (?) AND (?) ORDER BY sample_sequencing2.seq_id ASC";
					     
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('ss',$p_smydate , $p_emydate);
				if($stmt){
					build_seq_subbed_output_new($stmt,$root);
				}
			}
			elseif ($check_field == 'true' && $check_date == 'true') {//both date and project name are populated
				$query = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
						 JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
						 JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info 
						 WHERE sample.project_name = ? 
						 AND sequencing2.date_submitted BETWEEN (?) AND (?) ORDER BY sample_sequencing2.seq_id ASC";
						 
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('sss',$p_project_name, $p_smydate , $p_emydate);
				if($stmt){
					build_seq_subbed_output_new($stmt,$root);
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