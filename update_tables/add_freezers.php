<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Freezers</title>
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
<h3>Add Freezers</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_freezer = htmlspecialchars($_GET['freezer']);
			$p_freezer = ucfirst($p_freezer);
			
			if($p_freezer == ''){
				echo '<p>You Must Enter A Freezer Name!<p>';
				$error = 'true';
			}
			
			if($_GET['submit'] == 'update'){
				$p_oldFreezer = htmlspecialchars($_GET['oldFreezer']);
				if($p_oldFreezer == ''){
					echo '<p>You Must Enter An Old Freezer Name!<p>';
					$error = 'true';
				}
			}
			
			//check if name exisset
			$stmt1 = $dbc->prepare("SELECT freezer_id FROM freezer WHERE freezer_id = ?");
			$stmt1 -> bind_param('s', $p_freezer);

  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			if($name == $p_freezer){
        				echo $p_freezer." Exists. Please Check Name.";
						$error = 'true';
					}
				}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			$stmt1 -> close();
	
			//insert info into db
		    if($error != 'true'){
		    	
				if($_GET['submit'] == 'add'){
					//insert data into db. Use prepared statement 
					$stmt2 = $dbc -> prepare("INSERT INTO freezer (freezer_id) VALUES (?)");
					$stmt2 -> bind_param('s',$p_freezer);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You Added A New Freezer: '.$p_freezer.'<br>';
						$submitted = 'true';
					}else{
						echo 'An Error Has Occured';
						mysqli_error($dbc);
					}
				}
				
				
				if($_GET['submit'] == 'update'){
					//update name into db
					$set_query = 'UPDATE freezer SET freezer_id = ? WHERE freezer_id = ?';
					if($set_stmt = $dbc ->prepare($set_query)) {                 
	                	$set_stmt->bind_param('ss',$p_freezer,$p_oldFreezer);
				
	                    if($set_stmt -> execute()){
							$set_rows_affected = $set_stmt ->affected_rows;
						
							$set_stmt -> close();
							if($set_rows_affected >= 0){
								echo "You Updated Freezer Name To: ".$p_freezer.'<br>';
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

<form class="registration" action="add_freezers.php" method="GET">
	<p><i>* = required field   + = required only for update</i></p>
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>Freezer Info:</b></LEGEND>
	
  	<div class="col-xs-6">
  	
	<!--Freezer Name-->
	<p>
	<label class="textbox-label">New Freezer Name:*</label>
	<input type="text" name="freezer" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_freezer;} ?>">
	</p>
	
	<!--Old Freezer Name-->
	<p>
	<label class="textbox-label">Old Freezer Name:+</label>
	<input type="text" name="oldFreezer" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_oldFreezer;} ?>">
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
