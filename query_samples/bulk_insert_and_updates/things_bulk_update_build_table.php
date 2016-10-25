<?php
	include ('../../index.php');
	include ('../../database_connection.php');
?>	
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Bulk Update User Fields</title>
</head>

<body>
<?php
$id = htmlspecialchars($_GET['thing_select']);
$explode = explode(":",$id);
$thing_id = $explode[0];
$label = $explode[1];
$type = $explode[2];
$select_values = $explode[3];

echo '<div class="page-header">
<h3>Update Field: '.$label.'</h3>	
</div>';

echo '<form class="registration" onsubmit="return validate(this)" action="things_bulk_update_submit.php" method="POST">';
echo '<div>';
echo '<pre>';
echo '*Notice: Bulk update will update all samples that have been checkmarked<br>Please refresh page to clear if needed';
echo '</pre>';
echo '<table class = \'bulk\' id = \'top_table\'>';
echo '<thead>';
echo '<tr>';
echo '<th class="bulk">Sample Name <br><input type="checkbox" id="selecctall"/>(Select All)</th>';
echo '<th class="bulk">'.$label.'</th>';
echo '</tr>';
echo '</thead>';					
echo '<tbody>';
$query = "SELECT * FROM thing_storing JOIN sample ON sample.sample_name = thing_storing.sample_name";
$stmt = $dbc->prepare($query);
if ($stmt->execute()){
	if($stmt->fetch()){
		$meta = $stmt->result_metadata(); 
		while ($field = $meta->fetch_field()){
			$params[] = &$row[$field->name]; 
		} 
					
		call_user_func_array(array($stmt, 'bind_result'), $params); 
		$sort_the_samples = array();
		while ($stmt->fetch()) {
			$sample_name = $row['sample_name'];
			$sample_sort = $row['sample_sort'];
			$thing_id = 'thing'.$row['thing_id'];
			$thing_value = $row['thing_value'];
			$sort_the_samples[$sample_sort]['sample_name'] = $sample_name;
			$sort_the_samples[$sample_sort]['thing_value'] = $thing_value;
		}
	}
}		
			

ksort($sort_the_samples);

$build_select = 'N';
if($select_values != ''){
	$options = explode(";",$select_values);
	$build_select = 'Y';
}

foreach ($sort_the_samples as $sorted_name => $sname) {
		$mod_sample_name = preg_replace("/\//",'-',$sname['sample_name']);//jQuery cannot use slashes
		$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces

	echo '<tr>';
	echo '<td>';
	
		
	?><label class="checkbox-label" ><input type="checkbox" class = "checkbox1" id="<?php echo $mod_sample_name;?>_checkbox" name="sample[<?php echo $sname['sample_name']; ?>][checkbox]" value="<?php echo $mod_sample_name ?>" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == "false") {
 																																																 if(isset($_SESSION['sample_array'][$sample_name])){
 																																																 	echo "checked";
																																																}

																																																}?>><?php echo $mod_sample_name ?></label><br>
	<?php
																																																
	
	if($build_select == 'Y'){
		?><td><select id="<?php echo $mod_sample_name;?>_thing" name="sample[<?php echo $sname['sample_name']; ?>][<?php echo $thing ?>]">
		<?php

		$selected_option = $sname['thing'];
		echo '<option value="0">-Select-</option>';	
		foreach ($options as $key => $value) {
					echo '<option value="'.$value.'"', ($selected_option == $value) ? 'selected':'' ,'>'.$value.'</option>';	
			
		}
		echo '</select>';
		echo '</td>';
																																																
		
	}
	else{
		?>
		<td><input type="text" id="<?php echo $mod_sample_name;?>_thing" name="sample[<?php echo $sname['sample_name']; ?>][thing]" value="<?php echo $sname['thing_value'] ?>" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == "false") {
 																																																 if(isset($_SESSION['sample_array'][$thing_id])){
 																																																 	echo "checked";
																																																}

																																																}?>><br>
																																																
	<?php }	
	echo '</td>';
	echo '</tr>';
}
echo '<tr>';	
	
$stmt-> close();
echo '</tbody>';
echo '</table>';


//other fields to update
//check if form has  been submitted successfully or not
$submitted = 'true';
if(isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
	$submitted = 'false';
}
echo '<input type="text" style="visibility:hidden" class="hidden" name="thing_id" id="thing_id" value="'.$thing_id.'"/>';
echo '<input type="text" style="visibility:hidden" class="hidden" name="thing_type" id="thing_type" value="'.$type.'"/>';
?>
			
<button type="submit" name="submit" value="1" class="button"> Update Samples </button>
<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />

</div>
</form>

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

