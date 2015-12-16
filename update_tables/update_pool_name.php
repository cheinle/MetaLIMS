<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pool Name Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<pre> <!-- commenting this out gets rid of the large bar-->
	
<h3>Update Pool Name Dropdown </h3>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_poolName = htmlspecialchars($_GET['poolName']);
		
			if($p_poolName == ''){
					echo '<p>You must enter a Pool Name!<p>';
					$error = 'true';
			}
				
			//check if pool name exists
			$stmt1 = $dbc->prepare("SELECT pool_extracts_name FROM pool_extractions WHERE pool_extracts_name = ?");
			$stmt1 -> bind_param('s', $p_poolName);
				
  			if ($stmt1->execute()){
  			
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_poolName){
        				echo $p_poolName." exits. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results".'<br>';//no result came back so free to enter into db, no error
					
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
		    			
				$stmt2 = $dbc -> prepare("INSERT INTO pool_extractions (pool_extracts_name) VALUES (?)");
				$stmt2 -> bind_param('s', $p_poolName);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added new Pool Name Info: '.$p_poolName;
					$submitted = 'true';
				}else{
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
			}
		}
	?>
</pre>
	
	<form class="navbar-form" action="update_pool_name.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		
		<fieldset>
		<LEGEND><b>Pooling Sample Info:</b></LEGEND>
		<!--Pool Name-->
		<p>
		<label>Pool Name:*</label><br>
		<input type="text" name="poolName" class="fields" placeholder="Enter A Pool Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_poolName;} ?>"<br>
		</p>
		
		<!--submit button-->
		<p><button class="btn btn-success" type="submit" name="submit" value="1"> Add </button></p>
		<p><input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" /></p>
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
