<?php 
include('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php');

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Query Selection</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
</head>

 
<body>
<?php include('../functions/check_box_tables.php');?>
<div class="page-header">
<h3>Query Pooled Samples</h3>
</div>

<!--query by date range-->	
<form class="registration" action="query_results_pooled.php" method="GET">
<div class="container-fluid">
<div class="row">
	
<fieldset>
<LEGEND><b>Display Pool Info:</b></LEGEND>
	<div class="col-xs-6">
	<i>(Select Dates and/or Fields to Query By)</i><br>
	<label class="textbox-label">Select Start & End Date:</label>
	<input type="text" id="datepicker"  name="smydate" class="shrtfields"><input type="text" id="datepicker2"  name="emydate" class="shrtfields">
	<script>
		$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	
	<label class="textbox-label"><p><strong>OR</strong></p></label>
	
	<label class="textbox-label">Sample Name:</label>
    <input type="text" placeholder="Sample Name" name="sample_name" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off" value="<?php if (isset($_GET['submit'])){text_insert_update($parent_value,'sample_name');}?>"/>
	</p>
	<script>
		$(document).ready(function($){
    		$('#customerAutocomplte').autocomplete({
				source:'../suggest_name.php', 
				minLength:3
    			});
		});
	</script>
	
	<label class="textbox-label"><p><strong>OR</strong></p></label>

	<h3 class="checkbox-header">Display All?:</h3>
	<div class="vert-checkboxes">
	<p>
	<label class="checkbox-label"><input type="checkbox" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="all">Yes, Display All Pooled Samples</label>
	</p>
	</div>
	</div>
	<!--submit button-->
	<p><button type="submit" name="submit" class="button" value="p_sample"> Submit </button></p>
</fieldset>
</div>
</div>
</form>
</body>
</html>
