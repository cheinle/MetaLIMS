<?php include('../index.php'); ?>
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Update Relative Location</title>
</head>
<body>
<div class="page-header">	
<h3>Update Sample Relative Location Dropdown</h3>
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_loc_name = htmlspecialchars($_GET['loc_name']);
		
			
			if($p_loc_name == ''){
					echo '<p>You must enter a Relative Location Name!<p>';
					$error = 'true';
			}
		
			//check location name exists
			$stmt1 = $dbc->prepare("SELECT loc_name FROM relt_location WHERE loc_name = ?");
			$stmt1 -> bind_param('s', $p_loc_name);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_loc_name){
        				echo $p_loc_name." Exists. Please Check Name.";
						$error = 'true';
					}
				}

			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			#echo 'done';
			$stmt1 -> close();

			//insert info into db
		    if($error != 'true'){
					
				//insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO relt_location (loc_name) VALUES (?)");
				$stmt2 -> bind_param('s', $p_loc_name);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added a new Sample Location Info: '.$p_loc_name.'<br>';
					$submitted = 'true';
				}else{
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
			}
			echo '</div>';
		}
	?>
	<form class="registration" action="update_samp_rel_loc.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		<fieldset>
		<LEGEND><b>Sampling Relative Location Info:</b></LEGEND>
		<div class="col-xs-6">
		<!--Location Name-->
		<p>
		<label class="textbox-label">Location Name:*</label>
		<input type="text" name="loc_name" placeholder="Enter A Location Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_loc_name;} ?>"<br>
		</p>
		</div>
		
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1"> Add </button>
	    <input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
