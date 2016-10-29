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
</head>

 
<body>
<?php  ?>
<div class="page-header">
<h3>Label Preperation</h3>
</div>


<form class="registration" action="results_to_xls_for_labels.php" method="GET">
<fieldset>
<LEGEND><b>  Display Sample Label Info:</b></LEGEND>
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content =="xls") echo "checked";?> value="xls">Create QR Codes</label>
	<!--submit button-->
	<p><button type="submit" class="button" name="submit_labels" value="1"> Submit </button></p>
</fieldset>
</form>
		
