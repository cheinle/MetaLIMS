<?php 
if(!isset($_SESSION)) { session_start(); } 
include('../database_connection.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Storage Update</title>	
</head>
<body>

<?php 
include($_SESSION['include_path'].'index.php');
include($_SESSION['include_path'].'config/js.php');
include($_SESSION['include_path'].'functions/text_insert_update_storage_info.php');
include($_SESSION['include_path'].'functions/dropDown_update_for_storage.php');

$parent_value = $_GET['sample_name'];
$transaction_time = date_default_timezone_set("Asia/Singapore");//set timezone to the same as the server (was set at Europe/Berlin time)
$transaction_time = date("Y-m-d H:i:s");		
?>
		<div class="page-header">
		<h3>Update Storage Info</h3>	
		</div>
	
		<form class="registration" id="sample_form_update" action="update_storage_info3.php" method="GET">
		<div class="container-fluid">
  		<div class="row">
		<fieldset>
		<LEGEND><b>Update Storage Sample Info:</b></LEGEND>
		<div class="col-xs-6">
			
		<!--test transaction time-->
		<input type="text" style="visibility:hidden" name="transaction_time" value="<?php echo $transaction_time ?>"/>
		
		<p>
		<label class="textbox-label">Sample Name:*</label>
		<br>
		<input type="text" name="sample_name" id="sample_name" class="textbox-label" placeholder="yyyy/mm/dd[project name][sample_type][sample number-000]" value="<?php if (isset($_GET['submit'])){echo text_insert_update_stinfo($parent_value,'sample_name','storage_info',$root);}?>" readonly />
		</p>
		
		<p>
		<label class="textbox-label">Location of original sample:*</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
		dropDown_update_for_storage('oStore_temp', 'freezer', 'freezer_id','freezer_id', 'original',"$parent_value",'0',$root);
		dropDown_update_for_storage('oStore_name', 'drawer', 'drawer_id','drawer_id', 'original',"$parent_value",'1',$root);
		?>
		</p>
		
		<p>
		<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3>
		<div class="vert-checkboxes">
 		<label class="checkbox-label"><input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>No</label><br />
		</div>	
		</p>
		
		<!--DNA -->
		<p>
		<label class="textbox-label">Location of DNA Extraction:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
		dropDown_update_for_storage('dStore_temp', 'freezer', 'freezer_id','freezer_id', 'dna_extr',"$parent_value",'0',$root);
		dropDown_update_for_storage('dStore_name', 'drawer', 'drawer_id','drawer_id', 'dna_extr',"$parent_value",'1',$root);
		?>
		</p>
		
		<!--RNA-->
		<p>
		<label class="textbox-label">Location of RNA Extraction:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
		dropDown_update_for_storage('rStore_temp', 'freezer', 'freezer_id','freezer_id', 'rna_extr',"$parent_value",'0',$root);
		dropDown_update_for_storage('rStore_name', 'drawer', 'drawer_id','drawer_id', 'rna_extr',"$parent_value",'1',$root);
		?>
		</p>
		
		<button class="button" type="submit" name="submit" value="1">Update </button>
		<button class="button" type=button onClick="parent.location='<?php echo $root;?>update_storage/update_storage_info.php'" value='Go Back'>Go Back</button>
		<!--<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />-->
		</div>
		</fieldset>
		</div>
		</div>
		</form>
</body>
</html>
