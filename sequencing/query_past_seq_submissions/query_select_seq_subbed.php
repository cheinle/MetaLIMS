<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../database_connection.php');

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Query Past Sequencing Submission</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
</head>

 
<body>
<?php 
include('../../index.php');
include('../../functions/dropDown.php');

 ?>
<div class="page-header">
<h3>Query Past Sequencing Submission</h3>
</div>
	
<!--query by date range-->	
<form class="registration" action="query_results_seq_subbed_new.php" method="GET">
<div class="container-fluid">
<div class="row">
<div class="col-xs-6">
<fieldset>
<LEGEND><b>Display Sequencing Info:</b></LEGEND>
	<p><i>(Select Dates and/or Fields to Query By)</i></p>
	<label class="textbox-label">Select Start & End Date:</label>
	<input type="text" id="datepicker"  name="smydate" class="shrtfields"><input type="text" id="datepicker2"  name="emydate" class="shrtfields"></p>
	<script>
		$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	
	
	<p>
	<!--Project Name Dropdown-->
	<label class="textbox-label">Select Project Name:*</label>
	<?php
	$submitted = 'false';
	//url or $_GET name, table name, field name
	dropDown('projName', 'project_name', 'project_name','project_name',$submitted,$root);
	?>
	</p>
	
	<p>
	<h3 class="checkbox-header">Display All?:</h3>
	<div class="vert-checkboxes">
	<label class="textbox-label"><input type="checkbox" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="all">Display All</label>
	</div>
	</p>
	<!--submit button-->
	<button type="submit" class="button" name="submit" value="1"> Submit </button>
</fieldset>
</div></div></div>
</form>
</body>
</html>
