<?php	

//display table
function build_bulk_dna_table($stmt,$root){
	$path = $_SERVER['DOCUMENT_ROOT'].$root;		
	include($path.'functions/convert_time.php');
	//include('convert_header_names.php');
	include($path.'functions/text_insert_update.php');
	include($path.'functions/dropDown.php');

	
	echo '<form class="registration" onsubmit="return validate(this)"  action="bulk_insert_and_updates/dna_bulk_update.php" method="POST">';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked';
	echo '</pre>';
	echo '<table id = "datatable_bulk" class ="bulk" style="width:90%">';
	echo '<button type="button" id="selectAll" class="mini-button" style="float:left;margin-bottom: 0.5%;"><span class="sub"></span> Select All Samples </button>';
	
	echo '<thead>';
	echo '<tr>';
	echo '<th class="bulk">Sample Name</th>';
	echo '<th class="bulk">DNA Conc. (ng/uL)</th>';
	echo '</tr>';
	echo '</thead>';					
	echo '<tbody>';
	
	$sort_the_samples = array();
	$sample_dna_conc = array();
	if ($stmt->execute()){
   		$stmt->bind_result($sample_name,$dna_conc,$sample_sort);
		while ($stmt->fetch()){
			$sort_the_samples[$sample_sort] = $sample_name;
			$sample_dna_conc[$sample_name] = $dna_conc;
		}
	}
	
	
	ksort($sort_the_samples);
	foreach ($sort_the_samples as $sorted_name => $sname) {
		
		echo '<tr>';
		$mod_sample_name = preg_replace("/\//",'-',$sname);//jQuery cannot use slashes
		$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces
		?>
		<td>
 		<input type="checkbox" class = "checkbox1" id="<?php echo $mod_sample_name;?>_checkbox" name="sample[<?php echo $sname; ?>][checkbox]" value="checked" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {
 																																																 if(isset($_SESSION['sample_array'][$sname]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sname]['checkbox']) == 'checked'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php echo $sname ?><br />
		</td>
		<?php $dna_conc = htmlspecialchars($sample_dna_conc[$sname]);?>
		<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_dna" name="sample[<?php echo $sname; ?>][dna]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['sample_array'][$sname]['dna']);}else{echo $dna_conc;} ?>"></td>
					
		<!--mark checkbox if you change a DNA Concentration in the bulk DNA update-->
		<script type="text/javascript">
					
			$(document).ready(function(){  
				var sample_name = <?php echo(json_encode($mod_sample_name)); ?>;
				var sample_name_dna = sample_name+'_dna';
				var sample_name_checkbox = sample_name+'_checkbox';
	
	    		$('#'+sample_name_dna).change(function(){ //on change event
	    			if($('#'+sample_name_dna).val.length > 0){
			        	$('#'+sample_name_checkbox).prop('checked',true);
			        }else{
			        	$('#'+sample_name_checkbox).prop('checked',false);
			        }
				});
	
			});	
		</script>
		<?php 
		echo '</tr>';
	}

	$stmt->close();
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
	</div>
	<div id = 'bulk'>
	<table class = 'bulk' id='bulk' style="font-size: 16px;color:#666666;">
		
	<tr>
	<th class="bulk">DNA Extraction Info:(Required)</th>
	<th class="bulk">&nbsp;</th>
	</tr>
	
	<tr>
	<td align="left" style="padding-left:5%">
	DNA Extraction Date:
	</td>
	<td>
	<input type="text" id="datepicker5"  name="d_extr_date" placeholder="Enter Date" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['d_extr_date']);} ?>"/>
	<script>
	$('#datepicker5').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	</td>
	</tr>
	
	<tr>
	<td align="left" style="padding-left:5%">
	<!--DNA Extraction Kit dropdown-->
	Select DNA Extraction Kit:
	</td>
	<td>
	<?php
	//url or $_GET name, table name, field name
	dropDown('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name',$submitted,$root);
	?>
	</td>
	</tr>
	
	<!--Volume of DNA-->
	<tr>
	<td align="left" style="padding-left:5%">
	Volume of DNA Elution (ul):
	</td>
	<td>
	<input type="text" name="dVol" class="fields" placeholder="Enter A Volume" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['dVol']);} ?>">
	</td>
	</tr>
		
	<!--Instrument used to measure DNA concentration-->
	<tr>
	<td align="left" style="padding-left:5%">
	Instrument/Kit Used to Measure DNA Concentration:
	</td>
	<td>
	<?php
	//url or $_GET name, table name, field name
	dropDown('dInstru', 'quant_instruments', 'kit_name','kit_name',$submitted,$root);
	?>
	</td>
	</tr>
		
	<!--Volume of DNA to measure DNA conc-->
	<tr>
	<td align="left" style="padding-left:5%">
	Volume of DNA Used for Measure DNA Concentration(ul):
	</td>
	<td>
	<input type="text" name="dVol_quant" class="fields" placeholder="Enter A Volume" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['dVol_quant']);}  ?>">
	</td>
	</tr>
		
	<!--DNA -->
	<tr>
	<td align="left" style="padding-left:5%">
    Location of DNA Extract:(pick freezer and drawer owner)
	</td>
	<td>

	<?php
	//url or $_GET name, table name, field name
	dropDown('dStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root);
	?>
	<select id="dStore_name" name ="dStore_name" class='fields'>
		<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true'){
		echo '<option value='.$_GET["dStore_name"].'  echo "selected";}} ?>'.$_GET["dStore_name"].' </option>';
		}else{
			echo '<option value="0">-Select-</option>';
		}?>
	</select>	
	</td>
	</tr>
		
	<tr>
	<td align="left" style="padding-left:5%">
	<!--DNA Extractor Name input-->
	Enter Name(s) of Persons Who Extracted DNA:
	</td>
	<td>
	<p class="clone2"> <input type="text" name="dExtrName[]" class='input' placeholder="First Name(s)" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['dExtrName']);}  ?>"/></p>
	</td>
	</tr>

	<tr>
	<td align="left" style="padding-left:5%">
	Does Original Sample Still Exist?:
	</td>
	<td>

	<label class="checkbox-label"></label="checkbox-label"><input type="checkbox" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="false" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {if(isset($_SESSION['orig_sample_exist']) && $_SESSION['orig_sample_exist'] == 'true'){echo 'checked';}} ?>/>No</label>
	<!--<input type="radio" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="true" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['orig_sample_exist']) && $_SESSION['orig_sample_exist'] == 'true'){echo 'checked';}} ?>/>Yes<br />-->

	</td>
	</tr>
	

	<tr>
	<td align="left" style="padding-left:5%; word-wrap:wrap">
	Does DNA Extraction Sample Exist?
	</td>
	<td>
	<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="one" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {if(isset($_SESSION['DNA_sample_exist']) && $_SESSION['DNA_sample_exist'] == 'one'){echo 'checked';}} ?>/>Yes,DNA Sample Exisits</label>
	<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="three" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {if(isset($_SESSION['DNA_sample_exist']) && $_SESSION['DNA_sample_exist'] == 'three'){echo 'checked';}}  ?>/>No, DNA Sample Is Used Up</label>
	</td>
	</tr>

	
	<tr>
	<td>&nbsp;</td>
	<td align="right"><button type="submit" name="submit" value="1" class="button"> Update Samples </button></td>
	</tr>
	</table>
			
	</form>	
	</div>
			

 <?php } ?>

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
        var top_table = document.getElementById("datatable_bulk");
       	var number_of_samples_checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
        if(number_of_samples_checked < 1){
        	valid = 'false';
        	alert("Warning: Please select checkbox for samples to update");
        	top_table.style.background = "pink";
        }
        else{
       		top_table.style.background = "white";
        }
        
     
		        
         //check that second table is filled in
        var bottom_table = document.getElementById("bulk");
	    var input = bottom_table.getElementsByTagName( 'input' ); 
	    for ( var z = 0; z < input.length; z++ ) { 
	    	 var input_id = input[z];
	    	
	    	 var input_value = input_id.value;
			 if(input_value.length < 1){
			 	input_id.style.background = "blue";
			 }else{
			 	input_id.style.background = "white";
			 }
   		 } 
   		 
   		var select = bottom_table.getElementsByTagName( 'select' ); 
	    for ( var z = 0; z < select.length; z++ ) { 
	    	 var select_id = select[z];
	    	
	    	 var select_value = select_id.value;
			 if(select_value == 0){
			 	select_id.style.background = "blue";
			 }else{
			 	select_id.style.background = "white";
			 }
   		 } 

		if( $('input[type=radio]:selected').length == 0 ) {
		    alert('Please select if DNA Extraction Sample Exists.');
		    bottom_table.style.background = "pink";
		}else{
			bottom_table.style.background = "white";
		}
        
        //check that all checked have correct input
       var bulk_form = document.forms[0];

	   var i;
	   for (i = 0; i < bulk_form.length; i++) {
	       if (bulk_form[i].checked) {
	           checkbox_name = bulk_form[i].id;

	           var temp = new Array();
	           temp = checkbox_name.split("_");

	           var input = document.getElementById(temp[0]+'_dna');
	           var input_val = input.value;
		
	    		if(input_val == ''){
		      		input.style.background = "blue";
		       		valid = 'false';
		   		}
			    else{
		       		
					if(isNumeric(input_val) == false){
						alert("ERROR: Value must be a number");
						input.style.background = "blue";
					   	valid = 'false';
					}
					else{
	             		input.style.background = "white";
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
