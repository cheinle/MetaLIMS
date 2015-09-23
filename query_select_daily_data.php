<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>View Daily Data</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
</head>

 
<body>
<?php 
include('index.php');
include('functions/dropDown.php');
?>
<div class="page-header">
<h3>Query Daily Data</h3>
</div>


	
<!--query by date range-->	
<form class="registration" action="query_results_daily_data.php" method="GET">
<div class="container-fluid">
<div class="row">

<fieldset>
<LEGEND><b>Display Daily Data Info:</b></LEGEND>
	<div class="col-xs-6">
	<i>(Select Dates)</i><br>
	<label class="textbox-label">Select Start & End Date:</label>
	<input type="text" id="datepicker"  name="smydate" class="shrtfields"><input type="text" id="datepicker2"  name="emydate" class="shrtfields">
	<script>
		$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	
	<h3 class="checkbox-header">Display All?:</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="checkbox" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="all">Yes, Display All Daily Data</label>
	</div>
	</p>
	</div>
	<!--submit button-->
	<p><button type="submit" class="button" name="submit" value="1"> Submit </button></p>
</fieldset>
</div></div>
</form>
</body>
</html>
