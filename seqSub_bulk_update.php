<?php
	include ('index.php');
	include ('database_connection.php');
	include ('functions/text_insert_update_storage_info.php');
	include_once("functions/check_collector_names.php");
	include_once("functions/unset_session_vars.php");
	include("functions/check_sequencing_type.php");
	
	//////////////////////////////
	//error && type checking 
		$submitted = 'false';
		if(isset($_POST['submit'])){
			//print_r($_POST);
			$error = 'false';
			
			$sample_array=$_POST['sample'];
			//sanatize user input to make safe for browser
			$p_dtSub = htmlspecialchars($_POST['dtSub']);
			$p_projName = htmlspecialchars($_POST['projName']);
			$p_seqName = htmlspecialchars($_POST['seqName']);
			$p_libPK = htmlspecialchars($_POST['libPK']);
			$p_seqOther = htmlspecialchars($_POST['seqOther']);
			$p_submitted_by = htmlspecialchars($_POST['submittedBy']);
			$p_typeName = htmlspecialchars($_POST['type']);
			$p_primerL = htmlspecialchars($_POST['primerL']);
			$p_primerR = htmlspecialchars($_POST['primerR']);
			

			if($p_dtSub  == ''){
				echo '<p>ERROR: You must enter a Date Submitted!<p>';
				$error = 'true';
			}
			if($p_projName == '0'){
				echo '<p>ERROR: You must select a Project Name!<p>';
				$error = 'true';
			}
			
			if($p_seqName == '0'){
					echo '<p>ERROR: You must enter Sequencer Name!<p>';
					$error = 'true';
			}
			if($p_libPK == '0'){
				echo '<p>ERROR: You must select a Library Prep Kit!<p>';
				$error = 'true';
			}
			if($p_submitted_by == '0'){
				echo '<p>ERROR: You Must Enter Who Submitted The Sequencing Request!<p>';
				$error = 'true';
			}
			if(($p_typeName == 'Amplicon-Other' && $p_seqOther == '') || ($p_typeName != 'Amplicon-Other' && $p_seqOther != '')){
				echo '<p>ERROR: Please Fill In Other Sequencing Type If Amplicon-Other Is Selected!<p>';
				$error = 'true';
			}
			
			if(($p_typeName == 'Amplicon-16S'  || $p_typeName == 'Amplicon-18S'  || $p_typeName == 'Amplicon-Other' )){
				if($p_primerL == '' || $p_primerR == ''){
					echo '<p>ERROR: Please Fill In Primer Set Name(s) Used For Amplicon Sequencing!<p>';
					$error = 'true';
				}
			}
			else{
				if($p_primerL != '' || $p_primerR != ''){
					echo '<p>ERROR: Primer Set Name(s) Used Only For Amplicon Sequencing. Please Check Sequencing Type!<p>';
					$error = 'true';
				}
			}
			
			$p_seqInfo = $p_dtSub.$p_projName.'_submission';
			
			//your 'other' amplicon sequencing region is always stored at the end of the array
			/*$i=3;
			while($i>=0){
				if((isset($_POST['type'][$i])) && (($_POST['type'][$i]) == 'AmpliconOther')){
					$_POST['type'][] = $p_seqOther;
					
				}$i--;
			}
			
			//check and process sequencing type info
			if(isset($_POST['type'])){
				$array=$_POST['type'];
				
				$check = check_sequencing_types($array,'true');
				if($check['boolean'] == 'false'){
					echo '<p>ERROR: You must Enter valid Sequencing Type(s). Please check type(s)<p>';
					$error = 'true';
				}
				else{
					$p_typeName = $check['cat_name'];
				}
			}
			else{
				echo '<p>ERROR: You must Enter valid Sequencing Type(s). Please check type(s)<p>';
				$error = 'true';
			}
			*/
			
			
			//store get variables into session variables so you can use them on your back button
			//// is this built in for your samples????;
			$_SESSION['submitted'] = 'false';
			$_SESSION['sample_array'] = $sample_array;
			$_SESSION['type'] = $_POST['type'];
			$_SESSION['dtSub'] = $p_dtSub;
			$_SESSION['projName'] = $p_projName;
			$_SESSION['seqName'] = $p_seqName;
			$_SESSION['libPK'] = $p_libPK;
			$_SESSION['seqOther'] = $p_seqOther;
			$_SESSION['submittedBy'] = $p_submitted_by;
			$_SESSION['type'] = $p_type;
			$_SESSION['primerL'] = $p_primerL;
			$_SESSION['primerR'] = $p_primerR;

			if($p_primerL == ''){$p_primerL = NULL;};
			if($p_primerR == ''){$p_primerR = NULL;};
			if($p_seqOther == ''){$p_seqOther = NULL;};
			
			//check sequence info name exists
			$p_seqInfo_exists_check = 'false';
			$stmt1 = $dbc->prepare("SELECT sequencing_info FROM sequencing2 WHERE sequencing_info = ?");
			$stmt1 -> bind_param('s', $p_seqInfo);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			#echo "Name: {$name}<br>";
					#echo $p_seqInfo;
        			#echo 'Another way:'.print_r($row, true); //won't work with bind_result
        			if($name == $p_seqInfo){
        				echo "WARNING:".$p_seqInfo." exists. Please Check Sequencing Submission Info.<br>";
						$p_seqInfo_exists_check = 'true';
						#$error = 'true';
					}
				}
    			else {
        			#echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){
		
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
			$insert_error = 'false';
			
			
			////////////////////////////////////////////////////////////////////////////////////////////
			//if sequencing info ID did not exist, create it
			//create sequencing info record first, then you can attach it to each of your samples
			if($p_seqInfo_exists_check == 'false'){
				$stmt2 = $dbc -> prepare("INSERT INTO sequencing2 (sequencing_info,sequencing_type,sequencer_name,date_submitted,library_prep_kit,entered_by,submitted_by,seqOther,primerL,primerR) VALUES (?,?,?,?,?,?,?,?,?,?)");
				$stmt2 -> bind_param('ssssssss', $p_seqInfo,$p_typeName, $p_seqName,$p_dtSub,$p_libPK,$p_updated_by,$p_submitted_by,$p_seqOther,$p_primerL,$p_primerR);
					
				if($stmt2 -> execute()){
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
						
					//check if add was successful or not. Tell the user
				   	if($rows_affected2 > 0){
						echo 'You added new Seqencing Submission Info:'.$p_seqInfo.'<br>';
						#$submitted = 'true';
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
			
			///////////////////////////////////////////////////////////////////////////////////////
			
			//now process each of the samples with their DNA conc, Vol of aliquot, if DNA sample exists and link to the seq sub info
			echo 'Samples Updated:<br>';
			foreach($sample_array as $sample_name => $process){
				$p_sample_name = htmlspecialchars($sample_name);
				$checkbox = 'false';
				if(isset($process['checkbox'])){
					$checkbox = 'true';
				}
				
				//if checkbox is checked/true, then go ahead and update this sample :D
				if($checkbox == 'true'){
					$p_dConc = $process['dna'];
					$p_vol = $process['vol'];
					$p_exists = $process['exists'];
					#$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
					
					//check that your DNA conc and your volume are acceptable numbers
					$regrex_check= '/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,4})?\s*$/';
					if (!preg_match("$regrex_check", $p_dConc)){
						echo '<p>ERROR: You Must Enter Valid DNA conc For '.$p_sample_name.'. Please Check Your Number (000.0000).<p>';
						$insert_error = 'true';
					}
					if (!preg_match("$regrex_check", $p_vol)){
						echo '<p>ERROR: You Must Enter Valid Volume For '.$p_sample_name.'. Please Check Your Number (000.0000).<p>';
						echo "pvol".$p_vol;
						$insert_error = 'true';
					}
					
					
					//update sample table;link new seq submission name
					//update storage table
					//update sample storage info
					//where is your transaction?
					
					///////////////////////////////////////////////////////////////////
					//change this to new sample_sequencing2 table, can be different every time...don't populate DNA conc automatically
					//////////////////////////////////////////////////////////////////
					$query3 = 'UPDATE sample SET seq_dna_conc =?, seq_vol = ?, sequencing_info = ?, updated_by = ? WHERE sample_name = ?';
					if($stmt3 = $dbc ->prepare($query3)) {                 
		                $stmt3->bind_param('disss',$p_dConc,$p_vol,$p_seqInfo,$p_updated_by, $p_sample_name);
		
		                $stmt3 -> execute();
						$rows_affected3 = $stmt3 ->affected_rows;
						#echo "rows:".$rows_affected;
						$stmt3 -> close();
						if($rows_affected3 >= 0){
								echo "You updated sample ".$p_sample_name.'- '.$p_dConc.' (ng/ul) '.$p_vol.' (uL) '.$p_exists.'<br>'; 
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
						$p_dStore = text_insert_update_stinfo($p_sample_name,'dna_extr');
						
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
								throw new Exception('An error has occured in storing storage info for '.$p_sample_name);
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
			}
			
			if($insert_error == 'false'){
				$_SESSION['submitted'] = 'true';
				$dbc->commit();
				unset_session_vars('bulk_seqSub_update');
				echo '<p><button class="btn btn-success" type=button onClick="parent.location=\'/series/dynamic/airmicrobiomes/sample_update_lookup.php\'" value="\'Go Back\'>Go Back</button></p>';
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

