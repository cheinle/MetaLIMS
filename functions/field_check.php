

<?php	//function to check fields have appropriate info
function field_check($get_array,$check_for){
				include ('database_connection.php');
				include("../functions/check_collector_names.php");

				$error = 'false';

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
				if ($get_array['media'] == '0') {
					echo '<p>ERROR: You must select a Media Type!<p>';
					$error = 'true';
				}
				
				if ($get_array['sType'] == '0') {
					echo '<p>ERROR: You must enter a Sample Type!<p>';
					$error = 'true';
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