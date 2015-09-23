<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('database_connection.php');
include ('index.php');
include ('/functions/build_bulk_seqSub_table_new.php');

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
			 if($_POST['sample_type'] == '0' || $_POST['container_type'] == '0' || $_POST['method'] == '0' || $_POST['read_length'] == '0' ||
			 $_POST['quant_method'] == '0' || $_POST['application'] == '0' || $_POST['libPK'] == '0' || $_POST['submittedBy'] == '0' || $_POST['dtSub'] == ''){
			 	$error = 'true';
				echo '<script>Alert.render("ERROR: Required Field Not Entered. Please Check Form");</script>';
				echo '<p><button class="button" type=button onClick="parent.location=\'/series/dynamic/airmicrobiomes/seq_submission_UDF_check.php\'" value=\'Go Back\'>Go Back</button></p>';
			 }
			 
			 if($error == 'false'){
			 		$array_sample_names = array();
				    $array_sample_names = $_POST['sample_names'];
					
					//for form, need to send onto the next page
					$sample_type = $_POST['sample_type'];
					$container_type = $_POST['container_type'];			 	
				    $method = $_POST['method'];
					$read_length = $_POST['read_length'];
					$quant_method = $_POST['quant_method']; 
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
					$_SESSION['quant_method'] = $quant_method; 
					$_SESSION['application'] = $application;
					$_SESSION['libPK'] = $libPK;
					$_SESSION['submittedBy'] = $submittedBy;
					$_SESSION['dtSub'] = $dtSub;
					$_SESSION['seq_pool'] = $seq_pool;
					
					$options = array();
					$options = $_POST['optional'];
					
					build_bulk_seqSub_table_new($array_sample_names,$options,$sample_type,$container_type);
			 }
			 
			 
			 //check submission (warn cannot be undone)
			 
			 
			 /*//first check which fields you want to have as options to fill in
			 	//check if you have dna or rna
			 	//check if you have optional quality/quanity checks to add
			 	Sample/Name*
			  * Container/Type*  +
			  * Container/Name
			  * Sample/Well Location* 
			  * UDF/Sample Type  +
			  * UDF/Sample Conc. *
			  * UDF/Units 
			  * UDF/Quantitation Method * +
			  * UDF/DNA Contamination (%)
			  * UDF/RIN\/RINe ** (RNA only)
			  * UDF/Nanodrop Conc. (ng\/uL)
			  * UDF/260\/280 
				UDF/269\/230 
			  * UDF/Application* +
			  * UDF/Sequencing Method * +
			  * UDF/Read Length*+
			  * UDF/Sequencing Coverage (opt)
				UDF/Reference Genome (opt)
			  * UDF/Pooling *  
			  * 
			 		//add these to first page?
			 			UDF/Sample Type *
			 			Container/Type*
						Container/Name*
						UDF/Quantitation Method *
						UDF/Application*
						UDF/Sequencing Method *
						UDF/Read Length*
						UDF/Sequencing Coverage
						UDF/Reference Genome
						UDF/Pooling *
			  * 		UDF/Sample Buffer *
						UDF/Volume (uL) *
			 	
			 		//if DNA
					 	Sample/Name*
						Sample/Well Location* (auto populate this, does not need to appear on next page)
						UDF/Sample Conc. *
						UDF/Units * 
						UDF/Nanodrop Conc. (ng\/uL)
						UDF/260\/280 
						UDF/269\/230 
						UDF/Sample Buffer *
						UDF/Volume (uL) *
					//if RNA
						Sample/Name*
						Sample/Well Location*
						UDF/Sample Conc. *
						UDF/Units *
						UDF/DNA Contamination (%) ** (RNA only)
						UDF/RIN\/RINe ** (RNA only)
						UDF/Nanodrop Conc. (ng\/uL)
						UDF/260\/280 
						UDF/269\/230 
						UDF/Sample Buffer *
						UDF/Volume (uL) *
			 	
		
			 	
			 //second add what library used and what type of amplicon sequencing and what primers used
			 //third , add which dna extraction date your dna is taken from? dna extraction info? (have dropdown?)
			 //fourth, you need to build sample name for rikky based on sample submission type, and times of submission (need to keep track somewhere)
			 //set up key constraints?
			 //need way to edit/view per sample/sample submission?
			 			 */
			
		}
	?>

	</body>
	
	
</html>
