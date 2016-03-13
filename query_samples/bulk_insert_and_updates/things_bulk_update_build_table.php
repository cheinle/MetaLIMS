<?php
	include ('../../index.php');
	include ('../../database_connection.php');
?>	
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Bulk Update User Fields</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
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
$query = "SELECT * FROM store_user_things JOIN sample ON sample.sample_name = store_user_things.sample_name";
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
			//echo '<tr>';	
			//foreach($row as $key => $value){
				//if($key == 'sample_name' || $key == $thing_id){
					//$p_value = htmlspecialchars($value);
					//echo '<td class = "reg">'.$p_value.'</td>';
					$sample_name = $row['sample_name'];
					$sample_sort = $row['sample_sort'];
					$thing = $row[$thing_id];
					$sort_the_samples[$sample_sort]['sample_name'] = $sample_name;
					$sort_the_samples[$sample_sort]['thing'] = $thing;
				//}	
				
			//}
			//echo '</tr>';
		}
		
	}
	
}		
			
			

ksort($sort_the_samples);

foreach ($sort_the_samples as $sorted_name => $sname) {
		$mod_sample_name = preg_replace("/\//",'-',$sname['sample_name']);//jQuery cannot use slashes
		$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces

?>
	<tr>
	<td>
	
		
	<label class="checkbox-label" ><input type="checkbox" class = "checkbox1" id="<?php echo $mod_sample_name;?>_checkbox" name="sample[<?php echo $sname['sample_name']; ?>][checkbox]" value="<?php echo $mod_sample_name ?>" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name])){
 																																																 	echo "checked";
																																																 }

																																																}?>><?php echo $mod_sample_name ?></label><br>
																																																
	<td><input type="text" id="<?php echo $mod_sample_name;?>_thing" name="sample[<?php echo $sname['sample_name']; ?>][thing]" value="<?php echo $sname['thing'] ?>" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$thing])){
 																																																 	echo "checked";
																																																 }

																																																}?>><br>
																																																
	</td>
	</tr>
<?php
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
        var top_table = document.getElementById("top_table");
       	var number_of_samples_checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
        if(number_of_samples_checked < 1){
        	valid = 'false';
        	top_table.style.background = "pink";
        }
        else{
       		top_table.style.background = "white";
        }
        
        //check that all checked have correct input
        var coffee = document.forms[0];
	    var txt = "";
	    var i;
	    for (i = 0; i < coffee.length; i++) {
	        if (coffee[i].checked) {
	            txt = coffee[i].value;
	            alert(txt);
	            var input = document.getElementById(txt+'_thing');
	            var input_val = input.value;
		     	if(input_val == ''){
			        input.style.background = "blue";
			        valid = 'false';
			    }
			    else{
			    	var type = document.getElementById("thing_type").value;
		        	if(type == 'numeric_input'){
			        	var regrex_check_sh2  =  input_val.match(/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/);//this can be zero
			        	alert(regrex_check_sh2);
						if (regrex_check_sh2 == null){
							alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
							input.style.background = "blue";
						    valid = 'false';
						}
						else{
							input.style.background = "white";
						}
		        	}
					else{
				 		input.style.background = "white";
				 	}       	
			    }
			}
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
