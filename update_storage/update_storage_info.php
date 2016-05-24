
<?php 
include('../index.php');
include('../database_connection.php');
if(!isset($_SESSION)) { session_start(); } 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Storage Update</title>	
</head>
<body>
<div class="page-header">
	<h3>Update Storage Info</h3>	
</div>
<?php include($_SESSION['include_path'].'functions/dropDown.php'); ?>
		<form class="registration" id="sample_form_update" action="update_storage_info2.php" method="GET">
				
				<fieldset>
				<LEGEND><b>Lookup Sample Storage Info:</b></LEGEND>
				<div class="col-xs-6">
				<p>
				<label class="textbox-label">Sample Name:</label>
    			<input type="text" class="textbox-label" placeholder="Sample Name" name="sample_name" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off" value="<?php if (isset($_GET['submit'])){text_insert_update($parent_value,'sample_name');}?>"/>
				</p>
				<script>
					$(document).ready(function($){
    					$('#customerAutocomplte').autocomplete({
							source:'../suggest_name.php', 
							minLength:3
    					});
					});
				</script>
				</div>
				<!--submit button-->
				<button class="button" type="submit" name="submit" value="1">Lookup </button>
				<!--<p><button class="btn btn-success" type="submit" name="copy" value="1">Add and Copy </button></p>-->
				</fieldset>
					
		</form>
	
	

	
</body>
	
</html>
