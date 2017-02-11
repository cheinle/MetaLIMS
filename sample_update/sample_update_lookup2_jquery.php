<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) { session_start(); }
$root = $_SESSION['link_root'];
$path = $_SESSION['include_path']; //same as $path
include ($path.'database_connection.php');

?>
<!doctype html>
<html>
<head>
		<meta charset="utf-8">
		<title>Update Samples</title>	
</head>
<body class="update">
<?php	//drowpdown to select field_name from table_name


$parent_value = htmlspecialchars($_GET['sample_name']);
//include('../config/path.php');
include ($path.'index.php');
include($path.'functions/dropDown_update.php');
include($path.'functions/dropDown_update_for_storage.php');
include($path.'functions/text_insert_update.php');
include($path.'functions/text_insert_update_dt.php');
include($path.'functions/text_insert_update_storage_info.php');
include($path.'config/check_form_insert_js.php');
include($path.'config/check_form_update_js.php');
include($path.'config/check_required_user_things_js.php');
include($path.'config/check_sample_name.php');
include($path.'admin_tools/dynamically_add_user_fields/build_a_user_thing.php');

?>
<div class="page-header">	
	<h3>Update Samples</h3>
</div>
<?php
$testing = 'true';
if (isset($_GET['submit'])) {

	$error = 'false';
	if($parent_value == ''){
			echo '<p>ERROR: You must select a Sample Name<p>';
			?><button class="button" type=button onClick="parent.location='<?php echo $root;?>sample_update/sample_update_lookup_jquery.php'" value='Go Back'>Go Back</button><?php
				
			$error = 'true';
	}
	if ($error != 'true') {

				$transaction_time = date_default_timezone_set("Asia/Singapore");
				$transaction_time = date("Y-m-d H:i:s");
	
?>				
				<form  class="form-horizontal" onsubmit="return validate(this)" action="sample_update_lookup3_jquery.php" method="GET">
				<div id="tabs">
					  <ul>
					    <li><a href="#fragment-1"><span>Collection Info</span></a></li>
					    <li><a href="#fragment-2"><span>DNA/RNA Extraction</span></a></li>
					    <li><a href="#fragment-3"><span>Analysis</span></a></li>
					    <li><a href="#fragment-4"><span>User Created Fields</span></a></li>
					    <li><a href="#fragment-5"><span>Notes</span></a></li>
					  </ul>	
					
				<!--transaction time-->
				<input type="text" style="visibility:hidden" class="hidden" name="transaction_time" id="transaction_time" value="<?php echo $transaction_time ?>"/>
				
				<!--see if sample is part of a pool-->
				<input type="text" style="visibility:hidden" class="hidden" name="part_of_pool" id = "part_of_pool"  value="<?php echo text_insert_update($parent_value,'part_of_pool',$dbc); ?>"/>
				
				<!--see if sample is the end result of a pooling-->
				<input type="text" style="visibility:hidden" class="hidden" name="pooled_flag" id = "pooled_flag"  value="<?php echo text_insert_update($parent_value,'pooled_flag',$dbc); ?>"/>
				
				<br>* = required field <br>
				<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>
				<div id='samplename_availability_result'></div>  
				
				<div style="padding:1em 1.4em;">
				<fieldset>
				<LEGEND><b>Sample Name</b></LEGEND>
				<div class="col-xs-12 col-sm-6 col-md-6">
					
					<div class="form-group">
						 <div class="col-md-12">
					 		<!--<label class="col-md-3 control-label">Sample Name:*</label>-->
							<input type="text" name="sample_name" id="sample_name" class="form-control input-md" data-toggle="popover" title="Tip:" data-content="Sample Name is automatically re-created if name components are updated" placeholder="yyyy/mm/dd[project name][sample_type][sample number-000]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_name',$dbc);}?>" readonly />
						</div>
					</div>
					<script>
						$(document).ready(function(){
	    					$('[data-toggle="popover"]').popover({
	        					placement : 'right'
	    					});
						});
					</script>
					
					<style>
						.popover-content {
	    					font-style: bold;
	    					font-size: 14px;
						}
					</style>
				</div><!--end of col-xs-6-->
				</fieldset>
				</div>
				<!--------------------------------------fragment-1----------------------------------->
				<div id="fragment-1">
				<fieldset>
				<LEGEND><b>Sample Collection Info</b></LEGEND>
				<div class="col-xs-12 col-sm-6 col-md-6">	
				
				<div class="form-group">
				 <label class="col-md-3 control-label">Sample Number:*</label>
				 	<div class="col-md-8">
					<input type="text" name="sample_number" id="sample_number" class="form-control input-md" placeholder="[001]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_num',$dbc);}?>" />
				 </div>
				</div>
				
				<!--Barcode insert field-->
				<div class="form-group">
				   <label class="col-md-3 control-label">Barcode:(optional)</label>
				   <div class="col-md-8">
					<input type="text" name="barcode" id="barcode" class="form-control input-md" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'barcode',$dbc);}?>"/>
				   </div>
				</div>

				<!--Project Name Dropdown-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Select Project Name:*</label>
				 <div class="col-md-8">
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('projName', 'project_name', 'project_name','project_name','project_name',"$parent_value",$root);
					?>
				 </div>
				</div>
				
				<!--location dropdown-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Select Location:*</label>
				 <div class="col-md-8">
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('loc', 'location', 'loc_name','loc_name','location_name',"$parent_value",$root);
					?>
				 </div>
				</div>
				
				<!--relative location dropdown-->
				<div class="form-group">	 
					 <label class="col-md-3 control-label">Select Relative Location:*</label>
					 <div class="col-md-8">
						<?php
						//$select_name,$table_name,$field_name,value,$s_field_name,$sample_name
						dropDown_update('rloc', 'relt_location', 'loc_name','loc_name','relt_loc_name',"$parent_value",$root);
						?>
					</div>
				</div>
				
				
					 
				<!--media type dropdown-->
				<div class="form-group">
					 <label class="col-md-3 control-label">Media Type:*</label>
					 <div class="col-md-8">
						<?php
						//url or $_GET name, table name, field name, select_id, s field name, sample name
						dropDown_update('media', 'media_type', 'media_type','media_type','media_type',"$parent_value",$root);
						?>
					</div>
				</div>
				
				<!--Collector Name input-->
				<div class="form-group">
					 <label class="col-md-3 control-label">Enter Collector Name(s):*</label>
					 <div class="col-md-8">
						<p class="clone"> <input type="text" name="collector[]" id="collector" class='input form-control input-md' placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'collector_name',$dbc);} ?>"/></p>
					 </div>
				</div>
				
				<!--Sampling Type insert field-->
				<div class="form-group"> 
					<label class="col-md-3 control-label">Sample Type:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Please See FAQ"></i></label><br>
					<div class="col-md-8">
						<?php 
						//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
						dropDown_update('sType', 'sample_type', 'sample_type_name','sample_type_id','sample_type',"$parent_value",$root);
						?>
					</div>
				</div>
				
				
					
				<!--storage freezer-->
				<div class="form-group">
				 	<label class="col-md-3 control-label">Select Storage Location:*</label>
				  	<div class="col-md-8">
						<?php
						//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
						dropDown_update_for_storage('oStore_temp', 'freezer', 'freezer_id','freezer_id', 'original',"$parent_value",'0',$root);
						dropDown_update_for_storage('oStore_name', 'drawer', 'drawer_id','drawer_id', 'original',"$parent_value",'1',$root);
						?>
					</div>
				</div>
				
				<div class="form-group">
				  <label class="col-md-3 control-label" for="checkboxes">Does Original Sample Still Exist?</label>
				  <div class="col-md-4">
				  <div class="checkbox">
				    <label for="checkboxes-0">
				      <input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>
				      No
				    </label>
					</div>
				  </div>
				</div>
				
				<!--Invisible Project Name Dropdown-->
				<input type="text" style="visibility:hidden" name="orig_projName" id="orig_projName" class="form-control input-md" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'project_name',$dbc);}?>"/>
				
				</div><!--end of col-xs-6-->
				
				<?php 
				//add samplers
					include('sample_update_lookup_add_samplers.php');
				?>
				</fieldset>
				</div><!--end of fragment-1-->
				
				<!-----------------------------------------fragment 2---------------------------------------->
				<div id="fragment-2">
				<fieldset>
				<div id="dna_extraction">
					<div class="col-xs-12 col-sm-6 col-md-6">
					<LEGEND><b>DNA Extraction Info</b></LEGEND>
					
					
					<div class="form-group">
					 <label class="col-md-3 control-label">DNA Extraction Date:</label>
					  	<div class="col-md-8">
							<input type="text" id="d_extr_date"  name="d_extr_date" class="form-control input-md" value="<?php echo text_insert_update_dt($parent_value,'d_extraction_date','date');?>"/>
							<script>
							$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
							</script>
						</div>
					</div>
					
					<!--DNA Extraction Kit dropdown-->
					<div class="form-group">
					 <label class="col-md-3 control-label">Select DNA Extraction Kit:</label>
					  <div class="col-md-8">
						<?php
						//url or $_GET name, table name, field name
						dropDown_update('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name','dna_extract_kit_name',"$parent_value",$root);
						?>
					</div>
					</div>
					
					<!--DNA Concentration-->
					<div class="form-group">
						<label class="col-md-3 control-label">DNA Concentration (ng/ul):</label>
						 <div class="col-md-8">
						 <input type="text" name="dConc" id="dConc" class="form-control input-md" placeholder="Enter A DNA Concentration. Note: (0 = ND = <0.0050ng/ul)" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_conc',$dbc);} ?>">
						 </div>
					</div>
					
					<!--Volume of DNA-->
					<div class="form-group">
					 	<label class="col-md-3 control-label">Volume of DNA Elution (ul):</label>
					 	<div class="col-md-8">
							<input type="text" name="dVol" id="dVol" class="form-control input-md" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume',$dbc);} ?>">
						</div>
					</div>
	
	
					<!--Instrument used to measure DNA concentration-->
					<div class="form-group">
						<label class="col-md-3 control-label">Instrument/Kit Used to Measure DNA Concentration:</label>
						<div class="col-md-8">
							<?php
							//url or $_GET name, table name, field name
							dropDown_update('dInstru', 'quant_instruments', 'kit_name','kit_name','d_conc_instrument',"$parent_value",$root);
							?>
						</div>
					</div>
	
					<!--Volume of DNA to measure DNA conc-->
					<div class="form-group">
					 	<label class="col-md-3 control-label">Volume of DNA for Quantification(ul):</label>
					 	 <div class="col-md-8">
							<input type="text" name="dVol_quant" id="dVol_quant" class="form-control input-md" placeholder="Enter A Volume" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume_quant',$dbc);}?>">
						 </div>
					</div>
					<!------------------------------------------------------------>
					<!--DNA -->
					<div class="form-group">
					 		<label class="col-md-3 control-label">Location of DNA Extract:</label>
					 		<div class="col-md-8">
								<?php
								//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
								dropDown_update_for_storage('dStore_temp', 'freezer', 'freezer_id','freezer_id', 'dna_extr',"$parent_value",'0',$root);
								dropDown_update_for_storage('dStore_name', 'drawer', 'drawer_id','drawer_id', 'dna_extr',"$parent_value",'1',$root);
								?>
							</div>
					</div>
					
					<!--Extractor Name input-->
					<div class="form-group">
					 	<label class="col-md-3 control-label">Enter Name of Person(s) Who Extracted DNA:</label>
					 	 <div class="col-md-8">
						<p class="clone2"> <input type="text" name="dExtrName[]" id="dExtrName" class="input form-control input-md"  placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'dExtrName',$dbc);} ?>"/></p>
						</div>
					</div>
					
					<!--<div class="form-group">
					  <label class="col-md-3 control-label" for="checkboxes">Does Original Sample Still Exist?</label>
					  <div class="col-md-4">
					  <div class="checkbox">
					    <label for="checkboxes-0">
					      <input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>
					      No
					    </label>
						</div>
					  </div>
					</div>-->
					
					
					<div class="form-group">
				  	<label class="col-md-4 control-label" for="radios">Does RNA Extraction Sample Exist?</label>
					  <div class="col-md-4">
					  <div class="radio">
					    <label for="radios-0">
					      <input type="radio" name="DNA_sample_exist" id="radios-0" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>
					      Yes,DNA Sample Exists
					    </label>
						</div>
					  <div class="radio">
					    <label for="radios-1">
					      <input type="radio" name="DNA_sample_exist" id="radios-1" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?> />
					      No, DNA Has Not Been Extracted
					    </label>
						</div>
					  <div class="radio">
					    <label for="radios-2">
					      <input type="radio" name="DNA_sample_exist" id="radios-2" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?> />
					      No, DNA Sample Is Used Up
					    </label>
						</div>
					  </div>
					</div>
			
				
				</div><!--close dna extraction-->
				</div><!--close col-xs-6-->
			
				<div class="col-xs-12 col-sm-6 col-md-6">
				<LEGEND><b>RNA Extraction Info</b></LEGEND>	
				
				<div id="rna_extraction">
				
				<div class="form-group">
				 <label class="col-md-3 control-label">RNA Extraction Date:</label>
				  <div class="col-md-8">
					<input type="text" id="r_extr_date"  name="r_extr_date" class="form-control input-md" value="<?php echo text_insert_update_dt($parent_value,'r_extraction_date','date');?>"/>
					<script>
					$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
					</script>
				   </div>
				</div>

				<!--RNA Extraction dropdown-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Select RNA Extraction Kit:</label>
				  <div class="col-md-8">
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name','rna_extract_kit_name',"$parent_value",$root);
					?>
				  </div>
				</div>
				
				<!--RNA Concentration-->		
				<div class="form-group">
				 	<label class="col-md-3 control-label">RNA Concentration (ng/ul):</label>
				  	<div class="col-md-8">
					<input type="text" name="rConc" id="rConc"  class="form-control input-md" placeholder="Enter an RNA Concentration" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_conc',$dbc);} ?>">
					</div>
				</div>
				
				<!--RNA Volume-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Volume of RNA Elution (ul):</label>
				 <div class="col-md-8">
					<input type="text" name="rVol" id="rVol" class="form-control input-md" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume',$dbc);} ?>">
				</div>
				</div>
		
				<!--Instrument used to measure RNA concentration-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Instrument/Kit Used to Measure RNA Concentration:</label>
				 <div class="col-md-8">
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('rInstru', 'quant_instruments', 'kit_name','kit_name','r_conc_instrument',"$parent_value",$root);
					?>
				 </div>
				</div>
				
				<!--RNA Volume-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Volume of RNA for Quantification(ul):</label>
				  <div class="col-md-8">
					<input type="text" name="rVol_quant" id="rVol_quant" class="form-control input-md" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume_quant',$dbc);} ?>">
				  </div>
				</div>
				
				<div class="form-group">
					 <label class="col-md-3 control-label">Location of RNA Extract:</label>
					 <div class="col-md-8">
						<?php
						//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
						dropDown_update_for_storage('rStore_temp', 'freezer', 'freezer_id','freezer_id', 'rna_extr',"$parent_value",'0',$root);
						dropDown_update_for_storage('rStore_name', 'drawer', 'drawer_id','drawer_id', 'rna_extr',"$parent_value",'1',$root);
						?>
					</div>
				</div>
				
				<!--Extractor Name input-->
				<div class="form-group">
				 <label class="col-md-3 control-label">Enter Name of Person(s) Who Extracted RNA:</label>
				 <div class="col-md-8">
					<p class="clone3"> <input type="text" name="rExtrName[]" id="rExtrName" class="input form-control input-md" placeholder="Comma Sepearted Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'rExtrName',$dbc);} ?>"/></p>
				 </div>
				</div>
				
				<!--<div class="form-group">
				  <label class="col-md-3 control-label" for="checkboxes">Does Original Sample Still Exist?</label>
				  <div class="col-md-4">
				  <div class="checkbox">
				    <label for="checkboxes-0">
				      <input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>
				      No
				    </label>
					</div>
				  </div>
				</div>-->
				
				
				<div class="form-group">
				  <label class="col-md-4 control-label" for="radios">Does RNA Extraction Sample Exist?</label>
				  <div class="col-md-4">
				  <div class="radio">
				    <label for="radios-0">
				      <input type="radio" name="RNA_sample_exist" id="radios-0" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>
				      Yes,RNA Sample Exists
				    </label>
					</div>
				  <div class="radio">
				    <label for="radios-1">
				      <input type="radio" name="RNA_sample_exist" id="radios-1" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?> />
				      No, RNA Has Not Been Extracted
				    </label>
					</div>
				  <div class="radio">
				    <label for="radios-2">
				      <input type="radio" name="RNA_sample_exist" id="radios-2" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?> />
				      No, RNA Sample Is Used Up
				    </label>
					</div>
				  </div>
				</div>
				
				
				</div>
			
				
				
				</fieldset>
				</div><!-- end of fragment 2-->
				
				<!-----------------------------------------fragment 3----------------------------------------->
				<div id="fragment-3">
					<fieldset>
						<LEGEND><b>Analysis</b></LEGEND>
						<p><a href="../update_tables/update_seq_info.php">Fill Out Sequencing Submission Info</a></p>
						<div class="form-group">
							
						<!--Sequencing2 Dropdown-->
						 <label class="col-md-3 control-label">Select Analysis Pipeline:</label>
						  <div class="col-md-8">
					
							<?php
							//url or $_GET name, table name, field name
							dropDown_update('anPipe', 'analysis', 'analysis_name','analysis_name','analysis_name',$parent_value,$root);
							?>
						  </div>
						</div>
					</fieldset>
				</div><!--end fragment-3-->
				
				
				<!-----------------------------------------fragment 4----------------------------------------->
				<div id="fragment-4">
					<fieldset>
					<LEGEND><b>User Created Fields</b></LEGEND>
					<input type="text" style="visibility:hidden" class="hidden" name="build_type" id="build_type" value="update"/>
					<input type="text" style="visibility:hidden" class="hidden" name="parent_value" id="parent_value" value="<?php echo $parent_value;?>"/>	
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div id="required_things1">
							
							</div>
							<div id="user_things1">
								
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div id="required_things2">
								
							</div>
							<div id="user_things2">
								
							</div>
						</div>
					</fieldset>
				</div><!--end fragment-4-->

				
				<!-----------------------------------------fragment 5------------------------------------------>
				<div id="fragment-5">
					<fieldset>
					<LEGEND><b>Notes</b></LEGEND>
						<div class="col-md-12">
							<div class="form-group">
								 <div class="col-md-8">
							 	<label class="col-md-3 control-label">Sample Notes:(optional)</label>
								<textarea class="form-control" from="sample_form_update" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'notes',$dbc);} ?></textarea>
							</p>
						</div><!--close col-md-12-->
					</fieldset>
				</div> <!--close fragement-5-->
			
				
				</div> <!--end of tabs-->
				
				<p>
				<button class="button" type="submit" name="submit" value="2">Update </button>
				<button class="button" type=button onClick="parent.location='<?php echo $root;?>sample_update/sample_update_lookup_jquery.php'" value='Go Back'>Go Back</button>
				</p>
			</form>
			<script>
				$( "#tabs" ).tabs();
			</script>

<?php
		}
}
?>