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
				<!--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>-->		
</head>
<body class="update">
	<!--<script type="text/javascript">var root = "<?php echo $root; ?>";</script>-->
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
			echo '<button class="button" type=button onClick="parent.location=\'/series/dynamic/mymicrobiomes/sample_update/sample_update_lookup.php\'" value="Go Back">Go Back</button>';
			$error = 'true';
	}
	if ($error != 'true') {

				$transaction_time = date_default_timezone_set("Asia/Singapore");
				$transaction_time = date("Y-m-d H:i:s");
	
?>				
				<form  class="registration" onsubmit="return validate(this)" action="sample_update_lookup3_jquery.php" method="GET">
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
				
				
				<br>* = required field <br>
				<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>
				<div id='samplename_availability_result'></div>  
				<fieldset>
				<LEGEND><b>Sample Name</b></LEGEND>
				<div class="col-xs-6">
				<p>
				<label class="textbox-label">Sample Name:*</label>
				<input type="text" name="sample_name" id="sample_name" data-toggle="popover" title="Tip:" data-content="Unable to edit sample name. Please select Go Back button to select a different sample or go to Insert Sample tab to enter a new sample. Sample Name is automatically re-created if name components are updated" placeholder="yyyy/mm/dd[project name][sample_type][sample number-000]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_name',$dbc);}?>" readonly />
				</p>
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
				
				<!--------------------------------------fragment-1----------------------------------->
				<div id="fragment-1">
				<fieldset>
				<LEGEND><b>Sample Collection Info</b></LEGEND>
				<div class="col-xs-6">	
				<p>
				<label class="textbox-label">Sample Number:*</label>
				<input type="text" name="sample_number" id="sample_number"  placeholder="[001]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_num',$dbc);}?>" />
				</p>
				
				<!--Barcode insert field-->
				<p>
				<label class="textbox-label">Barcode:(optional)</label><br>
				<input type="text" name="barcode" id="barcode" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'barcode',$dbc);}?>"/>
				</p>

				<p>
				<!--Project Name Dropdown-->
				<label class="textbox-label">Select Project Name:*</label><br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('projName', 'project_name', 'project_name','project_name','project_name',"$parent_value",$root);
				?>
				</p>
				
				<!--location dropdown-->
				<p>
				<label class="textbox-label">Select Location:*</label><br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('loc', 'location', 'loc_name','loc_name','location_name',"$parent_value",$root);
				?>
				</p>
				
				<!--relative location dropdown-->
				<p>
				<label class="textbox-label">Select Relative Location:*</label><br/>
				<?php
				//$select_name,$table_name,$field_name,value,$s_field_name,$sample_name
				dropDown_update('rloc', 'relt_location', 'loc_name','loc_name','relt_loc_name',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<!--media type dropdown-->
				<label class="textbox-label">Media Type:*</label><br/>
				<?php
				//url or $_GET name, table name, field name, select_id, s field name, sample name
				dropDown_update('media', 'media_type', 'media_type','media_type','media_type',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<!--Collector Name input-->
				<label class="textbox-label">Enter Collector Name(s):*</label>
				<p class="clone"> <input type="text" name="collector[]" id="collector" class='input' placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'collector_name',$dbc);} ?>"/></p>
				</p>
				
				<!--Sampling Type insert field-->
				<p>
				<label class="textbox-label">Sample Type:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Please See FAQ"></i></label><br>
				<?php 
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update('sType', 'sample_type', 'sample_type_name','sample_type_id','sample_type',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<label class="textbox-label">Flow Rate-Start/End of Day:<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Coriolis or SASS: 300 l/m. Spin Air: 20-100 l/m"></i></label><br>
				<input type="text" name="fRate" id="fRate"  class = "shrtfields" placeholder="Enter A Flow Rate for SOD" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'flow_rate',$dbc);} ?>">
				<input type="text" name="fRate_eod" id="fRate_eod"  class = "shrtfields" placeholder="Enter A Flow Rate for EOD" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'flow_rate_eod',$dbc);} ?>">
				</p>

				
				<p>
				<!--storage freezer-->
				<label class="textbox-label">Select Storage Location:*</label><br/>
				<?php
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update_for_storage('oStore_temp', 'freezer', 'freezer_id','freezer_id', 'original',"$parent_value",'0',$root);
				dropDown_update_for_storage('oStore_name', 'drawer', 'drawer_id','drawer_id', 'original',"$parent_value",'1',$root);
				?>
				</p>
				
				<p>
				<label class="textbox-label">Height Above Floor:<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title=" Coriolis-113.5cm: SASS-156cm: Spin Air-151cm (all on tripods)" id='example'></i>
				</label><br>
				<input type="text" name="sampling_height" id="sampling_height" placeholder="Enter A Height Above Floor (cm)" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'sampling_height',$dbc);} ?>">
				</p>
				
				<!--Invisible Project Name Dropdown-->
				<input type="text" style="visibility:hidden" name="orig_projName" id="orig_projName" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'project_name',$dbc);}?>"/>
				
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
					
					<LEGEND><b>DNA Extraction Info</b></LEGEND>
					<div class="col-xs-6">
					
					<p>
					<label class="textbox-label">DNA Extraction Date:</label><br>
					<input type="text" id="d_extr_date"  name="d_extr_date" value="<?php echo text_insert_update_dt($parent_value,'d_extraction_date','date');?>"/>
					<script>
					$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
					</script>
					
					<p>
					<!--DNA Extraction Kit dropdown-->
					<label class="textbox-label">Select DNA Extraction Kit:</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name','dna_extract_kit_name',"$parent_value",$root);
					?>
					</p>
					
					<!--DNA Concentration-->
					<p>
					<label class="textbox-label">DNA Concentration (ng/ul):</label><br>
					<input type="text" name="dConc" id="dConc" placeholder="Enter A DNA Concentration. Note: (0 = ND = <0.0050ng/ul)" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_conc',$dbc);} ?>">
					</p>
					
						<!--Volume of DNA-->
					<p>
					<label class="textbox-label">Volume of DNA Elution (ul):</label><br>
					<input type="text" name="dVol" id="dVol" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume',$dbc);} ?>">
					</p>
	
	
					<!--Instrument used to measure DNA concentration-->
					<p>
					<label class="textbox-label">Instrument/Kit Used to Measure DNA Concentration:</label><br>
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('dInstru', 'quant_instruments', 'kit_name','kit_name','d_conc_instrument',"$parent_value",$root);
					?>
					</p>
	
					<!--Volume of DNA to measure DNA conc-->
					<p>
					<label class="textbox-label">Volume of DNA Used for Measure DNA Concentration(ul):</label><br>
					<input type="text" name="dVol_quant" id="dVol_quant" placeholder="Enter A Volume" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume_quant',$dbc);}?>">
					</p>
					<!------------------------------------------------------------>
					<!--DNA -->
					<p>
					<label class="textbox-label">Location of DNA Extract:</label><br>
					</p>
					<?php
					//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
					dropDown_update_for_storage('dStore_temp', 'freezer', 'freezer_id','freezer_id', 'dna_extr',"$parent_value",'0',$root);
					dropDown_update_for_storage('dStore_name', 'drawer', 'drawer_id','drawer_id', 'dna_extr',"$parent_value",'1',$root);
					?>
					</p>
					
					<p>
					<!--Extractor Name input-->
					<label class="textbox-label">Enter Name of Person(s) Who Extracted DNA:</label>
					<p class="clone2"> <input type="text" name="dExtrName[]" id="dExtrName" class="input"  placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'dExtrName',$dbc);} ?>"/></p>
					<!--<p><a href="#" class="add2" rel=".clone2">Add More Names</a></p>
					</p>
					<script type="text/javascript">
					$(document).ready($(function(){
						var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
							$('a.add2').relCopy({ append: removeLink}); 
						})
					);
					</script>-->
					
					<p>
					<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3><br>
	 				<div class="vert-checkboxes">
	 				<label class="checkbox-label"><input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" class = "orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>No</label><br />
					</div>
					</p>
					
					<p>
					<h3 class="checkbox-header">Does DNA Extraction Sample Exist?:</h3><br>
					<div class="vert-checkboxes">
	 				<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>Yes,DNA Sample Exisits</label><br />
					<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?>/>No, DNA Has Not Been Extracted</label><br />
					<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?>/>No, DNA Sample Is Used Up</label><br />
					</div>
					</p>
					</div>
				</div>

				<div class="col-xs-6">
				<div id="rna_extraction">
				<LEGEND><b>RNA Extraction Info</b></LEGEND>
				
				<p>
				<label class="textbox-label">RNA Extraction Date:</label><br>
				<input type="text" id="r_extr_date"  name="r_extr_date" value="<?php echo text_insert_update_dt($parent_value,'r_extraction_date','date');?>"/>
				<script>
				$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				</script>
				
				<p>
				<!--RNA Extraction dropdown-->
				<label class="textbox-label">Select RNA Extraction Kit:</label>
				<br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name','rna_extract_kit_name',"$parent_value",$root);
				?>
				</p>
				
				<!--RNA Concentration-->		
				<p>
				<label class="textbox-label">RNA Concentration (ng/ul):</label><br>
				<input type="text" name="rConc" id="rConc"  placeholder="Enter an RNA Concentration" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_conc',$dbc);} ?>">
				</p>
				
				<!--RNA Volume-->
				<p>
				<label class="textbox-label">Volume of RNA Elution (ul):</label><br>
				<input type="text" name="rVol" id="rVol" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume',$dbc);} ?>">
				</p>
		
				<!--Instrument used to measure RNA concentration-->
				<p>
				<label class="textbox-label">Instrument/Kit Used to Measure RNA Concentration:</label><br>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('rInstru', 'quant_instruments', 'kit_name','kit_name','r_conc_instrument',"$parent_value",$root);
				?>
				</p>
				
				<!--RNA Volume-->
				<p>
				<label class="textbox-label">Volume of RNA for Quantification(ul):</label><br>
				<input type="text" name="rVol_quant" id="rVol_quant" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume_quant',$dbc);} ?>">
				</p>
				
				<p>
				<label class="textbox-label">Location of RNA Extract:</label><br>
				</p>
				<?php
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update_for_storage('rStore_temp', 'freezer', 'freezer_id','freezer_id', 'rna_extr',"$parent_value",'0',$root);
				dropDown_update_for_storage('rStore_name', 'drawer', 'drawer_id','drawer_id', 'rna_extr',"$parent_value",'1',$root);
				?>
				
				<p>
				<!--Extractor Name input-->
				<label class="textbox-label">Enter Name of Person(s) Who Extracted RNA:</label>
				<p class="clone3"> <input type="text" name="rExtrName[]" id="rExtrName" class="input" placeholder="Comma Sepearted Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'rExtrName',$dbc);} ?>"/></p>
				<!--<p><a href="#" class="add3" rel=".clone3">Add More Names</a></p>
				</p>
				
				<script type="text/javascript">
				$(document).ready($(function(){
					var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
						$('a.add3').relCopy({ append: removeLink}); 
					})
				);
				</script>-->
				
				<p>
				<h3 class="checkbox-header">Does Original RNA Sample Still Exist?:</h3>
				<div class="vert-checkboxes">
 				<label class="checkbox-label"><input type="checkbox" class = "orig_sample_exist" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>No</label><br />
				</div>
				</p>
				
				<p>
				<h3 class="checkbox-header">Does RNA Extraction Sample Exist?:</h3><br>
 				<div class="vert-checkboxes">
 				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>Yes,RNA Sample Exisits</label><br />
				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?>/>No, RNA Has Not Been Extracted</label><br />
				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?>/>No, RNA Sample Is Used Up</label><br />
				</div>
				</p>
				</div>
			
				
				
				</fieldset>
				</div><!-- end of fragment 2-->
				
				<!-----------------------------------------fragment 3----------------------------------------->
				<div id="fragment-3">
					<fieldset>
						<LEGEND><b>Analysis</b></LEGEND>
						<p><a href="/series/dynamic/mymicrobiomes/update_tables/update_seq_info.php">Fill Out Sequencing Submission Info</a></p>
						<p>
						<!--Sequencing2 Dropdown-->
						<label class="textbox-label">Select Analysis Pipeline:</label>
						<br/>
						<?php
						//url or $_GET name, table name, field name
						dropDown_update('anPipe', 'analysis', 'analysis_name','analysis_name','analysis_name',$parent_value,$root);
						?>
						</p>
					</fieldset>
				</div><!--end fragment-3-->
				
				
				<!-----------------------------------------fragment 4----------------------------------------->
				<div id="fragment-4">
					<fieldset>
					<LEGEND><b>User Created Fields</b></LEGEND>
						<div class="col-xs-6">
							<input type="text" style="visibility:hidden" class="hidden" name="build_type" id="build_type" value="update"/>
							<input type="text" style="visibility:hidden" class="hidden" name="parent_value" id="parent_value" value="<?php echo $parent_value;?>"/>
							<div id="required_things">
							
							</div>
							<div id="user_things">
								
							</div>
						</div>
					</fieldset>
				</div><!--end fragment-4-->

				
				<!-----------------------------------------fragment 5------------------------------------------>
				<div id="fragment-5">
					<fieldset>
					<LEGEND><b>Notes</b></LEGEND>
						<div class="col-md-12">
							<p>
							<label class="textbox-label">Sample Notes:(optional)</label>
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