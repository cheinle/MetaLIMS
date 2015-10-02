<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Remove An Entry</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root; ?>
<div class="page-header">
<h3>Remove An Entry</h3>	
</div>
	<?php 

		//error && type checking 
		if(isset($_GET['submit']) && $_GET['submit'] == 'Delete'){
			
			$error = 'false';
			$submitted = 'false';
			
			$entry_type  = $_GET['eType'];
			$entry_to_delete = $_GET['eNameDelete'];
			
			
			if($entry_type == '0'){
					echo '<p>You Must Enter An Entry Type To Delete!<p>';
					$error = 'true';
			}
			
			if($entry_to_delete == ''){
					echo '<p>You Must Enter A Name To Delete!<p>';
					$error = 'true';
			}
			
			if($entry_type == 'dExtrKit'){
				$select_exists_query = "SELECT d_kit_name FROM dna_extraction WHERE d_kit_name = ?";
				$select_query = "SELECT sample_name FROM sample WHERE dna_extract_kit_name = ?";
				$update_query = "UPDATE dna_extraction SET visible = ? WHERE d_kit_name = ? ";
			}
			if($entry_type == 'Location'){
				$select_exists_query = "SELECT loc_name FROM location WHERE loc_name = ?";
				$select_query = "SELECT sample_name FROM sample WHERE location_name = ?";
				$update_query = "UPDATE location SET visible = ? WHERE loc_name = ? ";
			}
			if($entry_type == 'Media Type'){
				$select_exists_query = "SELECT media_type FROM media_type WHERE media_type = ?";
				$select_query = "SELECT sample_name FROM sample WHERE media_type = ?";
				$update_query = "UPDATE media_type SET visible = ? WHERE media_type = ? ";
			}
			if($entry_type == 'Relative Location'){
				$select_exists_query = "SELECT loc_name FROM relt_location WHERE loc_name = ?";
				$select_query = "SELECT sample_name FROM sample WHERE relt_loc_name = ?";
				$update_query = "UPDATE relt_location SET visible = ? WHERE loc_name = ? ";
			}
			if($entry_type == 'rExtrKit'){
				$select_exists_query = "SELECT r_kit_name FROM rna_extraction WHERE r_kit_name = ?";
				$select_query = "SELECT sample_name FROM sample WHERE rna_extract_kit_name = ?";
				$update_query = "UPDATE rna_extraction SET visible = ? WHERE r_kit_name = ? ";
			}
			
			
			if($entry_type == 'Sampler'){
				$select_exists_query = "SELECT sampler_name FROM sampler WHERE sampler_name = ?";
				$select_query = "SELECT sample_name FROM sample_sampler WHERE sampler_name = ?";
				$update_query = "UPDATE sampler SET visible = ? WHERE sampler_name = ? ";
			}
			if($entry_type == 'Sensor'){	
				$select_exists_query = "SELECT part_sens_name FROM particle_counter WHERE part_sens_name = ?";
				$select_query = "SELECT daily_date FROM daily_data2_particle_counter WHERE part_sens_name = ?";
				$update_query = "UPDATE particle_counter SET visible = ? WHERE part_sens_name = ? ";
			}
			
			//check that the entry actually exists
			$entry_exists_check = 'false';
			if($stmt_exists = $dbc->prepare($select_exists_query)){
				$stmt_exists -> bind_param('s', $entry_to_delete);
	
	  			if ($stmt_exists->execute()){
	  			
	    			$stmt_exists->bind_result($name);
	    			while ($stmt_exists->fetch()){
	        			echo "$name <br>";
						$entry_exists_check = 'true';
					}
				} 
				else {
					$error = 'true';
	    			die('Execute() failed: ' . htmlspecialchars($stmt_exists->error));
				}
			}
			else {
				$error = 'true';
	    		die('Prepare() failed: ' . htmlspecialchars($stmt_exists->error));	
			}
			$stmt_exists -> close();
			
			
				
			//check samples exists for this project
			$seen_attached_samples = 'false';
			if($stmt1 = $dbc->prepare($select_query)){
				$stmt1 -> bind_param('s', $entry_to_delete);
	
	  			if ($stmt1->execute()){
	  			
	    			$stmt1->bind_result($name);
	    			while ($stmt1->fetch()){
	        			echo "$name <br>";
						$error = 'true';
						$seen_attached_samples = 'true';
					}
				} 
				else {
					$error = 'true';
	    			die('Execute() failed: ' . htmlspecialchars($stmt1->error));
				}
			}
			else {
				$error = 'true';
	    		die('Prepare() failed: ' . htmlspecialchars($stmt1->error));	
			}
			$stmt1 -> close();

			//Offer to bulk update samples
			if($seen_attached_samples == 'true'){
				echo 'Do You Wish To Bulk Update The '.$entry_type.' For These Samples?';
			}
			
			//Tell User Your Entry Does Not Exists
			if($entry_exists_check == 'false'){
				echo $entry_to_delete.' Does Not Exist. Please Check Name And Type';
			}
			
			//update visible to 0
			$zero_visible = 0;
		    if($error == 'false'  && $entry_exists_check  == 'true'){
		    	$stmt_delete = $dbc -> prepare($update_query);
				$stmt_delete -> bind_param('is', $zero_visible,$entry_to_delete);
				$stmt_delete -> execute();
				$rows_affected_delete = $stmt_delete ->affected_rows;
				if($rows_affected_delete == 0){ //return zero on update ...will return the same if did not update anything 
					echo "You Successfully Deleted ".$entry_to_delete.'<br>';
					$submitted = 'true';
				}
				else{
					die('Delete failed: ' . htmlspecialchars($stmt_delete->error));
				}
			
				$stmt_delete -> close();		

			}
		}
	?>
	<form class="registration" action="remove_entries.php" method="GET">
	<p><i>* = required field</i></p>
	<div class="container-fluid">
	<fieldset>
  	<div class="row">
	<LEGEND><b>Delete Entry:</b></LEGEND>
	
  	<div class="col-xs-6">
	<!--Entry Type-->
	<p>
	<label class="textbox-label">Entry Type:*</label>
	<select name="eType">
		<option value='0'>-Select-</option>
		<option value='dExtrKit'>DNA Extraction Kit</option>
		<option value='Location'>Location</option>
		<option value='Media Type'>Media Type</option>
		<option value='Relative Location'>Relative Location</option>
		<option value='rExtrKit'>RNA Extraction Kit</option>
		<option value='Sampler'>Sampler</option>
		<option value='Sensor'>Sensor</option>
	</select>
	
	</p>
	
	<!--Entry Name-->
	<p>
	<label class="textbox-label">Old Entry Name To Delete:*</label>
	<input type="text" name="eNameDelete" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $entry_to_delete;} ?>">
	</p>
	
	</div><!--end of class = 'col-xs-6'-->

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="Delete">Delete</button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	</div><!--end of class = 'row'-->
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
		
	</form>
	
	

	
</body>
	
</html>
