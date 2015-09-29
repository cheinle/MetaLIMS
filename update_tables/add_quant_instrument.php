<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Quantitation Instrument</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script>
	$(document).ready(function(){
   		$('[data-toggle="popover"]').popover({
        	placement : 'right'
    	});
	});
</script>
				
<style>
	.popover-content {
    	font-style: bold;
    	font-size: 14px;
	}
</style>

</head>
<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Add Quantitation Instrument</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_quantInstrument = htmlspecialchars($_GET['quantInstrument']);
			$p_quantInstrument = ucfirst($p_quantInstrument);
			
			if($p_quantInstrument == ''){
				echo '<p>You Must Enter A Quantitation Instrument!<p>';
				$error = 'true';
			}
			
			if($_GET['submit'] == 'update'){
				$p_oldQuantInstrument = htmlspecialchars($_GET['oldQuantInstrument']);
				if($p_oldQuantInstrument == ''){
					echo '<p>You Must Enter An Old Quantitation Instrument!<p>';
					$error = 'true';
				}
			}
			
			//check if name exisset
			$stmt1 = $dbc->prepare("SELECT kit_name FROM quant_instruments WHERE kit_name = ?");
			$stmt1 -> bind_param('s', $p_quantInstrument);

  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			if($name == $p_quantInstrument){
        				echo $p_quantInstrument." Exists. Please Check Name.";
						$error = 'true';
					}
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
		    	
				if($_GET['submit'] == 'add'){
					//insert data into db. Use prepared statement 
					$stmt2 = $dbc -> prepare("INSERT INTO quant_instrumenset (kit_name) VALUES (?)");
					$stmt2 -> bind_param('s',$p_quantInstrument);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You Added A New Quantitation Instrument: '.$p_quantInstrument.'<br>';
						$submitted = 'true';
					}else{
						echo 'An Error Has Occured';
						mysqli_error($dbc);
					}
				}
				
				
				if($_GET['submit'] == 'update'){
					//update name into db
					$set_query = 'UPDATE quant_instruments SET kit_name = ? WHERE kit_name = ?';
					if($set_stmt = $dbc ->prepare($set_query)) {                 
	                	$set_stmt->bind_param('ss',$p_quantInstrument,$p_oldQuantInstrument);
				
	                    if($set_stmt -> execute()){
							$set_rows_affected = $set_stmt ->affected_rows;
						
							$set_stmt -> close();
							if($set_rows_affected >= 0){
								echo "You Updated Quantitation Instrument Name To: ".$p_quantInstrument.'<br>';
							}
							else{	
								echo 'An Error Has Occured';
								mysqli_error($dbc);
							}
						}
						else{
							echo 'An Error Has Occured';
							mysqli_error($dbc);
						}
					}
					else{
						echo 'An Error Has Occured';
						mysqli_error($dbc);
					}
				}
			}
		}
	?>

<form class="registration" action="add_quant_instrument.php" method="GET">
	<p><i>* = required field   + = required only for update</i></p>
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>Quantitation Instrument Info:</b></LEGEND>
	
  	<div class="col-xs-6">
  	<p class = "adjust"><i>Note: Used To Add Instrument/Kit Used to Measure DNA/RNA Concentration</i></p>
	<br>
	<!--Quantitation Instrument Name-->
	<p>
	<label class="textbox-label">New Quantitation Instrument Name:*</label>
	<input type="text" name="quantInstrument" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_quantInstrument;} ?>">
	</p>
	
	<!--Old Quantitation Instrument Name-->
	<p>
	<label class="textbox-label">Old Quantitation Instrument Name:+</label>
	<input type="text" name="oldQuantInstrument" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_oldQuantInstrument;} ?>">
	</p>
	</div><!--end of class = 'col-xs-6'-->

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="add"> Add </button>
	<button class="button" type="submit" name="submit" value="update">Update</button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	</div><!--end of class = 'row'-->
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
