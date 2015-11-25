<?php
	include ('index.php');
	include ('database_connection.php');
	include ('functions/text_insert_update_storage_info.php');
	include_once("functions/check_collector_names.php");
	include_once("functions/unset_session_vars.php");
	
    $sample_array=$_POST['sample'];
	$p_dExtKit = htmlspecialchars($_POST['dExtKit']);
	$p_d_extr_date = htmlspecialchars($_POST['d_extr_date']);
	$p_dVol= htmlspecialchars($_POST['dVol']);
	$p_dInstru= htmlspecialchars($_POST['dInstru']);
	$p_dVol_quant= htmlspecialchars($_POST['dVol_quant']);
	$p_dStore = $_POST['dStore_temp'].','.$_POST['dStore_name'];
	$p_dStore = htmlspecialchars($p_dStore);
	
	//check and process DNA Extractor names
	$array2=$_POST['dExtrName'];
	$check2 = check_collector_names($array2,'false');
	$p_dExtrName = $check2['cat_name'];
	

	
	//store get variables into session variables so you can use them on your back button
	////
	$_SESSION['submitted'] = 'false';
	$_SESSION['sample_array'] = $sample_array;
	$_SESSION['dExtKit'] = $p_dExtKit;
	$_SESSION['d_extr_date'] = $p_d_extr_date;
	$_SESSION['dVol'] = $p_dVol;
	$_SESSION['dInstru'] = $p_dInstru;
	$_SESSION['dVol_quant'] = $p_dVol_quant;
	
	$_SESSION['dStore_temp'] = $_POST['dStore_temp'];
	$_SESSION['dStore_name'] = $_POST['dStore_name'];
	//$_SESSION['DNA_sample_exist'] = $p_DNA_sample_exist;
	
	$_SESSION['dExtrName']= $p_dExtrName;
	
	if(isset($_POST['orig_sample_exist'])){;
		$p_orig_sample_exist= htmlspecialchars($_POST['orig_sample_exist']);
		$_SESSION['orig_sample_exist']=$p_orig_sample_exist;
	}
	else{
		$p_orig_sample_exist = 'true';
		$_SESSION['orig_sample_exist']=$p_orig_sample_exist;
	}
	if(isset($_POST['DNA_sample_exist'])){
		$p_DNA_sample_exist= htmlspecialchars($_POST['DNA_sample_exist']);
		$_SESSION['DNA_sample_exist']=$p_DNA_sample_exist;
	}
	
	
	
	
	/////
	
	

	//or should these fields be optional? This way you can choose if you want to update these extra fields or not?
	//right now leave as required for DNA extract updates :D
	$error = 'false';
	//check that fields have been set
	if($p_dExtKit == '0' ||$p_d_extr_date == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: DNA Extract Kit OR DNA Extract Date Not Entered.");</script>';
	}
	if($p_dVol == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: DNA Extract Volume Not Entered.");</script>';
	}
	if($p_dInstru == '0'){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Instrument Used To Measure DNA Conc. Not Entered.");</script>';
	}
	if($p_dVol_quant == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Vol Of DNA Used For DNA Quant. Not Entered.");</script>';
	}
	if($p_dExtrName == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: DNA Extractors Not Entered.");</script>';
	}
	if( $_POST['dStore_temp'] == '' ||$_POST['dStore_temp'] == '0' ){
		$error = 'true';
		echo '<script>Alert.render("ERROR: DNA Extract Storage Freezer Not Entered.");</script>';
	}
	if( $_POST['dStore_name'] == '' ||$_POST['dStore_name'] == '0' ){
		$error = 'true';
		echo '<script>Alert.render("ERROR: DNA Extract Storage Drawer Owner Not Entered.");</script>';
	}
	//if( !isset($_POST['orig_sample_exist']) || $_POST['orig_sample_exist'] == ''){
	//	$error = 'true';
	//	echo '<script>Alert.render("ERROR: Check Checkbox If Original Sample Exists.");</script>';
	//}
	if(!isset($_POST['DNA_sample_exist']) || $_POST['DNA_sample_exist'] == ''){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Check Checkbox If DNA Sample Exists.");</script>';
	}
	if($p_dVol_quant > $p_dVol){
		$error = 'true';
		echo '<script>Alert.render("ERROR: Check Volume Used to Measure DNA Concentration");</script>';
	}
	

	//if there are no errors, proceed to update the samples choosen
	if($error == 'false'){
		echo 'Samples Updated:<br>';
		
		try{
			//start transaction
			$dbc->autocommit(FALSE);
			
			foreach($sample_array as $sample_name => $dna_conc){
				$p_sample_name = htmlspecialchars($sample_name);
				$checkbox = 'false';
				if(isset($dna_conc['checkbox'])){
					$checkbox = 'true';
				}
				
				//if checkbox is checked/true, then go ahead and update this sample :D
				if($checkbox == 'true'){
					$p_dConc = $dna_conc['dna'];
					#echo $p_dConc.','.$p_d_extr_date.','.$p_dExtKit.','.$p_dVol.','.$p_dInstru.','.$p_dVol_quant.','.$p_dExtrName.','.$p_sample_name;
					//update sample table 
					$query = 'UPDATE sample SET d_conc =?, d_extraction_date = ?,dna_extract_kit_name=?,d_volume = ?,d_conc_instrument=?,d_volume_quant =?,dExtrName =?  WHERE sample_name = ?';
					if($stmt = $dbc ->prepare($query)) {                 
		                $stmt->bind_param('dssisiss',$p_dConc,$p_d_extr_date,$p_dExtKit,$p_dVol,$p_dInstru,$p_dVol_quant,$p_dExtrName,$p_sample_name);
		
		                $stmt -> execute();
						$rows_affected = $stmt ->affected_rows;
						#echo "rows:".$rows_affected;
						$stmt -> close();
						if($rows_affected >= 0){
							echo "You updated sample ".$p_sample_name.'- '.$p_dConc.' (ng/ul)<br>'; //cleanup
						}
						elseif($rows_affected == 0){
						//	echo "No Update Needed for ".$p_sample_name.' All Fields Are The Same. Please Check Sample<br>';
						}
						else{
							throw new Exception("ERROR: Sample ".$p_sample_name." was not updated");
						}
					}
					else{
						throw new Exception("ERROR: Sample Insert Prepare Failure");
					}
					
					//update storage table
			      	//if orig_sample_exists = 'false' change original to Used,Used and store
			      	//else, don't add to update line!
			      	if($p_orig_sample_exist == 'false'){
			      		$original = 'Used,Used';
			      	}
					else{
						//grab what is stored and insert it again O.o
						$original = text_insert_update_stinfo($p_sample_name,'original','storage_info',$root);
						
					}
			      	//check that sample exists in storage_info first?
			      	//check that sample name exists in storage first
			      	$getName = $dbc->prepare('SELECT sample_name FROM storage_info WHERE sample_name = ?') or die('Couldn\'t check the name');
					$getName->bind_param('s', $p_sample_name);
					$getName->execute();
					$getName->store_result();
					$countRows = $getName->num_rows;
					$getName->close();

					if($countRows == 1){
				        $query_si = 'UPDATE storage_info SET original = ?, orig_sample_exists = ?,dna_extr = ?,DNA_sample_exists = ? WHERE sample_name = ?';		
						if($stmt_si = $dbc ->prepare($query_si)) {
					    	$stmt_si->bind_param('sssss', $original,$p_orig_sample_exist,$p_dStore,$p_DNA_sample_exist,$p_sample_name); 
							$stmt_si -> execute();
							$rows_affected_si = $stmt_si ->affected_rows;
							if($rows_affected_si > 0){
								echo 'SUCCESS: Updated '.$p_sample_name.' in storage info <br>';	
							}
							elseif($rows_affected_si == 0){
								echo "No Update Needed for ".$p_sample_name.'<br>';
							}
							else{
								throw new Exception('An error has occured in storing storage info for '.$p_sample_name);
							}
						}
						else{
							throw new Exception("ERROR: Storage Insert Prepare Failure");
						}
					}
					else{
						//give warning message instead of inserting here because it should be inserted later. If you see this happening
						//then can check if there is another problem somewhere else
						throw new Exception("ERROR: Sample ".$p_sample_name." Does Not Exist In Storage Info. Please See Admin");
					}
				}	
			}
			$dbc->commit();
			unset_session_vars('bulk_dna_update');
		}
		catch (Exception $e) { 
    		if (isset ($dbc)){
       	 		$dbc->rollback ();
       			echo "Error:  " . $e; 
    		}
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
		}
		
	}
	else{
			echo '<p>
			<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />
			</p>';
	}

?>
