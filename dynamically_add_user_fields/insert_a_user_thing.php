<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../index.php');
include('insert_a_user_thing_js.php');

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Form Insert</title>
		<!--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>-->
	
	</head>
 
	<body>
	<div class="page-header">
	<h3>Add User UDF's to form</h3>
	
	<?php //button
		//add a select
			//tell me what you want the label to be and a comma seperated list of dropdown values
		//add a text box
			//tell me what you want the label to be
			
			
			
	?>

		<div id="user_things">User's Things:<br>
		<label class="textbox-label">Enter Textbox label name:</label><br>
		<input type="text" "name="label" id="label" value=""/>
		</div>
	
		<input class="button" id="text_insert" type="button" value="Insert Text Box"><br>
		
		<script type="text/javascript">

		
		</script>
			
	</body>
	
</html>
