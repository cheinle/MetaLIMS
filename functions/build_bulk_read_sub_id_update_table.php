<?php	

function build_bulk_read_sub_id_update_table($stmt,$root){
	

	$submitted = 'false';
include('convert_time.php');
	include('convert_header_names.php');
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'/functions/dropDown_update_for_read_subm.php');
	include($path.'/config/js.php');
	
	echo '<form class="bulk" onsubmit="return confirm(\'Do you want to submit the form?\');" action="read_sub_bulk_update.php" method="POST">';
	echo '<div>';
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked for Update/Delete';
	echo '</pre>';
	echo '<table class="bulky_bulk">';
	echo '<thead>';
	echo '<tr>';
	echo '<th class="bulk">(Uncheck)</th>';
	echo '<th class="bulk">Update</th>';
	echo '<th class="bulk">Delete</th>';
	echo '<th class="bulk">Sample Name </th>';
	echo '<th class="bulk">Seq Sub Name</th>';
	echo '<th class="bulk">Read Sub Name</th>';
	echo '<th class="bulk">Read Sub Date</th>';
	echo '<th class="bulk">Read Sub DB</th>';
	echo '<th class="bulk">Submitter</th>';
	echo '<th class="bulk">Type Of Experiment</th>';
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
										'sample_sort' => $row['sample_sort'],
										'subm_id' => $row['subm_id'],
										'subm_db' => $row['subm_db'],
										'subm_date' => $row['subm_date'],
										'submitter' => $row['submitter'],
										'type_exp' => $row['type_exp']
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
				$sub_id = htmlspecialchars($row['subm_id']);
				$subm_db = htmlspecialchars($row['subm_db']);
				$subm_date = htmlspecialchars($row['subm_date']);
				$submitter = htmlspecialchars($row['submitter']);
				$type_exp = htmlspecialchars($row['type_exp']);
				#echo $sub_id;
				$p_value = htmlspecialchars($row['sample_name']);
				$sample_name = $p_value;
				$mod_sample_name = preg_replace("/\//",'-',$sample_name);//jQuery cannot use slashes
				$mod_sample_name = preg_replace("/\s+/",'-',$mod_sample_name);//jQuery can also not use spaces
				
				$old_id = $sub_id;
				$_SESSION[$sample_name.','.$old_id]['old_id'] = $old_id;
				
				$old_id = preg_replace("/\//",'-',$old_id);//jQuery cannot use slashes
				$old_id = preg_replace("/\s+/",'-',$old_id);//jQuery can also not use spaces
				#echo $old_id;
				
				?>
				<td>
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_uncheck" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="uncheck" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'uncheck'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</td>
				<td>
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_update" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="update" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'update'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</td>
				<td>
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_delete" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="delete" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'delete'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</td>
				<td><?php echo $sample_name; ?></td>
				<?php
				echo '<td>'.$row['seq_id'].'</td>';

				
				
				?>
				<td><input type="text" class = "checkbox1" id="<?php echo $mod_sample_name;?>_id" name="sample[<?php echo $sample_name.','.$old_id; ?>][id]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['id']);}else{echo $old_id;} ?>"></td>
				
				<td>
				
				<input type="text" id="datepicker5_<?php echo $mod_sample_name.','.$old_id; ?>"  name="sample[<?php echo $sample_name.','.$old_id; ?>][subm_date]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['date_submitted']);}else{ echo $subm_date;} ?>"/>
				<script>
				var sample_name = <?php echo json_encode("$mod_sample_name,$old_id"); ?>;
				sample_name = 'datepicker5_'+sample_name;

				var test = document.getElementById(sample_name).value;
				//alert(test);
				//you have the correct sample name, but datepicker isn't working.
				$('#'+sample_name).datepicker({ dateFormat: 'yy-mm-dd' }).val();
				</script>
				</td>
				
				<td><?php dropDown_update_for_read_subm('subm_db', 'read_subm_database', 'database_name','database_name','subm_db',"$sample_name","0","$sub_id",$root);?></td>
				<td><input type="text" name="sample[<?php echo $sample_name.','.$old_id; ?>][submitter]" placeholder="Enter A Name" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['submitter']);}else{ echo $submitter;} ?>"></td>
				<td><?php dropDown_update_for_read_subm('type_of_experiement', 'read_subm_exp_types', 'exp_types','exp_types','type_exp',"$sample_name","0","$sub_id",$root);?></td>
				</tr>

				<?php
					
			}	
		}else{
			echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
		}
	}
		
			$stmt->close();
			echo '</tbody>';
			echo '</table>';
			echo '<button type="submit" name="submit" value="1" class="button"> Update Samples </button>';
			echo '</form>';	
			echo '</div>';
			
			

}
	

?>
