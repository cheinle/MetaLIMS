<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Analysis Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Update Analysis Dropdown</h3>	
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_anaName = htmlspecialchars($_GET['anaName']);
			$p_anaPipe = htmlspecialchars($_GET['anaPipe']);
		
			if($p_anaName == ''){
					echo '<p>You must enter an Analysis Name!<p>';
					$error = 'true';
			}
			if($p_anaPipe == ''){
				echo '<p>You must enter an Analysis Pipeline!<p>';
				$error = 'true';
			}
		
			//check if analysis name exists
			$stmt1 = $dbc->prepare("SELECT analysis_name FROM analysis WHERE analysis_name = ?");
			$stmt1 -> bind_param('s', $p_anaName);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_anaName){
        				echo $p_anaName." exits. Please check name.";
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

			$stmt1 -> close();

			//insert info into db
		    if($error != 'true'){
		    
				//insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO analysis (analysis_name, analysis_pipeline) VALUES (?,?)");
				$stmt2 -> bind_param('ss', $p_anaName,$p_anaPipe);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added a new Analysis'.$p_anaName.'<br>';
					$submitted = 'true';
				}else{
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
		
			}
		}
	?>

<form class="registration" action="update_analysis.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
	<fieldset>
	<LEGEND><b>Analysis Info:</b></LEGEND>
	<div class="col-xs-6">
	
	<!--Analysis Name-->
	<p>
	<label class="textbox-label">Analysis Name:*</label>
	<input type="text" name="anaName" placeholder="Enter Name of Analysis" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_anaName;} ?>"<br>
	</p>
	
	<!--Media Type-->
	<p>
	<label class="textbox-label">Analysis Pipeline:*</label>
	<input type="text" name="anaPipe" placeholder="Enter location of Analysis Pipeline" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_anaPipe;} ?>">
	</p>
	</div>
	<!--submit button-->
	<button class="button" type="submit" name="submit" value="1"> Add </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	</fieldset>
</form>
	
</body>
</html>
