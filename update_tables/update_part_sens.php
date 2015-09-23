<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Particle Sensor Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>	
</head>

<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Update Particle Sensor Dropdown</h3>	
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_partSens = htmlspecialchars($_GET['partSens']);
			$p_sensType = htmlspecialchars($_GET['sensType']);
			$p_serNum = htmlspecialchars($_GET['serNum']);
			
			if($p_partSens == ''){
					echo '<p>You must enter a Particle Sensor Name!<p>';
					$error = 'true';
			}
			if($p_sensType == ''){
				echo '<p>You must enter a Sensor Type!<p>';
				$error = 'true';
			}
			if($p_serNum == ''){
				echo '<p>You must enter Serial Number!<p>';
				$error = 'true';
			}
			
			//check if particle sensor name exists
			$stmt1 = $dbc->prepare("SELECT part_sens_name FROM particle_counter WHERE part_sens_name = ?");
			$stmt1 -> bind_param('s', $p_partSens);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			#echo 'Another way:'.print_r($row, true); //won't work with bind_result
        			if($name == $p_partSens){
        				echo $p_partSens." exits. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
	
			//insert info into db
		    if($error != 'true'){
		    	
				$stmt2 = $dbc -> prepare("INSERT INTO particle_counter (part_sens_name,sensor_type,serial_num) VALUES (?,?,?)");
				$stmt2 -> bind_param('sss', $p_partSens,$p_sensType,$p_serNum);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added new Particle Sensor Info: '.$p_partSens.'<br>';
					$submitted = 'true';
				}else{
					
					echo 'An error has occured';
					mysqli_error($dbc);
					
				}
			}
		}
	?>
</pre>
	
	<form class="registration" action="update_part_sens.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		<fieldset>
		<LEGEND><b>Particle Sensor Info:</b></LEGEND>
		<div class="col-xs-6">
		<!--Particle Sensor Name-->
		<p>
		<label class="textbox-label">Particle Sensor Name:*</label>
		<input type="text" name="partSens" placeholder="Enter A Particle Sensor Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_partSens;} ?>"<br>
		</p>
		
		<!--Sensor Type-->
		<p>
		<label class="textbox-label">Sensor Type:*</label>
		<input type="text" name="sensType" placeholder="Enter A Sensor Type" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_sensType;} ?>">
		</p>
		
		<!--Serial Number-->
		<p>
		<label class="textbox-label">Serial Number:*</label>
		<input type="text" name="serNum" placeholder="Enter A Serial Number" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_serNum;} ?>">
		</p>
		</div>
		
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1"> Add </button>
		<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
