<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		
		<title>Update Samples</title>

	</head>
	
	<body class="update">
		<?php include ('../index.php');?>
		<div class="page-header">
		<h3>Update Samples</h3>
		</div>
		
		<form class="registration" id="sample_form_update" action="sample_update_lookup2_jquery.php" method="GET">
				
				<fieldset>
				<LEGEND><b>Update Sample Info:</b></LEGEND>
				<div class="col-xs-6">
				<label class="textbox-label">Sample Name:</label>
    			<input type="text" placeholder="Sample Name" name="sample_name" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off" value="<?php if (isset($_GET['submit'])){text_insert_update($parent_value,'sample_name');}?>"/>
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
				</fieldset>
					
		</form>

    			
	</body>

</html>
