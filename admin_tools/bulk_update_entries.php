<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bulk Update Entries</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php');?>
<div class="page-header">
<h3>Bulk Update Entries</h3>	
</div>
	<?php 

		//error && type checking 
		if(isset($_GET['submit']) && $_GET['submit'] == 'Delete'){
			
			$error = 'false';
			$submitted = 'false';
			
			$entry_type  = $_GET['eType'];
			$old_entry_name = $_GET['eOldName'];
			$new_entry_name = $_GET['eNewName'];
			
			
			if($entry_type == '0'){
					echo '<p>You Must Enter An Entry Type To Update Samples!<p>';
					$error = 'true';
			}
			
			if($old_entry_name == ''){
					echo '<p>You Must Enter An Old Name To Update Samples!<p>';
					$error = 'true';
			}
			
			if($new_entry_name == ''){
					echo '<p>You Must Enter An New Name To Update Samples!<p>';
					$error = 'true';
			}
			
			
			//do you want to check that  both new and old exits?
			
			if($entry_type == 'dExtrKit'){
				$select_exists_query = "SELECT d_kit_name FROM dna_extraction WHERE d_kit_name = ? OR d_kit_name = ?";
				$update_query = "UPDATE dna_extraction SET visible = ? WHERE d_kit_name = ? ";
			}
			if($entry_type == 'Location'){
				$select_exists_query = "SELECT loc_name FROM location WHERE loc_name = ? OR loc_name = ?";
				$update_query = "UPDATE location SET visible = ? WHERE loc_name = ? ";
			}
			if($entry_type == 'Media Type'){
				$select_exists_query = "SELECT media_type FROM media_type WHERE media_type = ? OR media_type = ?";
				$update_query = "UPDATE media_type SET visible = ? WHERE media_type = ? ";
			}
			if($entry_type == 'Relative Location'){
				$select_exists_query = "SELECT loc_name FROM relt_location WHERE loc_name = ? OR loc_name = ?";
				$update_query = "UPDATE relt_location SET visible = ? WHERE loc_name = ? ";
			}
			if($entry_type == 'rExtrKit'){
				$select_exists_query = "SELECT r_kit_name FROM rna_extraction WHERE r_kit_name = ? OR r_kit_name = ?";
				$update_query = "UPDATE rna_extraction SET visible = ? WHERE r_kit_name = ? ";
			}
			
			
			if($entry_type == 'Sampler'){
				$select_exists_query = "SELECT sampler_name FROM sampler WHERE sampler_name = ? OR sampler_name = ?";
				$update_query = "UPDATE sampler SET visible = ? WHERE sampler_name = ? ";
			}
			if($entry_type == 'Sensor'){	
				$select_exists_query = "SELECT part_sens_name FROM particle_counter WHERE part_sens_name = ? OR part_sens_name = ?";
				$update_query = "UPDATE particle_counter SET visible = ? WHERE part_sens_name = ? ";
			}
			
			//check that both entries actually exists 
			$entry_exists_check = 'false';
			
			if($stmt_exists = $dbc->prepare($select_exists_query)){
				$stmt_exists -> bind_param('s', $entry_to_delete);
	
	  			if ($stmt_exists->execute()){
	  			
	    			$stmt_exists->bind_result($name);
					$counter = 0;
	    			while ($stmt_exists->fetch()){
	        			if($name == $old_entry_name || $name == $new_entry_name){
	        				$counter++;
	        			}
						
					}
					if($counter == 2){
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
			
			
			//Tell User Your Entry Does Not Exists
			if($entry_exists_check == 'false'){
				echo $old_entry_name.' OR '.$new_entry_name.' Does Not Exist. Please Check Name And Type';
			}
			
			//update samples with new entry
		    if($error == 'false'  && $entry_exists_check  == 'true'){
		    	$stmt_update = $dbc -> prepare($update_query);
				$stmt_update -> bind_param('ss', $zero_visible,$entry_to_delete);
				$stmt_update -> execute();
				$rows_affected_update = $stmt_update ->affected_rows;
				if($rows_affected_update == 0){ //return zero on update ...will return the same if did not update anything 
					echo "You Successfully Updated Samples To ".$new_entry_name.'<br>';
					$submitted = 'true';
				}
				else{
					die('Update Failed: ' . htmlspecialchars($stmt_update->error));
				}
			
				$stmt_update -> close();		

			}
		}
	?>
	<form class="registration" action="bulk_update_entries.php" method="GET">
	<p><i>* = required field </i></p>
	<div class="container-fluid">
	<fieldset>
  	<div class="row">
	<LEGEND><b>Update All Samples In A Group:</b></LEGEND>
	<pre>Bulk Update All Samples Containing One Field To A New Field. 
		
	This Feature Is Mainly Used To Enable Admin To Delete A Field Which Is Currently Being Used By Other Samples.
	
	Example: Sample1, Sample2 And Sample3 Are Stored In Freezer A. You Are Moving Labs
	And No Longer Want Freezer A To Be An Option. You Would First, Create A New Freezer 
	And Then Update Samples To That Freezer (At This Step).
	Then You Would Delete Freezer A </pre>
	
  	<div class="col-xs-6">
	<!--Entry Type-->
	<p>
	<label class="textbox-label">Field Name:*</label>
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
	<label class="textbox-label">Update Samples From:*</label>
	<input type="text" name="eOldName" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $oldEntry;} ?>">
	</p>
	
	<!--New Entry Name-->
	<p>
	<label class="textbox-label">Update Samples To:*</label>
	<input type="text" name="eNewName" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $newEntry;} ?>">
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
