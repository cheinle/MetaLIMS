<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../../index.php');
include('add_a_user_thing_js.php');

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
	<h3>Add Admin Created Fields To Form</h3>
	</div>
	<?php //button
		//add a select
			//tell me what you want the label to be and a comma seperated list of dropdown values
		//add a text box
			//tell me what you want the label to be
			
			
			
	?>
		<form class = "registration" id="form" name="form">
			<div class="container-fluid">
  				<div class="row">  	
					<fieldset>
					<LEGEND><b>Sample Collection Info</b></LEGEND>
					<pre>* required field  + required only for dropdown entries<br></pre>
			
						<div class="col-xs-6">
						<div id="user_things">
						<label class="textbox-label">Enter Label Name:*</label>
						<input type="text" "name="label" id="label" Placeholder = "Label Name" value=""/>
						<br>
						
						<label class="textbox-label">Select Field Type:*</label>
						<select id='field_type' name='field_type'>
							<option value='0'>-Select-</option>
							<option value='text_input'>Text Input</option>
							<option value='select'>Dropdown Entry</option>
						</select>
						
						<label class="textbox-label">Enter Dropdown Options:+</label>
						<input type="text" "name="option" id="options" placeholder = "Option1; Option2; Option3" value=""/>
						</div><!--end of div id "user things...is this needed?-->
						
					
				
						<script type="text/javascript">
						</script>
						</div><!--end of col-xs-6-->
					</fieldset>
				</div><!--end of row-->
			</div><!--end of container fluid-->
			<input class="button" id="submit" type="button" value="submit"><br>
		</form>	
	</body>	
</html>
