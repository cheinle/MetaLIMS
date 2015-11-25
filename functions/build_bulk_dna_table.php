<?php	

//display table
function build_bulk_dna_table($stmt,$root){
	include('convert_time.php');
	include('convert_header_names.php');
	include('text_insert_update.php');
	include('dropDown.php');
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'/config/js.php'); //was not being inherited correctly...just added here for now
	
	echo '<form class="registration" onsubmit="return confirm(\'Do you want to submit the form?\');" action="dna_bulk_update.php" method="POST">';
	//echo '<div class = \'left\'>';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked';
	echo '</pre>';
	echo '<table class = \'bulk\'>';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="bulk">Sample Name <br><input type="checkbox" id="selecctall"/>(Select All)</th>';
	echo '<th class="bulk">DNA Conc. (ng/uL)</th>';
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
						$data[] = array('sample_name' => $row['sample_name'], 'sample_sort' => $row['sample_sort'],'d_conc' => $row['d_conc']);
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
					$dna_conc = htmlspecialchars($row['d_conc']);
					?>
					<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_dna" name="sample[<?php echo $sample_name; ?>][dna]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['dna']);}else{echo $dna_conc;} ?>"></td>
					<?php
					
					?>
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
	}
		
				
			
			//}}}
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
			<th class="bulk">DNA Extraction Info:(Required)</th>
			
			<tr>
			<td>
			<p>
			<label class="textbox-label">DNA Extraction Date:</label><br>
			<input type="text" id="datepicker5"  name="d_extr_date" style='width:4.20in;height:30px;' placeholder="Enter Date" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['d_extr_date']);} ?>"/>
			<script>
			$('#datepicker5').datepicker({ dateFormat: 'yy-mm-dd' }).val();
			</script>
			</p>
			</td>
			</tr>
			
			<tr>
			<td>
			<p>
			<!--DNA Extraction Kit dropdown-->
			<label class="textbox-label">Select DNA Extraction Kit:</label>
			<br/>
			<?php
			//url or $_GET name, table name, field name
			
			dropDown('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name',$submitted);
			?>
			</p>
			</td>
			</tr>
			
			<!--Volume of DNA-->
			<tr>
			<td>
			<p>
			<label class="textbox-label">Volume of DNA Elution (ul):</label><br>
			<input type="text" name="dVol" class="fields" placeholder="Enter A Volume" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['dVol']);} ?>">
			</p>
			</td>
			</tr>
				
			<!--Instrument used to measure DNA concentration-->
			<p>
			<tr>
			<td>
			<label class="textbox-label">Instrument/Kit Used to Measure DNA Concentration:</label><br>
			<?php
			//url or $_GET name, table name, field name
			dropDown('dInstru', 'quant_instruments', 'kit_name','kit_name',$submitted);
			?>
			</p>
			</td>
			</tr>
				
			<!--Volume of DNA to measure DNA conc-->
			<p>
			<tr>
			<td>
			<label class="textbox-label">Volume of DNA Used for Measure DNA Concentration(ul):</label><br>
			<input type="text" name="dVol_quant" class="fields" placeholder="Enter A Volume" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['dVol_quant']);}  ?>">
			</p>
			</td>
			</tr>
				
			<!--DNA -->
			<p>
			<tr>
			<td class="textbox-label">
			<label class="textbox-label">Location of DNA Extract:(pick freezer and drawer owner)</label><br>
			
			<?php
			//url or $_GET name, table name, field name
			dropDown('dStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted);
			?>
			
			<select id="dStore_name" name ="dStore_name" class='fields'>
 				<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
 				echo '<option value='.$_GET["dStore_name"].'  echo "selected";}} ?>'.$_GET["dStore_name"].' </option>';
 				}else{
 					echo '<option value="0">-Select-</option>';
 				}?>
 			</select>	
 			</td>
			</tr>
 				
 			<p>
 			<tr>
			<td>
			<!--DNA Extractor Name input-->
			<label class="textbox-label">Enter Name(s) of Persons Who Extracted DNA:</label>
			<p class="clone2"> <input type="text" name="dExtrName[]" class='input' style='width:4.20in;' placeholder="First Name(s)" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['dExtrName']);}  ?>"/></p>
			<p><a href="#" class="add2" rel=".clone2">Add More Names</a></p>
			</p>
			</td>
			</tr>
			<script type="text/javascript">
			$(document).ready($(function(){
				var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
					$('a.add2').relCopy({ append: removeLink}); 
				})
			);
			</script>
 				
 			<p>
 			<tr>
			<td>
			<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3><br>
 			<div class="vert-checkboxes">
 			<label class="checkbox-label"></label="checkbox-label"><input type="checkbox" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="false" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['orig_sample_exist']) && $_SESSION['orig_sample_exist'] == 'false'){echo 'checked';}} ?>/>No</label>
 			<!--<input type="radio" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="true" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['orig_sample_exist']) && $_SESSION['orig_sample_exist'] == 'true'){echo 'checked';}} ?>/>Yes<br />-->
			</div>
			</p>
			</td>
			</tr>
			
			<p>	
			<tr>
			<td>
			<h3 class="checkbox-header">Does DNA Extraction Sample Exist?</h3><br>
 			<div class="vert-checkboxes">
 			<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="one" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['DNA_sample_exist']) && $_SESSION['DNA_sample_exist'] == 'one'){echo 'checked';}} ?>/>Yes,DNA Sample Exisits</label>
			<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="three" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {if(isset($_SESSION['DNA_sample_exist']) && $_SESSION['DNA_sample_exist'] == 'three'){echo 'checked';}}  ?>/>No, DNA Sample Is Used Up</label>
			</div>
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
