<?php include('../index.php'); ?>
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Particle Sensor Update</title>
</head>

<body>
<div class="page-header">
<h3>Update Sensor Dropdown</h3>	
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_partSens = htmlspecialchars($_GET['partSens']);
			$p_sensType = htmlspecialchars($_GET['sensType']);
			$p_serNum = htmlspecialchars($_GET['serNum']);
			
			if($p_partSens == ''){
					echo '<p>You Must Enter A Sensor Name!<p>';
					$error = 'true';
			}
			if($p_sensType == ''){
				echo '<p>You Must Enter A Sensor Type!<p>';
				$error = 'true';
			}
			if($p_serNum == ''){
				echo '<p>You Must Enter Serial Number!<p>';
				$error = 'true';
			}
			
			//check if particle sensor name exists
			$stmt1 = $dbc->prepare("SELECT part_sens_name FROM particle_counter WHERE part_sens_name = ?");
			$stmt1 -> bind_param('s', $p_partSens);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_partSens){
        				echo $p_partSens." Exists. Please Check Name.";
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
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
			}
			echo '</div>';
		}
	?>
</pre>
	
	<form class="registration" action="update_part_sens.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		<fieldset>
		<LEGEND><b>Sensor Info:</b></LEGEND>
		<div class="col-xs-6">
		<!--Particle Sensor Name-->
		<p>
		<label class="textbox-label">Sensor Name:*</label>
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
