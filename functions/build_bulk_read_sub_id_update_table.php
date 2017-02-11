<?php	

function build_bulk_read_sub_id_update_table($stmt,$root){

	$submitted = 'false';
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'functions/convert_time.php');
	//include('convert_header_names.php');
	
	include($path.'/functions/dropDown_update_for_read_subm.php');
	
	echo "<style>
	form.form-horizontal select{
	  font-family: Arial;
	  font-size: 12px;
	  border:1px solid #cccccc;
	  margin:2px 0px 10px 10px;
	  color:#00abdf;
	  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	  border-radius: 5px;
	   
	  -moz-appearance: none;//remove dropdown arrow because cannot get the arrow to round
	  text-indent: 0.01;
	  text-overflow: ellipsis;
	}
	form.form-horizontal input[type=text]{
	  font-family: Arial;
	  font-size: 12px;
	  border:1px solid #cccccc;
	  margin:2px 0px 10px 10px;
	  color:#00abdf;
	  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	  border-radius: 5px;
	   
	  -moz-appearance: none;//remove dropdown arrow because cannot get the arrow to round
	  text-indent: 0.01;
	  text-overflow: ellipsis;
	}
	</style>";
	
	echo '<form class="form-horizontal" style="width:100%;margin-left: 0px;" onsubmit="return confirm(\'Do you want to submit the form?\');" action="bulk_insert_and_updates/read_submission/read_sub_bulk_update.php" method="POST">';
	echo '<pre>';
	echo '*Notice: Bulk Update will update all samples that have been checkmarked for Update/Delete';
	echo '</pre>';


	echo '<div class="container-fluid">';
  	echo '<div class="row">';  
	echo '<div class="col-sm-1">(Uncheck)</div>';
	echo '<div class="col-sm-1">Update</div>';
	echo '<div class="col-sm-1">Delete</div>';
	echo '<div class="col-sm-1">Sample Name</div>';
	echo '<div class="col-sm-1">Seq Sub Name</div>';
	echo '<div class="col-sm-1">Read Sub Name</div>';
	echo '<div class="col-sm-1">Read Sub Date</div>';
	echo '<div class="col-sm-1">Read Sub DB</div>';
	echo '<div class="col-sm-1">Submitter</div>';
	echo '<div class="col-sm-3">Type Of Experiment</div>';
	echo '</div>';

	
	
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
				echo '<div class = "row">';
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
				<div class="col-sm-1">
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_uncheck" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="uncheck" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'uncheck'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</div>
				<div class="col-sm-1">
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_update" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="update" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'update'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</div>
				<div class="col-sm-1">
 				<input type="radio" id="<?php echo $mod_sample_name;?>_<?php echo $old_id;?>_checkbox_delete" name="sample[<?php echo $sample_name.','.$old_id; ?>][checkbox]" value="delete" <?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {
 																																																 if(isset($_SESSION['sample_array'][$sample_name]['checkbox']) && htmlspecialchars($_SESSION['sample_array'][$sample_name]['checkbox']) == 'delete'){
 																																																 	echo "checked";
																																																 }
																																															}?>/> <?php #echo $sample_name ?>
				</div>
				<div class="col-sm-1"><?php echo $sample_name; ?></div>
				
				<?php echo '<div class="col-sm-1">'.$row['seq_id'].'</div>';?>
				
				<div class="col-sm-1"><input type="text" class = "form-control input-md" id="<?php echo $mod_sample_name;?>_id" name="sample[<?php echo $sample_name.','.$old_id; ?>][id]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['sample_array'][$sample_name]['id']);}else{echo $old_id;} ?>"/></div>
				
				<div class="col-sm-1"><input type="text" class= "form-control input-md" id="datepicker5-<?php echo $mod_sample_name.'-'.$old_id; ?>"  name="sample[<?php echo $sample_name.','.$old_id; ?>][subm_date]" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['date_submitted']);}else{ echo $subm_date;} ?>"/></div>
				<script>
					var sample_name = <?php echo json_encode("$mod_sample_name".'-'."$old_id"); ?>;
					sample_name = 'datepicker5-'+sample_name;
	
					var test = document.getElementById(sample_name).value;
					//alert(test);
					//you have the correct sample name, but datepicker isn't working.
					$('#'+sample_name).datepicker({ dateFormat: 'yy-mm-dd' }).val();
				</script>
				
				<div class="col-sm-1"><?php dropDown_update_for_read_subm('subm_db', 'read_subm_database', 'database_name','database_name','subm_db',"$sample_name","0","$sub_id",$root);?></div>
				<div class="col-sm-1"><input type="text" name="sample[<?php echo $sample_name.','.$old_id; ?>][submitter]" class= "form-control input-md" placeholder="Enter A Name" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['submitter']);}else{ echo $submitter;} ?>"/></div>
				<div class="col-sm-3"><?php dropDown_update_for_read_subm('type_of_experiement', 'read_subm_exp_types', 'exp_types','exp_types','type_exp',"$sample_name","0","$sub_id",$root);?></div>
			
				</div><!--close row-->
				<?php
					
			}	
		}else{
			echo '<script>Alert.render2("Sorry! No Results Found. Please Check Query");</script>';
		}
	}
		
			$stmt->close();
			echo '</div>'; //close container div
			echo '<button type="submit" name="submit" value="1" class="button"> Update Samples </button>';
			echo '</form>';	
			
			

}
	

?>
