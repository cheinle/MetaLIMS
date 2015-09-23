<?php	

//display table
function build_bulk_seqSub_table($stmt){
	include('convert_time.php');
	include('convert_header_names.php');
	include('text_insert_update.php');
	include('dropDown.php');
	include('../config/path.php');
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'/config/js.php'); //was not being inherited correctly...just added here for now;
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked';
	echo '</pre>';
	#echo '<form class="bulk" onsubmit="return confirm(\'Do you want to submit the form?\');" action="seqSub_bulk_update.php" method="GET">';
	echo '<form class="bulk" onsubmit="return validate(this)" action="seqSub_bulk_update.php" method="POST">';
	#echo '<form name=\'form-main\' onsubmit=\'return validate()\' action=\'\' method=\'post\'>';
	//echo '<div class = \'left\'>';
	echo '<div>';
	
	echo '<table class = \'bulk\'>';
	echo '<thead>';
	echo '<tr>';
	echo '<th class = \'bulk\'>Sample Name <br><input type="checkbox" id="selecctall"/>(Select All)</th>';
	echo '<th class = \'bulk\'>DNA Conc. (ng/uL)</th>';
	echo '<th class = \'bulk\'>Volume Of Aliquot(uL)</th>';
	echo '<th class = \'bulk\'>Does DNA Sample Still Exisit?<br><input type="checkbox" id="selecctallyes"/>Yes <input type="checkbox" id="selecctallno"/>No</th>';
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
						
						$data[] = array('sample_name' => $row['sample_name'], 'sample_sort' => $row['sample_sort'],'seq_dna_conc' => $row['seq_dna_conc'],'seq_vol' => $row['seq_vol']);
						$row['sample_name'] = preg_replace("/\//",'-',$row['sample_name']);
						$mod_data[] = array('sample_name' => $row['sample_name'], 'sample_sort' => $row['sample_sort'],'seq_dna_conc' => $row['seq_dna_conc'],'seq_vol' => $row['seq_vol']);
					}
				}
			}
			?><script type="text/javascript">var js_array = <?php echo json_encode($mod_data); ?>;</script><?php
			// Obtain a list of columns
			foreach ($data as $key => $row) {
			    $s_sort[$key]  = $row['sample_sort'];
			    $s_name[$key] = $row['sample_name'];
			}
			
			// Sort the sample names for output
			// Add $data as the last parameter, to sort by the common key
			array_multisort($s_sort, SORT_ASC, $s_name, SORT_ASC, $data);

				foreach($data as $key => $row){
					echo '<tr class = "row_collapse">';
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
					#$dna_conc = text_insert_update($sample_name,'d_conc'); 
					#$dna_conc = htmlspecialchars($row['seq_dna_conc']);
					#$vol = htmlspecialchars($row['seq_vol']);
					#if($vol == '0'){
					#	$vol = '';
					#}
					$dna_conc = '';
					$vol = '';
					?>
					<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_dna" name="sample[<?php echo $sample_name; ?>][dna]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['dna']);}else{echo $dna_conc;} ?>"></td>
					<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_vol" name="sample[<?php echo $sample_name; ?>][vol]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['vol']);}else{echo $vol;}?>"></td>
					
					
					<td><div id="<?php echo $mod_sample_name;?>_color_checkbox_div"><input type="radio" class = "checkbox2" id="<?php echo $mod_sample_name;?>_exists_yes" name="sample[<?php echo $sample_name; ?>][exists]" value="one" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['sample_array'][$sample_name]['exists']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['exists']) == 'one'){echo "checked='checked'";}}?>">Yes
					
					<input type="radio" class = "checkbox3" id="<?php echo $mod_sample_name;?>_exists_no" name="sample[<?php echo $sample_name; ?>][exists]" value="three" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['sample_array'][$sample_name]['exists']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['exists']) == 'three'){
 					
																																														 	echo "checked='checked'";;
																																																 }}?>">No</div></td>
																																															 
					<!---------form validation----->
					<!--if sample name is checked, make sure dna conc, vol and does sample exist are checked-->
					
					<script type="text/javascript">
					//var valid = 'true';
					var inputs = document.getElementsByTagName("input");
					for (var i = 0; i < inputs.length; i++) {
  						//alert(inputs[i].id);
					}
					$(document).ready(function(){ 
					
						var test = valid_check();
						
						function valid_check(){ 
							var valid_check = '';
							var sample_name = <?php echo json_encode("$mod_sample_name"); ?>;
							var checkbox_name = sample_name+'_checkbox';
							
							var dna_name = sample_name+'_dna';
							dna_name = document.getElementById(dna_name);
							
							var vol_name = sample_name+'_vol';
							vol_name = document.getElementById(vol_name);
							
							var exists_name_yes = sample_name+'_exists_yes';
							var exists_name_no = sample_name+'_exists_no';
							
							var color_checkbox_div = sample_name+'_color_checkbox_div';
							color_checkbox_div = document.getElementById(color_checkbox_div);
							
							//if checkbox is checked, make sure all the required fields are there
							$("#"+checkbox_name).change(function(){ 	
								if(document.getElementById(checkbox_name).checked){	
									Emptyvalidation(dna_name);
									Emptyvalidation(vol_name);
									if((!document.getElementById(exists_name_yes).checked) && (!document.getElementById(exists_name_no).checked)){
										color_checkbox_div.style.backgroundColor = "red";
										valid_check = 'false';
									}
									else{
										color_checkbox_div.style.backgroundColor = "white";
										valid_check = 'true';
									}	
								}
								else{
									dna_name.style.background = "white";
									vol_name.style.background = "white";
									color_checkbox_div.style.backgroundColor = "white";
									valid_check = 'true';
								}
							});
							
							//if user uses back button, keep error highlighting
							if(document.getElementById(checkbox_name).checked){	
									Emptyvalidation(dna_name);
									Emptyvalidation(vol_name);
									if((!document.getElementById(exists_name_yes).checked) && (!document.getElementById(exists_name_no).checked)){
										color_checkbox_div.style.backgroundColor = "red";
										valid_check = 'false';
									}
									else{
										color_checkbox_div.style.backgroundColor = "white";
										valid_check = 'true';
									}	
							}
							else{
									dna_name.style.backgroundColor = "white";
									vol_name.style.backgroundColor = "white";
									color_checkbox_div.style.backgroundColor = "white";
									valid_check = 'true';
							}
						
							//if you checkbox is checked and you change your DNA conc etc, then oncolor
							$("#"+sample_name+'_dna').change(function(){ 
								if(document.getElementById(checkbox_name).checked){		
									Emptyvalidation(dna_name);
								}
							});		
							$("#"+sample_name+'_vol').change(function(){ 
								if(document.getElementById(checkbox_name).checked){		
									Emptyvalidation(vol_name);
								}
							});	
							$("#"+sample_name+'_exists_yes').change(function(){ 
								if(document.getElementById(checkbox_name).checked){		
									if((!document.getElementById(exists_name_yes).checked) && (!document.getElementById(exists_name_no).checked)){
										color_checkbox_div.style.backgroundColor = "red";
										valid_check = 'false';
									}
									else{
										color_checkbox_div.style.backgroundColor = "white";
										valid_check = 'true';
									}	
								}
							});	
							$("#"+sample_name+'_exists_no').change(function(){ 
								if(document.getElementById(checkbox_name).checked){		
									if((!document.getElementById(exists_name_yes).checked) && (!document.getElementById(exists_name_no).checked)){
										color_checkbox_div.style.backgroundColor = "red";
										valid_check = 'false';
									}
									else{
										color_checkbox_div.style.backgroundColor = "white";
										valid_check = 'true';
									}	
								}
							});			
						}				
					    function Emptyvalidation(inputtxt){
					    	  
					    	if (inputtxt.value.length == 0){  
					    		inputtxt.style.backgroundColor =   'Yellow';
					    		valid_check = 'false';   
					         }  
					    	else{  
					    		
					    		if(!inputtxt.value.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,4})?\s*$/)){
		   	 								inputtxt.style.backgroundColor = 'Yellow';
		   	 								valid = 'false'
		   	 								alert("Whoops! DNA/Vol Should Can Only Be Up To 4 Decimal Places");
		   	 					}
		   	 					else{
		   	 						inputtxt.style.backgroundColor = 'White';  
					    			valid_check = 'true';
		   	 					}
					         }  
					   }  
					   
					   
					 });
					</script>
					<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['sample_array'][$sample_name]['exists']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['exists']) == 'one'){echo 'checked';}}?>	
					<!--mark checkbox if you change a DNA Concentration in the bulk DNA update-->
					<script type="text/javascript">
					
						$(document).ready(function(){  
							var sample_name = <?php echo(json_encode($mod_sample_name)); ?>;
							var sample_name_dna = sample_name+'_dna';
							var sample_name_checkbox = sample_name+'_checkbox';
	
			        		$('#'+sample_name_dna).change(function(){ //on change event
			        			$('#'+sample_name_checkbox).prop('checked',true);
			        			//alert(sample_name);  
							});
						});	
					</script>
			<?php
				echo '</tr>';
				}
			}else{
				echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
			}	
				
			
			//}
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
			<!--<div class = 'right'>--></div>
			<div = 'bulk'>
			<table class = 'bulk'>
			<th class = 'bulk'>Sequencing Submission Info:(Required)</th>
			
			<tr>
			<td>
			<!--Date Submitted-->
			<p>
			<label>Date Submitted:</label><br>
			<input type="text" id="datepicker2"  name="dtSub" class="fields" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['dtSub']);} ?>"/>
			<script>
			$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
			</script>
			</td>
			</tr>
			
			
			<tr>
			<td>
			<p>
			<!--Project Name Dropdown-->
			<label for="project_name">Select Project Name:</label><br/>
			<?php
			//url or $_GET name, table name, field name
			dropDown('projName', 'project_name', 'project_name','project_name',$submitted);
			?>
			</p>
					
			<tr>
			<td>
			<label>Sequencing Type:</label><br>
			<label>I:</label><br>
			<p>
			<label class="checkbox-label"><input type="radio" name="type" value="Amplicon16S" <?php if((isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false')){if((isset($_SESSION['type'])) && (($_SESSION['type']) == 'Amplicon16S')){echo "checked";}} ?>/>  Amplicon-16S</label>   
			<label class="checkbox-label"><input type="radio" name="type" value="Amplicon18S" <?php if((isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false')){if((isset($_SESSION['type'])) && (($_SESSION['type']) == 'Amplicon18S')){echo "checked";}} ?>/>  Amplicon-18S</label>   	
			<label class="checkbox-label"><input type="radio" name="type" value="AmpliconOther" <?php if((isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false')){if((isset($_SESSION['type'])) && (($_SESSION['type']) == 'AmpliconOther')){echo "checked";}} ?>/> Amplicon-other</label>
			<p class="adjust"></p>
			<input type="text" name="seqOther" class = "fields" placeholder="Enter Other Sequencing Type" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['seqOther']);} ?>"/><br>
			<input type="text" name="primerL" placeholder="Left Primer Set Name" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['primerL']);} ?>"/>
			<input type="text" name="primerR"  placeholder="Right Primer Set Name" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['primerR']);} ?>"/><br />
			<p class="adjust"></p>
			<label class="checkbox-label"><input type="radio" name="type" value="Metagenome"  <?php if((isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false')){if((isset($_SESSION['type'])) && (($_SESSION['type']) == 'Metagenome')){echo "checked";}}} ?>/>Metagenome</label>
			</p>
			
			<tr>
			<td>
		
			<p>
			<label>II:</label><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('seqName', 'sequencer_names', 'seqName','seqName',$submitted);
			?>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<p>
			<!--Library Prep Kit-->
			<label>Library Prep Kit:</label><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('libPK', 'library_prep_kit', 'lib_prep_kit','lib_prep_kit',$submitted);
			?>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<p>
			<label>Submission Made By:</label><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('submittedBy', 'users', 'user_id','user_id',$submitted);
			?>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<script type="text/javascript">
			    function validate(from) {
			    	
			    	//if you tried to submit, check the entire page for color?
			    	//return valid is false if you find it
			    	
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Are you sure you want to submit?');
				    }
				}
				
				function check_form(){
					var index;
					var a = js_array;
					var valid = 'true';
					for (index = 0; index < a.length; ++index) {
   	 					//console.log(a[index]["sample_name"]);
   	 					//change slashes to - 
   	 					var sample_name = a[index]["sample_name"];
   	 					var dna_name = sample_name+'_dna';
   	 					dna_name = document.getElementById(dna_name);
   	 					
   	 					var vol_name = sample_name+'_vol';
   	 					vol_name = document.getElementById(vol_name);
   	 					
   	 					var div_name = sample_name+'_color_checkbox_div';
   	 					div_name = document.getElementById(div_name);
   	 					
   	 					if(dna_name.style.backgroundColor == 'yellow'){
   	 						console.log(dna_name.style.backgroundColor);
   	 						valid = 'false'
   	 					}
   	 					if(vol_name.style.backgroundColor == 'yellow'){
   	 						valid = 'false'
   	 					}
   	 					if(div_name.style.backgroundColor == 'red'){
   	 						valid = 'false'
   	 					}
   	 				
					}
					return valid;
				}
			
			</script>
			<input type='submit' id="sub"  name ="submit" class="button" value='Update Samples' />
			</td>
			</tr>
			</table>
			</form>	
			</div>
<?php
			
		}
	

?>
