<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php'); 
include('index.php');
include('functions/build_seq_subbed_output_new.php');
include('config/path.php');
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
		//$query_main = "SELECT sequencing2.sequencing_info,sequencing2.sequencer_name,sequencing2.sequencing_type,sequencing2.date_submitted,sequencing2.library_prep_kit,sample.sample_name,sample.seq_id FROM sequencing2 JOIN sample ON sequencing2.sequencing_info = sample.sequencing_info";
		/*$query_main = "SELECT sample_sequencing2.seq_id,
		sample_sequencing2.seq_sub_name,
		sample_sequencing2.dna_conc,
		sample_sequencing2.vol,
		sample_sequencing2.wellLoc,
		sample_sequencing2.sampBuffer,
		sample_sequencing2.nano,
		sample_sequencing2.a_280,
		sample_sequencing2.a_230,
		sample_sequencing2.dnaCont,
		sample_sequencing2.RIN,
		sample_sequencing2.sample_exists,
		sample.sample_name 
		 FROM sample_sequencing2 JOIN sample ON sample_sequencing2.sample_name = sample.sample_name";*/
		 
		//$query_main = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 JOIN sample ON sample_sequencing2.sample_name = sample.sample_name";
		$query_main = "SELECT DISTINCT sample_sequencing2.seq_id FROM sample_sequencing2 
		JOIN sample ON sample_sequencing2.sample_name = sample.sample_name
		JOIN sequencing2 ON sample_sequencing2.seq_id = sequencing2.sequencing_info";
		//add date submitted etc etc...are you storing these other info somewhere? like who submitted
		//please check all other form + samplesheet info...you would want to store all of that somewhere
		//and retrieve it here
		$query = "";
		
		if(isset($_GET['db_content'])){
			$query = $query_main;
			$stmt = $dbc->prepare($query);
			if($stmt){
				build_seq_subbed_output_new($stmt,$root);
			};
		}
		else{
			if($check_field == 'true' && $check_date == 'false'){//only query field populated
				$query = $query_main.' WHERE '.$query_field.' ORDER BY sample_sequencing2.seq_id ASC';
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('s', $p_project_name);
				if($stmt){
					build_seq_subbed_output_new($stmt,$root);
				}
			}
			elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
				$query = $query_main.' WHERE '.$query_date.' ORDER BY sample_sequencing2.seq_id ASC';
				$stmt = $dbc->prepare($query);
				$stmt -> bind_param('ss',$p_smydate , $p_emydate);
				if($stmt){
					build_seq_subbed_output_new($stmt,$root);
				}
			}
			elseif ($check_field == 'true' && $check_date == 'true') {//both date and project name are populated
				$query = $query_main.' WHERE '.$query_field.' AND '.$query_date.' ORDER BY sample_sequencing2.seq_id ASC';
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