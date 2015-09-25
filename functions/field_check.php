

<?php	//function to check fields have appropriate info
function field_check($get_array,$check_for){
				include ('database_connection.php');
				include("functions/check_collector_names.php");

				$error = 'false';
				//check that all date/times exist
				/*if((!$get_array['sdate']) || (!$get_array['stime']) || (!$get_array['edate']) || (!$get_array['etime'])){
					echo "<p>ERROR: Check Date/Time(s)".'</p><br>';
					$error = 'true';
				}*/
				
				//check that storage locations are picked 
					//original storage
				if ($get_array['oStore_temp'] == '0') {//check this makes sense
					echo '<p>ERROR: You must select a Sample Storage Temperature!<p>';
					$error = 'true';
				}
				if ($get_array['oStore_name'] == '' || $get_array['oStore_name'] == '0') {
					echo '<p>ERROR: You must select a Sample Storage Location!<p>';
					$error = 'true';
				}
				 if(($get_array['oStore_name'] == 'Used' && $get_array['oStore_temp'] != 'Used') ||(($get_array['oStore_temp'] == 'Used' && $get_array['oStore_name'] != 'Used'))){
				 	echo '<p>ERROR: Please check Sample Storage Location!<p>';
					$error = 'true';
				 }
				
				
				//add other storage locations?
				
				
				
				//if you have a storage freezer, you must have a storage drawer
				//check that if freezer is set, the drawer is also set
				if($_GET['oStore_temp'] != '0'){
					if($_GET['oStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer</p>";
						$error = 'true';
					}
				}
				if($_GET['dStore_temp'] != '0'){
					if($_GET['dStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer For Your DNA</p>";
						$error = 'true';
					}
				}
				if($_GET['rStore_temp'] != '0'){
					if($_GET['rStore_name'] == '0'){
						echo "<p>ERROR:Please Select a Storage Drawer For Your RNA</p>";
						$error = 'true';
					}
				}
				
				//check if required fields are entered
				$name_check = 'false';
				
				if ($get_array['sample_number'] == '0') {
					echo '<p>ERROR: You must enter a Sample Number!<p>';
					$error = 'true';
				}
				
				if ($get_array['projName']== '0') {
					echo '<p>ERROR: You must select Project Name!<p>';
					$error = 'true';
				}
				if ($get_array['loc'] == '0') {
					echo '<p>ERROR: You must select a Location Name!<p>';
					$error = 'true';
				}
				if ($get_array['rloc'] == '0') {
					echo '<p>ERROR: You must select a Relative Location Name!<p>';
					$error = 'true';
				}
				//if ($get_array['airSamp'] == '0') {
				//	echo '<p>ERROR: You must select an Air Sampler Name!<p>';
				//	$error = 'true';
				//}
				if ($get_array['media'] == '0') {
					echo '<p>ERROR: You must select a Media Type!<p>';
					$error = 'true';
				}
				
				if ($get_array['sType'] == '0') {
					echo '<p>ERROR: You must enter a Sample Type!<p>';
					$error = 'true';
				}
				
				//check flow rate if sample is an air sample
				//check if flow rate is a number
				if($get_array['sType'] == 'A'){
					if(($get_array['fRate'] == '') || ($get_array['fRate_eod'] == '')){
						echo '<p>ERROR: You must enter a Flow Rate!<p>';
						$error = 'true';
					}
					else{
						#$regrex_check_fr  = '/(^[0-9]{0,3}\.[0-9][0-9]|[0-9]{0,3})$/';//flow rate should be between 0 and 3 digit number allowing for two decimal places
						$regrex_check_fr  = '/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*$/';
						if (!preg_match("$regrex_check_fr", $get_array['fRate'])){
							echo '<p>ERROR: You Must Enter Valid Flow Rate. Please Check Your Number.<p>';
							$error = 'true';
						}
						if (!preg_match("$regrex_check_fr", $get_array['fRate_eod'])){
							echo '<p>ERROR: You Must Enter Valid EOD Flow Rate. Please Check Your Number.<p>';
							$error = 'true';
						}	
					}
					
					//if sample type is P-pool, then restrict user from choosing
					if(!isset($get_array['pooled_flag']) || $get_array['pooled_flag'] == 'NP'){//if you are not a pooled sample
						if($get_array['sType'] == 'P'){
							echo '<p>ERROR: Unable To Choose Sample For Pooling Here. Please Use Sample Pooling Page<p>';
							$error = 'true';	
						}
					}
					
					
					//check sampling height if air sample
					if ($get_array['sampling_height'] == '') {
						echo '<p>ERROR: You must enter a Sampling Height!<p>';
						$error = 'true';
					}
					elseif($get_array['sampling_height'] == '0.00'){
						echo '<p>Warning: Sampling Height Is Set To 0.00 cm Above The Ground. Please Check This Is True<p>';
					}
					else{
						//check that sampling height is a number
						$regrex_check_sh  = '/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/'; //this can be zero
						if (!preg_match("$regrex_check_sh", $get_array['sampling_height'])){
							echo '<p>ERROR: You Must Enter Valid Sampling Height. Please Check Your Number.<p>';
							$error = 'true';
						}
					}		
				}
				else{
					//if you are a fungal or bacteria isolate
					if(($get_array['fRate'] != '0') || ($get_array['fRate_eod'] != '0')){//if update page
						echo '<p>ERROR: Flow Rate for Samples Other Than Air Samples Should Be Zero!<p>';
						$error = 'true';
					}
				}
				
				//check collector names
				if(isset($get_array['collector'])){
					$array=$get_array['collector'];
					$check = check_collector_names($array,'true');
					if($check['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid Collector Name(s). Please Check Name(s)<p>';
						$error = 'true';
					}
				}
				
				//check DNA Extractor Name(s)
				if(isset($get_array['dExtrName'])){
					$array2=$get_array['dExtrName'];
					$check2 = check_collector_names($array2,'false');
					if($check2['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid DNA Extractor Name(s). Please Check Names(s)<p>';
						$error = 'true';
					}
				}

				//check RNA Extractor Name(s)
				if(isset($get_array['rExtrName'])){
					$array3=$get_array['rExtrName'];
					$check3 = check_collector_names($array3,'false');
					if($check3['boolean'] == 'false'){
						echo '<p>ERROR: You Must Enter Valid RNA Extractor Name(s). Please Check Names(s)<p>';
						$error = 'true';
					}
				}

				//cat together sample name here and then check if it exists
				//also cat together a sort name
				$regrex_check_sn  = '/^[0-9]{3}$/';
				if (!preg_match("$regrex_check_sn", $get_array['sample_number'])){
					echo '<p>ERROR: You Must Enter Valid Sample Number. Please Check Your Number.<p>';
					$error = 'true';
				}
				
				
				//New check name based on project name and sample number
				if($check_for == 'insert_sample'){
					$p_sample_name = $get_array['sdate1'].$get_array['projName'].$get_array['sType'].$get_array['sample_number'];
					if(($get_array['projName'] && $get_array['sample_number']) != NULL){
						$param1 = "%{$get_array['projName']}%";
						$param2 = "%{$get_array['sample_number']}";
						$stmt1 = $dbc->prepare("SELECT sample_name FROM sample WHERE sample_name LIKE ? AND sample_name LIKE ?");
						$stmt1->bind_param("ss", $param1,$param2);
					
						$stmt1->bind_result($col1);
											
						if ($stmt1->execute()){
							   $stmt1->bind_result($name);
							   if ($stmt1->fetch()){
							   		#echo "Name: {$name}<br>";
							   		echo "<p>ERROR: ".htmlspecialchars($p_sample_name)." cannot be added. Sample number for this project already exisits. Please check name.".'</p><br>';
									$error = 'true';	
								}
						} 
						else {
							$error = 'true';
							echo "new error";
							die('execute() failed: ' . htmlspecialchars($stmt1->error));
											
						}
						$stmt1 -> close();
					}
				}

				return $error;
				
				
			
}	
?>

</body>
</html>