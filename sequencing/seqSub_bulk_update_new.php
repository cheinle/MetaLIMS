<?php
	include ('../index.php');
	include ('../database_connection.php');
	include ('../functions/text_insert_update_storage_info.php');
	include_once("../functions/check_collector_names.php");
	include_once("../functions/unset_session_vars.php");
	include("../functions/check_sequencing_type.php");
	include('../functions/get_submission_number.php');
	include('../functions/get_application_abbrev.php');
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
?>
	<!doctype html>
	<html>
	<head>
	<meta charset="utf-8">
	<title>SampleSheet</title>
	</head>
	 
	<body>
	<div class="page-header">
	<h3>Sequencing Sample Form</h3>
	</div>
	<div class="border">
	<fieldset>
	<LEGEND><b>Results:</b></LEGEND>
	<div class="indent">
<?php
	//////////////////////////////
	
//error && type checking 
$submitted = 'false';
if(isset($_POST['submit'])){
	$error = 'false';
	$sample_array=$_POST['sample'];
		
	//grab stuff from your first page
	$sample_type = $_SESSION['sample_type'];
	$container_type = $_SESSION['container_type'];			 	
	$method = $_SESSION['method'];
	$read_length = $_SESSION['read_length'];
	$application = $_SESSION['application'];
	$libPK = $_SESSION['libPK'];		
	$submittedBy = $_SESSION['submittedBy'];
	$dtSub =$_SESSION['dtSub'];
	$seq_pool =$_SESSION['seq_pool'];
	
		
	//check on amplicon info
	if($sample_type == 'Amplicon'){
		$amplicon_type = $_SESSION['amplicon_type'];
		if($amplicon_type == 'AmpliconOther'){
			$amplicon_type = $_SESSION['seqOther'];
		}
		$primerL = $_SESSION['primerL'];
		$primerR = $_SESSION['primerR'];
	}
	else{
		$amplicon_type = NULL;
		$primerL = NULL;
		$primerR = NULL;
	}
		
	//create sequence submission name
	$length = 5;
	$randomString = substr(str_shuffle(md5(time())),0,$length);
	$p_seqInfo = $dtSub.'_'.$randomString.'_submission';
		
	//check sequence info name exists
	$p_seqInfo_exists_check = 'false';
	$stmt1 = $dbc->prepare("SELECT sequencing_info FROM sequencing2 WHERE sequencing_info = ?");
	$stmt1 -> bind_param('s', $p_seqInfo);
	$stmt1->bind_result($col1);
			
	if ($stmt1->execute()){
		$stmt1->bind_result($name);
		if ($stmt1->fetch()){
			if($name == $p_seqInfo){
				echo "WARNING:".$p_seqInfo." exists. Please Check Sequencing Submission Info.<br>";
				$p_seqInfo_exists_check = 'true';
			}
		}
	} 
	else {
		$error = 'true';
	    die('execute() failed: ' . htmlspecialchars($stmt->error));
	}
	$stmt1 -> close();

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){
		
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
			$insert_error = 'false';
			
			//if sequencing info ID did not exist, create it
			//no longer updating sample table with this sequencing info...going to save and retrieve a diff way
			//because you now have possibility of submitting sample mult times->//create sequencing info record first, then you can attach it to each of your samples
			if($p_seqInfo_exists_check == 'false'){
				$stmt2 = $dbc -> prepare("INSERT INTO sequencing2 (sequencing_info,entered_by,sequencing_type,date_submitted,library_prep_kit,submitted_by,container_type,sequencing_method,read_length,sample_type,seq_pool,amplicon_type,primerL,primerR) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$stmt2 -> bind_param('ssssssssssssss',$p_seqInfo,$p_updated_by,$application,$dtSub,$libPK,$submittedBy,$container_type,$method,$read_length,$sample_type,$seq_pool,$amplicon_type,$primerL,$primerR);
					
				if($stmt2 -> execute()){
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
						
					//check if add was successful or not. Tell the user
				   	if($rows_affected2 > 0){
						echo 'You added new Seqencing Submission Info:'.$p_seqInfo.'<br>';
					}else{
						$insert_error = 'true';
						throw new Exception("ERROR: Seq Info ".$p_seqInfo." was not added");	
					}
				}
				else{
					$insert_error = 'true';
					throw new Exception("Seq Info Exectution ERROR: ".$p_seqInfo);		
				}
			}
			
			//now process each of the samples with their DNA conc, Vol of aliquot, if DNA sample exists and link to the seq sub info
			
			//prepare excel sample sheet
			//require_once dirname(__FILE__) . '/xls_classes/PHPExcel/IOFactory.php';
			require_once ($path.'acquired/xls_classes/PHPExcel/IOFactory.php');
			 
			//$objPHPExcel = PHPExcel_IOFactory::load("Sequencing_SampleSubmissionForm.xlsx");
			$objPHPExcel = PHPExcel_IOFactory::load($path."sequencing/sequencing_form.xlsx");
			$styleArray = array(
		    'font'  => array(
		    	'color' => array('rgb' => '000000'),
		    ));
			
			//insert new rows 
			$number_of_samples = sizeof($sample_array);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			for($i = 1; $i <= $number_of_samples; $i++){
				$objWorksheet->insertNewRowBefore(15);
			}
			$starting_row = 18;
		
			//insert sample info to db and excel sheet at the same time
			echo 'Samples Updated:<br>';
			$container_counter = 1;
			foreach($sample_array as $sample_name => $process){
				$starting_row++;
				$p_sample_name = htmlspecialchars($sample_name);
				
				//create new sample name
				///////////////////////////////Build to track number of submissions for each sequencing type for each sample/////////////////////////

				$seq_name = $process['seq_id'];
				$seq_type_abbrev = get_application_abbrev($application,'abbrev'); //genomic ...need a way to get this if application == 'Genomic DNA' seq_type_abbrev = G
				$check_for_error1 = strcmp($seq_type_abbrev,'ERROR');
				if($check_for_error1 == '0'){
					throw new Exception("ERROR: No Sequencing Type Abbreviation. Please Notify Admin");
				}
				
				//based on application type?
				$number_of_submissions = get_submission_num($sample_name,$seq_type_abbrev); //would you need to create an row in the table everytime you create a sample? (yes, because then you can look up in the table)
				$check_for_error = strcmp($number_of_submissions,'ERROR');
				if($check_for_error == '0'){
						throw new Exception("ERROR: No Sequencing Submission Number. Please Notify Admin");
				}
				
				$new_number_of_submissions = $number_of_submissions + 1;
				//check if submission number is two digits
				$length_check = strlen($new_number_of_submissions);
				if($length_check < 1 || $length_check > 2){
					throw new Exception("ERROR: Sequencing Number Has Invalid Format:'".$new_number_of_submission."'.Please Notify Admin");
				}
				else if($length_check == 1){
					$new_number_of_submissions = '0'.$new_number_of_submissions;
				}

				$new_seq_sub_name = $seq_name.'-'.$seq_type_abbrev.'-'.$new_number_of_submissions;
				

				//if everything went well, you want to update the new number of submissions
				//do you need a transaction for this?
				$update_query = get_application_abbrev($application,'query'); 
				$stmt = $dbc -> prepare("$update_query");
				if($stmt){
					$stmt -> bind_param('is', $new_number_of_submissions,$sample_name);
					$stmt -> execute();
					if($stmt){
						$rows_affected = $stmt ->affected_rows;					
						$stmt -> close();
										
						//check if add was successful or not. Tell the user
						if($rows_affected < 0){
							throw new Exception("An Error Has Occurred: No Update For Submission Number");
						}
					}
					else{
						throw new Exception("Execution Error: No Update For Submission Number");
					}
				}
				else{
					throw new Exception("Prepare Error: No Update For Submission Number");
				}			
				
				////////////////////////////////////////////////////////////////////////////////////////////////
				
				$p_wellLoc = '';
				$container_name = '';
				if(isset($process['wellLoc'])){
					$p_wellLoc = $process['wellLoc'];
				}
				/*$container_name = 'container'.$container_counter;
				if($container_type == 'Tube'){
					$container_counter++;
				}*/
				if($container_type == "Tube"){
					$container_name = $seq_name;
				}
				else{
					$container_name = $p_seqInfo;// $dtSub.'_'.$randomString.'_submission';
				}
				
				$p_sampConc = $process['sampConc'];
				$p_vol = $process['vol'];
				$p_sampBuffer = $process['sampBuffer'];
				$p_exists = $process['exists'];
				
				//update samplesheet-write to file
				$objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$starting_row, $new_seq_sub_name)
                        ->setCellValue('B'.$starting_row, $container_type)
                        ->setCellValue('C'.$starting_row, $container_name)
                        ->setCellValue('D'.$starting_row, $p_wellLoc)
						->setCellValue('E'.$starting_row, $method)
						->setCellValue('F'.$starting_row, "")//seq coverage
						->setCellValue('G'.$starting_row, $sample_type)//
						->setCellValue('H'.$starting_row, "")//reference genome
						->setCellValue('I'.$starting_row, $seq_pool)//pooling
						->setCellValue('J'.$starting_row, $application)
						->setCellValue('K'.$starting_row, $read_length)
						->setCellValue('L'.$starting_row, $p_sampBuffer)
						->setCellValue('M'.$starting_row, $p_sampConc)
						->setCellValue('N'.$starting_row, 'ng/uL')
						->setCellValue('O'.$starting_row, $p_vol );
					
				//style the newly inserted cells
				$alpha_array = range('A','O');
				foreach($alpha_array as $index => $alpha){
					$objPHPExcel->getActiveSheet()->getStyle($alpha.$starting_row)->applyFromArray($styleArray);
					$objPHPExcel->getActiveSheet()->getStyle($alpha.$starting_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				//update sample table;link new seq submission name
				//update storage table
				//update sample storage info
				$sql = "INSERT INTO sample_sequencing2 (sample_name,seq_id,seq_sub_name,dna_conc,vol,wellLoc,sampBuffer,nano, a_280,a_230,dnaCont,RIN) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
				if($stmt3 = $dbc ->prepare($sql)){
	                $stmt3->bind_param('sssddssddddd',$p_sample_name,$p_seqInfo,$new_seq_sub_name,$p_sampConc,$p_vol,$p_wellLoc,$p_sampBuffer,$p_nano,$p_280,$p_230,$p_dnaCont,$p_RIN);
	
	                $stmt3 -> execute();
					$rows_affected3 = $stmt3 ->affected_rows;
					$stmt3 -> close();
					if($rows_affected3 >= 0){
							echo "You updated sample ".$p_sample_name.'- '.$p_sampConc.' (ng/ul) '.$p_vol.' (uL). Number of Submissions: '.$new_number_of_submissions.'<br>';
					}
					else{
						$insert_error = 'true';
						throw new Exception("ERROR: Sample ".$p_sample_name." was not updated");
					}
				}
				else{
					$insert_error = 'true';
					throw new Exception("ERROR: Sample Update Prepare Failure");
				}
				
				//update storage table
		      	//if orig_sample_exists = 'false' change original to Used,Used and store
		      	//else, don't add to update line!
		      	if($p_exists == 'three'){
		      		$p_dStore = 'Used,Used';
		      	}
				else{
					//grab what is stored and insert it again O.o
					$p_dStore = text_insert_update_stinfo($p_sample_name,'dna_extr','storage_info',$root);
				}
				//check that sample name exists in storage first
		      	$getName = $dbc->prepare('SELECT sample_name FROM storage_info WHERE sample_name = ?') or die('Couldn\'t check the name');
				$getName->bind_param('s', $p_sample_name);
				$getName->execute();
				$getName->store_result();
				$countRows = $getName->num_rows;
				$getName->close();

				if($countRows == 1){
			        $query_si = 'UPDATE storage_info SET dna_extr = ?,DNA_sample_exists = ? WHERE sample_name = ?';		
					if($stmt_si = $dbc ->prepare($query_si)) {
				    	$stmt_si->bind_param('sss', $p_dStore,$p_exists,$p_sample_name); 
						$stmt_si -> execute();
						$rows_affected_si = $stmt_si ->affected_rows;
						if($rows_affected_si < 0){
							$insert_error = 'true';
							throw new Exception('An error has occurred in storing storage info for '.$p_sample_name);
						}
					}
					else{
						$insert_error = 'true';
						throw new Exception("ERROR: Storage Insert Prepare Failure");
					}
				}
				else{
					echo '<script>Alert.render("ERROR:Unable To Update Sample In Storage Info. Please See Admin");</script>';
					throw new Exception("ERROR:Unable To Update Sample In Storage Info. Please See Admin");	
					$insert_error = 'true';											
				}
					
			}
			if($insert_error == 'false'){
				$_SESSION['submitted'] = 'true';
				$dbc->commit();
				//unset_session_vars('bulk_seqSub_update'); //uncomment this!
				//write to file						
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save($path.'sequencing/sequencing_sample_submission_forms/SamplesSubmissionForm_'.$dtSub.'_'.$randomString.'.xlsx');
				
				$file_name ='SamplesSubmissionForm_'.$dtSub.'_'.$randomString.'.xlsx';
				echo 'File has been created and stored in : [base_dir]/sequencing/sequencing_sample_submission_forms/ and can also be downloaded from a link on the View Past Submissions info page',EOL;
				echo '<a href='.$root.'sequencing/sequencing_sample_submission_forms/'.$file_name.' download>Click Here To Download</a><br>';
				if($container_type != "Tube"){
					echo '<strong>Your Plate Container Name is: '.$container_name.'</strong><br>';
				}else{
					echo "<strong>Container Names for tubes are abbreviated sequencing IDs</strong><br>";
				}
				echo '<button class="button" type=button onClick="parent.location=\'<?php echo $root;?>sample_update_lookup.php\'" value="\'Go Back\'>Go Back</button>';
			}
			else{
				throw new Exception("ERROR: Update Failed. Please Contact Admin");
			}
		}
		catch (Exception $e) { 
    		if (isset ($dbc)){
       	 		$dbc->rollback ();
       			echo "Error:  " . $e; 
    		}
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
		};
	}
	else{
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
	}
	
}
	 
?>
</div>
</fieldset>
</div>
</body>
</html>
