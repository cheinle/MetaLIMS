<?php
if(!isset($_SESSION)) { session_start(); }
$path = $_SESSION['include_path']; //same as $path
include ($path.'/functions/admin_check.php');
include ('../../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('add_a_user_thing_js.php');

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Add User Fields</title>
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
					<pre>
Add a user thing is used to add user created fields for recording on a per sample basis
	* required field  
	+ required only for text dropdown entries
	
Ex: Enter Label Name:        Blood Type
Select Field Type:           Text Dropdown
Enter Dropdown Options:      A;AB;B;O
Is Field Required For User?: Yes

Field 'Blood Type' will now be required to be filled in for each sample
</pre>
			
						<div class="col-xs-6">
						<label class="textbox-label">Enter Label Name:*</label>
						<input type="text" "name="label" id="label" Placeholder = "Label Name" value=""/>
						<br>
						
						<label class="textbox-label">Select Field Type:*</label>
						<select id='field_type' name='field_type'>
							<option value='0'>-Select-</option>
							<option value='text_input'>Text Input</option>
							<option value='numeric_input'>Numeric Input</option>
							<option value='select'>Text Dropdown</option>
							<!--<option value='numeric_select'>Numeric Dropdown</option>-->
						</select>
						
						<label class="textbox-label">Enter Dropdown Options:+</label>
						<input type="text" "name="options" id="options" placeholder = "Option1; Option2; Option3" value=""/>
						
						<label class="textbox-label">Is Field Required For User?:*</label>
						<select id='required' name='required'>
							<option value='0'>-Select-</option>
							<option value='Y'>Yes</option>
							<option value='N'>No</option>
						</select>
						
						</div><!--end of col-xs-6-->
					</fieldset>
				</div><!--end of row-->
			</div><!--end of container fluid-->
			<input class="button" id="submit" type="button" value="Submit"><br>
		</form>	
	</body>	
</html>
