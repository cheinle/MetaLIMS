<?php
	 include('../database_connection.php');

	$sample_type = $_GET['sample_type'];
	if($sample_type == 'Amplicon'){
		echo '
		<h3 class="checkbox-header">Amplicon Type:</h3>
		<div class="vert-checkboxes">
		<label class="checkbox-label"><input type="radio" name="amplicon_type" value="Amplicon16S"/>Amplicon-16S</label>   
		<label class="checkbox-label"><input type="radio" name="amplicon_type" value="Amplicon18S"/>Amplicon-18S</label>
		</div>
		<div class="vert-checkboxes">
		<label class="checkbox-label"><input type="radio" name="amplicon_type" value="Amplicon18S"/>ITS</label>  	
		<label class="checkbox-label"><input type="radio" name="amplicon_type" value="AmpliconOther"/> Amplicon-other</label>
		</div>
		<label class="textbox-label">Other Amplicon Type:</label>
		<input type="text" id = "seqOther" name="seqOther" placeholder="Enter Other Amplicon Type" value=""/>
		<label class="textbox-label">Left Primer Set Name:</label>
		<input type="text" name="primerL" placeholder="Left Name" value=""/>
		<label class="textbox-label">Right Primer Set Name:</label>
		<input type="text" name="primerR"  placeholder="Right Name" value=""/>';
			
	}
?>
