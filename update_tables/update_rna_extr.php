<?php include('../index.php'); ?>
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>RNA Extraction Kit Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>	
</head>

<body>
<div class="page-header">
<h3>Update RNA Extraction Kit Dropdown</h3>	
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_rExtr = htmlspecialchars($_GET['rExtr']);
			
			if($p_rExtr == ''){
					echo '<p>You must enter an RNA Extraction Kit Name!<p>';
					$error = 'true';
			}
		
			//check if rna extraction kit name exists
			$stmt1 = $dbc->prepare("SELECT r_kit_name FROM rna_extraction WHERE r_kit_name = ?");
			$stmt1 -> bind_param('s', $p_rExtr);

  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_rExtr){
        				echo $p_rExtr." Exists. Please Check Name.";
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
	
				$stmt2 = $dbc -> prepare("INSERT INTO rna_extraction (r_kit_name) VALUES (?)");
				$stmt2 -> bind_param('s', $p_rExtr);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added new RNA Extraction Kit: '.$p_rExtr.'<br>';
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
	
	<form class="registration" action="update_rna_extr.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		
		<fieldset>
		<LEGEND><b>RNA Extraction Info:</b></LEGEND>
		<div class="col-xs-6">
		<!--RNA Extraction Kit Name-->
		<p>
		<label class="textbox-label">RNA Extraction Kit Name:*</label>
		<input type="text" name="rExtr" placeholder="Enter an RNA Extraction Kit Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_rExtr;} ?>"<br>
		</p>
		</div>
			
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1"> Add </button>
		<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
