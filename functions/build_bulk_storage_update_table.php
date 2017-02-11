<?php	

//display table
function build_bulk_storage_update_table($stmt,$root){

	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'functions/dropDown.php');
	
	echo '<form class="registration" onsubmit="return validate(this)" action="bulk_insert_and_updates/storage_bulk_update.php" method="POST">';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk update will update all samples that have been checkmarked<br>Please refresh page to clear if needed';
	echo '</pre>';
	
	echo '<table id = "datatable_bulk" class ="bulk" style="width:90%">';
	echo '<button type="button" id="selectAll" class="mini-button" style="float:left;margin-bottom: 0.5%;"><span class="sub"></span> Select All Samples </button>';
	
	echo '<thead>';
	echo '<tr><th class="bulk">Sample Name</th></tr>';
	echo '</thead>';
	
						
	echo '<tbody>';

	$sort_the_samples = array();
	if ($stmt->execute()){
   		$stmt->bind_result($sample_name,$sample_sort);
		while ($stmt->fetch()){
			$sort_the_samples[$sample_sort] = $sample_name;
		}
	}
	
	//echo '';
	ksort($sort_the_samples);
	foreach ($sort_the_samples as $sorted_name => $sname) {
		
		?>
		<tr><td><label class="checkbox-label" ><input type="checkbox" name="sample_names[]" value="<?php echo $sname ?>" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name])){
 																																																 	echo "checked";
																																																 }
																																															}?>><?php echo $sname ?></label><br></td></tr>
	<?php
	}
	
	$stmt-> close();

	echo '</tbody>';
	echo '</table>';
	echo '</div>';
			
			
			
			//other fields to update
			//check if form has  been submitted successfully or not
			$submitted = 'true';
			if(isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
				$submitted = 'false';
			}
			?>
			<!--<div class = 'right'>--></div>
			<div id = 'bulk'>
			<table class = 'bulk'>
			<tr>
			<th class="bulk">Storage Location:(Required)</th>
			</tr>
		
			<p>
			<tr>
			<td>
			<label class="textbox-label">Sample Type:</label><br>
			<select name="sample_type">
			  <option value="0">-Select</option>
			  <option value="original">Original Sample</option>
			  <option value="dna">DNA Extraction</option>
			  <option value="rna">RNA Extraction</option>
			</select>
			</p>
			</td>
			</tr>

			<p>
			<tr>
			<td>
			<label class="textbox-label">Freezer:</label><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('Store_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root);
			?>
			</p>
			</td>
			</tr>
			
			
			<p>
			<tr>
			<td>
			<label class="textbox-label">Drawer:</label><br>
			<select id="Store_name" name ="Store_name" class='fields'>
 			<option value="0">-Select-</option>
 			</select>	
 			</td>
			</tr>
			
			<tr>
			<td>
			<button type="submit" name="submit" value="1" class="button"> Update Samples </button>
			<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
			</td>
			</tr>
			</table>
			
<?php
			#echo '<button type="submit" name="submit" value="1" class="btn btn-success"> Update Samples </button>';
			echo '</form>';	
			echo '</div>';

}
	

?>
<script type="text/javascript">
 	var name_check = 'true';
	function validate(form) {
		var valid = 'true';

		if(check_this_form() == 'false'){
			valid = 'false';
		}	
		if(valid == 'false'){
			alert('ERROR: Some inputs are invalid. Please check fields and ensure at least one sample is selected');
			return false;
		}
		else{
			return confirm('Sure You Want To Submit?');
		}
	}
    function check_this_form(){
       	var index;
        var valid = 'true';
        
         //check that at least one checkbox is selected
        var top_table = document.getElementById("datatable_bulk");
       	var number_of_samples_checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
        if(number_of_samples_checked < 1){
        	valid = 'false';
        	top_table.style.background = "pink";
        }
        else{
       		top_table.style.background = "white";
        }

		//check that selects are selected
        var selects = document.getElementsByTagName("select");
        var i2;
        for (i2 = 0; i2 < selects.length; i2++) {
        	selected = selects[i2].value;
            if(selected == '0'){
	        	selects[i2].style.background = "blue";
	            valid = 'false';
	        }
	        else{
	        	selects[i2].style.background = "white";
	        }
	    }
	    return valid;
	}

</script>