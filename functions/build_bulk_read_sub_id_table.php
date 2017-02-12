<?php	

//display table
function build_bulk_read_sub_id_table($stmt,$root){
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'functions/convert_time.php');
	include($path.'functions/text_insert_update.php');
	include($path.'functions/dropDown.php');
	
	echo '<form class="registration" onsubmit="return validate(this)" action="bulk_insert_and_updates/read_submission/read_sub_bulk_insert.php" method="POST">';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked';
	echo '</pre>';
	echo '<table id = "datatable_bulk" class ="bulk" style="width:90%">';
	echo '<button type="button" id="selectAll" class="mini-button" style="float:left;margin-bottom: 0.5%;"><span class="sub"></span> Select All Samples </button>';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="bulk">  Sample Name</th>';
	echo '<th class="bulk">Seq Sub Name</th>';
	echo '<th class="bulk">Read Sub Name</th>';

	echo '</tr>';
	echo '</thead>';					
	echo '<tbody>';
	
	
	if ($stmt->execute()){
		if($stmt->fetch()){
			$meta = $stmt->result_metadata(); 
		    while ($field = $meta->fetch_field()){ 
		    	$params[] = &$row[$field->name]; 
		    } 
		
		    call_user_func_array(array($stmt, 'bind_result'), $params); 
		
			$sample_name = '';
			$stmt->execute();
		    while ($stmt->fetch()) {
		    	$counter = 0;
				foreach($row as $key => $value){
					$counter++;
					if($counter == 1){
						$data[] = array('sample_name' => $row['sample_name'],
										'seq_id' => $row['seq_id'],
										'sample_sort' => $row['sample_sort']
										);
					}
				}
			}
			
			// Obtain a list of columns
			foreach ($data as $key => $row) {
			    $volume[$key]  = $row['sample_sort'];
			    $edition[$key] = $row['sample_name'];
			}
			
			// Sort the data with volume ascending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $data);
		
			foreach($data as $key => $row){
				#echo '<tr class = "row_collapse">';
				echo '<tr>';
				$p_value = htmlspecialchars($row['sample_name']);
				$sample_name = $p_value;
				$mod_sample_name = preg_replace("/\//",'-',$sample_name);//jQuery cannot use slashes
				$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces
				
				?>
				<td>
 				<input type="checkbox" class = "checkbox1" id="<?php echo $mod_sample_name;?>_checkbox" name="sample[<?php echo $sample_name; ?>][checkbox]" value="checked" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'checked'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php echo $sample_name ?><br />
				</td>
				
				<?php
				echo '<td>'.$row['seq_id'].'</td>';

				if(isset($row['subm_id'])){//moved to a different table...right now no recall if an id exists
					$id = htmlspecialchars($row['subm_id']);
				}
				else{
					$id = '';
				}
				if(isset($row['subm_db'])){
					$db = htmlspecialchars($row['subm_db']);
				}
				else{
					$db = '';
				}
				if(isset($row['subm_date'])){
					$date = htmlspecialchars($row['subm_date']);
				}
				else{
					$date = '';
				}
				if(isset($row['submitter'])){
					$submitter = htmlspecialchars($row['submitter']);
				}
				else{
					$submitter = '';
				}
				
				
				?>
				<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_id" name="sample[<?php echo $sample_name; ?>][id]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo "test";echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['id']);}else{echo $id;} ?>"></td>
				</tr>
					
				<!--mark checkbox if you change a Read Submission name, check the checkbox-->
				<script type="text/javascript">
					
					$(document).ready(function(){  
						var sample_name = <?php echo(json_encode($mod_sample_name)); ?>;
						var sample_name_id = sample_name+'_id';
						var sample_name_checkbox = sample_name+'_checkbox';
	
			        	$('#'+sample_name_id).change(function(){ //on change event
			        		if($('#'+sample_name_id).val.length > 0){
			        			$('#'+sample_name_checkbox).prop('checked',true);
			        		}else{
			        			$('#'+sample_name_checkbox).prop('checked',false);
			        		}

						});
		
					});	
				</script>
				<?php
					
			}	
		}else{
			echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
		}
	}
		
			$stmt->close();
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			//other fields to update
			//check if form has  been submitted successfully or not...I don't think you're using this anymore?
			$submitted = 'true';
			if(isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
				$submitted = 'false';
			}
			?>
			<!--<div class = 'right'>--></div>
			<div id = 'bulk'>
			<table class = 'bulk' id='bulk'>
			<th class= 'bulk'>Read Submission Info:(Required)</th>
			
			<tr>
			<td>
			<p>
			<label>Date Submitted:</label><br>
			<input type="text" id="datepicker5"  name="subm_date" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['date_submitted']);} ?>"/>
			<script>
			$('#datepicker5').datepicker({ dateFormat: 'yy-mm-dd' }).val();
			</script>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<p>
			<label for="dExtKit">Database Submitted To:</label>
			<br/>
			<?php
			//url or $_GET name, table name, field name
			
			dropDown('subm_db', 'read_subm_database', 'database_name','database_name',$submitted,$root);
			?>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<p>
			<label>Person Who Made Submission:</label><br>
			<input type="text" name="submitter" class="fields" placeholder="Enter A Name" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'true') {echo htmlspecialchars($_SESSION['submitter']);} ?>">
			</p>
			</td>
			</tr>
				
			<p>
			<tr>
			<td>
			<label>Type Of Exeperiment Submitted:</label><br>
			<i> (For More Details On BioSample Types -https://submit.ncbi.nlm.nih.gov/biosample/template/)</i><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('type_of_experiement', 'read_subm_exp_types', 'exp_types','exp_types',$submitted,$root);
			?>
			</p>
			</td>
			</tr>
				
			<tr>
			<td>
			<button type="submit" name="submit" value="1" class="button"> Update Samples </button>
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
        
        //check that all checked have correct input
       var bulk_form = document.forms[0];

	   var i;
	   for (i = 0; i < bulk_form.length; i++) {
	       if (bulk_form[i].checked) {
	           checkbox_name = bulk_form[i].id;

	           var temp = new Array();
	           temp = checkbox_name.split("_");

	           var input = document.getElementById(temp[0]+'_id');
	           var input_val = input.value;
		
	    		if(input_val == ''){
		      		input.style.background = "blue";
		       		valid = 'false';
		   		}
		    	
			}
		}
		return valid; 
	}
	
</script>
