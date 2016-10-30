<?php include('../index.php'); ?>
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Form Insert</title>
</head>
<body>
<div class="page-header">	
	<h3>Update Sample Location Dropdown </h3>
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
			$p_address = htmlspecialchars($_GET['address']);
			$p_loc_type = htmlspecialchars($_GET['loc_type']);
			$p_env_type = htmlspecialchars($_GET['env_type']);
			$p_long = htmlspecialchars($_GET['long']);
			$p_lat = htmlspecialchars($_GET['lat']);
		
			
			if($p_loc_name == ''){
					echo '<p>You must enter a Location Name!<p>';
					$error = 'true';
			}
			if($p_address == ''){
				echo '<p>You must enter an Address Name!<p>';
				$error = 'true';
			}
			if($p_loc_type == ''){
				echo '<p>You must enter a Location Type!<p>';
				$error = 'true';
			}
			if($p_env_type == ''){
				echo '<p>You must enter an Environmental Type!<p>';
				$error = 'true';
			}
			if($p_long == ''){
				echo '<p>You must enter a Latitude!<p>';
				$error = 'true';
			}
			if($p_lat == ''){
				echo '<p>You must enter a Longitude!<p>';
				$error = 'true';
			}
			
			//check location name exists
			$stmt1 = $dbc->prepare("SELECT loc_name FROM location WHERE loc_name = ?");
			$stmt1 -> bind_param('s', $p_loc_name);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			#echo 'Another way:'.print_r($row, true); //won't work with bind_result
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
		    	
				//note: at this time these cannot be null(required) but format the fields correctly in case we do switch to allow null...note: no longer need additonal fromatting unless setting to NULL
		    	//set to null any non-required fields that are not populated
				if($p_address == ''){$p_address = NULL;}
				if($p_loc_type == ''){$p_loc_type = NULL;}
				if($p_env_type == ''){$p_env_type = NULL;}
				if($p_lat == ''){$p_lat = NULL;}
				if($p_long == ''){$p_long = NULL;}
					
				//insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO location (loc_name, address, loc_type,environmental_type,latitude,longitude) VALUES (?,?,?,?,?,?)");
				$stmt2 -> bind_param('ssssss', $p_loc_name,$p_address,$p_loc_type,$p_env_type,$p_lat,$p_long);
				
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
	<form class="registration" action="update_samp_loc.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
	<p><a id="myLink" href="link">link</a></p>
	<script>
    var link = "query_samples/query_select_mod.php#fragment-3";
    link = root+link;
    document.getElementById('myLink').setAttribute("href",link);
    document.getElementById('myLink').innerHTML = 'Check if Location Exists';
	</script>
		<fieldset>
		<LEGEND><b>Sampling Location Info:</b></LEGEND>
		<div class="col-xs-6">
		<!--Location Name-->
		<p>
		<label class="textbox-label">Location Name:*</label>
		<input type="text" name="loc_name" placeholder="Enter A Location Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_loc_name;} ?>"<br>
		</p>
		
		<!--Location Address-->
		<p>
		<label class="textbox-label">Address:*</label>
		<input type="text" name="address" placeholder="Enter a Full Address" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_address;} ?>">
		</p>
		
		<!--Location Type-->
		<p>
		<label class="textbox-label">Location Type:*</label>
		<input type="text" name="loc_type" placeholder="e.g. residential/industrial" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_loc_type;} ?>">
		</p>
		
		<!--Environmental Type-->
		<p>
		<label class="textbox-label">Environmental Type:*</label>
		<input type="text" name="env_type" placeholder="e.g. open/closed  " value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_env_type;} ?>">
		</p>
		
		<!--Circulation Type-->
		<p>
		<label class="textbox-label">Circulation Type:*</label>
		<input type="text" name="circ_type" placeholder="e.g. central/split/natural" value="<?php if(isset($_GET['submit'])&& $submitted != 'true'){echo $p_circ_type;} ?>">
		</p>
		
		<!--Latitude-->
		<p>
		<label class="textbox-label">Latitude:*</label>
		<input type="text" name="lat" placeholder="e.g. 34.865784" value="<?php if(isset($_GET['submit'])&& $submitted != 'true'){echo $p_lat;} ?>">
		</p>
		
		<!--Longitude-->
		<p>
		<label class="textbox-label">Longitude:*</label>
		<input type="text" name="long" placeholder="e.g. -120.554501"" value="<?php if(isset($_GET['submit'])&& $submitted != 'true'){echo $p_long;} ?>">
		</p>
		</div>
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1"> Add </button>
		<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
