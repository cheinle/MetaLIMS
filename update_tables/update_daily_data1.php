<?php include('../database_connection.php');
error_reporting(E_ALL); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Data Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/themes/redmond/jquery-ui.css" />	
</head>

<body>
<?php 
include('../index.php');
include('../functions/dropDown.php');
$submitted = 'false';
?>
<div class="page-header">
<h3>Pick Daily Data To Update</h3>
</div>

	<form name="form_name" class="registration" onsubmit="return validate(this)" action="update_daily_data_two.php" method="GET">
	<p><i>* = required field </i></p>
		
		<fieldset>
		<LEGEND><b>Date:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Daily Data DATE:*</label>
		<input type="text" id="datepicker"  name="mydate" class="fields" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>">
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		</p>
		
		<!--location dropdown-->
		<p>
		<label class="textbox-label">Select Location:*</label>
		<?php

		//url or $_GET name, table name, field name
		dropDown('loc', 'location', 'loc_name','loc_name',$submitted);
		?>
		</p>
		</div>
		</fieldset>

		<script type="text/javascript">
		
				//vailidate form
			    function validate(form) {
			    	//if you tried to submit, check the entire page for color
			    	//return valid is false if you find it
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		//return confirm('Are you sure you want to submit?');
				   		return true;
				    }
				}
				
				function check_form(){
					var valid = 'true';
	
					//check date
	   	 			var date = 'datepicker';
	   	 			var date_value = document.getElementById(date).value;
	   	 			if(date_value == ''){
	   	 				document.getElementById(date).style.backgroundColor = 'blue';
	   	 				valid = 'false'
	   	 			}
	   	 			else{
	   	 				document.getElementById(date).style.backgroundColor = 'white';
	   	 			}
	   	 			
	   	 			//check locatioin
	   	 			var location = 'loc';
	   	 			var location_value = document.getElementById(location).value;
	   	 			if(location_value == '0'){
	   	 				document.getElementById(location).style.backgroundColor = 'blue';
	   	 				valid = 'false'
	   	 			}
	   	 			else{
	   	 				document.getElementById(location).style.backgroundColor = 'white';
	   	 			}
					return valid;
				}
			
			</script>
			<input type='submit' id="sub" class="button" name ="submit" value='Submit'>
	
	</form>
	
	

	
</body>
	
</html>
