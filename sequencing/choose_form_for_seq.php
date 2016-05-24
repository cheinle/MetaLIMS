<?php
include ('../index.php');
include ('../database_connection.php');
include ('../functions/build_bulk_seqSub_table_new.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Choose Form</title>
	</head>
 
	<body>
		<div class="page-header"> <!-- commenting this out gets rid of the large bar-->	
	    <h3>Choose Form</h3>
		</div>
	<?php
		$submitted = 'false';
		if (isset($_POST['submit'])) {
			 $error = 'false';
			 //error check 
			 if($_POST['sample_type'] == '0' || $_POST['container_type'] == '0' || $_POST['method'] == '0' || $_POST['read_length'] == '0' || $_POST['application'] == '0' || $_POST['libPK'] == '0' || $_POST['submittedBy'] == '0' || $_POST['dtSub'] == ''){
			 	$error = 'true';
				echo '<script>Alert.render("ERROR: Required Field Not Entered. Please Check Form");</script>';
				echo '<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />';
			}
			 
			 if($error == 'false'){
			 		$array_sample_names = array();
				    $array_sample_names = $_POST['sample_names'];
					
					//for form, need to send onto the next page
					$sample_type = $_POST['sample_type'];
					$container_type = $_POST['container_type'];			 	
				    $method = $_POST['method'];
					$read_length = $_POST['read_length'];
					$application = $_POST['application'];
					$libPK = $_POST['libPK'];
					$seq_pool = $_POST['seq_pool'];
					
					//if amplicon
					$amplicon_type = '';
					$seqOther = '';
					$primerL = '';
					$primerR = '';
					if($sample_type == 'Amplicon'){
						if(isset($_POST['amplicon_type'])){
							$_SESSION['amplicon_type'] = $_POST['amplicon_type'];
						}
						if(isset($_POST['seqOther'])){
							$_SESSION['seqOther'] = $_POST['seqOther'];
						}
						if(isset($_POST['primerL'])){
							$_SESSION['primerL'] = $_POST['primerL'];
						}
						if(isset($_POST['primerR'])){
							$_SESSION['primerR'] = $_POST['primerR'];
						}
					}
					
					
					$submittedBy = $_POST['submittedBy'];
					$dtSub =$_POST['dtSub'];
					
					//set session vars
					$_SESSION['sample_type'] = $sample_type;
					$_SESSION['container_type'] = $container_type;			 	
				    $_SESSION['method'] = $method;
					$_SESSION['read_length'] = $read_length ;
					$_SESSION['application'] = $application;
					$_SESSION['libPK'] = $libPK;
					$_SESSION['submittedBy'] = $submittedBy;
					$_SESSION['dtSub'] = $dtSub;
					$_SESSION['seq_pool'] = $seq_pool;
					
					
					build_bulk_seqSub_table_new($array_sample_names,$sample_type,$container_type,$root);
			 }
			
		}
	?>

	</body>
	
	
</html>
