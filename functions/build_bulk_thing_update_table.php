<?php	

//display table
function build_bulk_thing_update_table($stmt,$root,$selected_thing){

	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'config/js.php'); //was not being inherited correctly...just added here for now
	include($path.'functions/dropDown.php');
	include($path.'functions/find_thing_labels.php');

	$explode = explode(":",$selected_thing);
	$thing_id = $explode[0];
	$thing_label = $explode[1];
	$type = $explode[2];
	$select_values = $explode[3];
	
	//$thing_label = find_thing_label($thing_id);
	
	echo '<form class="registration" onsubmit="return validate(this)" action="bulk_insert_and_updates/things_bulk_update_submit.php" method="POST">';
	//echo '<div class = \'left\'>';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk update will update all samples that have been checkmarked<br>Please refresh page to clear if needed';
	echo '</pre>';
	echo '<table class = \'bulk\' id = \'top_table\'>';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="bulk">Sample Name <br><input type="checkbox" id="selecctall"/>(Select All)</th>';
	echo '<th class="bulk">'.$thing_label.'</th>';
	echo '</tr>';
	echo '</thead>';					
	echo '<tbody>';

	$sort_the_samples = array();
	if ($stmt->execute()){
   		$stmt->bind_result($sample_name,$sample_sort);
		while ($stmt->fetch()){
			$sort_the_samples[$sample_sort] = $sample_name;
		}
	}
	
	
	ksort($sort_the_samples);
	foreach ($sort_the_samples as $sorted_name => $sname) {
		$mod_sample_name = preg_replace("/\//",'-',$sname);//jQuery cannot use slashes
		$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces
		//echo $mod_sample_name;
		
		?>
		<tr>
		<td><label class="checkbox-label" >
			<input type="checkbox" 
			class = "checkbox1" 
			id="<?php echo $mod_sample_name;?>_checkbox" 
			name = "sample[<?php echo $sname; ?>][checkbox]"
			value="<?php echo $mod_sample_name ?>" 
			<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
					 if(isset($_SESSION['sample_array'][$sample_name])){
					 	echo "checked";
					 }

				  }?>>
			<?php echo $mod_sample_name ?></label><br></td>

	
	
		<td><input type="text" 
			id="<?php echo $mod_sample_name;?>_thing" 
			name="sample[<?php echo $sname; ?>][thing]" 
			value="<?php echo find_thing_values($sname,$thing_id) ?>" 
			<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == "false") {
				if(isset($_SESSION['sample_array'][$thing_id])){
					echo "checked";
				}

				}?>><br>

		</td>	
	</tr>
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
			
			<tr>
			<td>
			<button type="submit" name="submit" value="1" class="button"> Update Samples </button>
			<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
			</td>
			</tr>
			</table>
			
<?php
			echo '<input type="text" style="visibility:hidden" class="hidden" name="thing_id" id="thing_id" value="'.$thing_id.'"/>';
			echo '<input type="text" style="visibility:hidden" class="hidden" name="thing_type" id="thing_type" value="'.$type.'"/>';
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
			alert('ERROR: Some inputs are invalid. Please check fields and ensure at least one sample checkbox is checked');
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
        var top_table = document.getElementById("top_table");
       	var number_of_samples_checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
        if(number_of_samples_checked < 1){
        	valid = 'false';
        	//alert("Warning: Please select checkbox for samples to update");
        	top_table.style.background = "pink";
        }
        else{
       		top_table.style.background = "white";
        }
        
        //check that all checked have correct input
       var bulk_form = document.forms[0];
	   var txt = "";
	   var i;
	   for (i = 0; i < bulk_form.length; i++) {
	       if (bulk_form[i].checked) {
	           txt = bulk_form[i].value;
	           var type = document.getElementById("thing_type").value;
	           var input = document.getElementById(txt+'_thing');
	           var input_val = input.value;
		    	if(type == 'select'){
		    		if(input_val == '0'){
			      		input.style.background = "blue";
			       		valid = 'false';
			   		}
			   		else{
			   			input.style.background = "white";
			   		}
		    	}
		    	else{
		    		if(input_val == ''){
			      		input.style.background = "blue";
			       		valid = 'false';
			   		}
				    else{
			       		if(type == 'numeric_input'){
							if(isNumeric(input_val) == false){
								alert("ERROR: Value must be a number");
								input.style.background = "blue";
							   	valid = 'false';
							}
							else{
			             		input.style.background = "white";
			             	}
			       		}
						else{
							if(isNumeric(input_val) == true){
								alert("ERROR: Value should not be a number");
								input.style.background = "blue";
							   	valid = 'false';
							}
							else{
			             		input.style.background = "white";
			             	}
						}       	
				   	}
		    	}
			}
		}
		return valid; 
	}
	function isNumeric(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
</script>
