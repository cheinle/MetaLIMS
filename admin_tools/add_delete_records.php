<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Records</title>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Change Record Source For Daily Data</h3>	
</div>
<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input to make safe for browser
			$p_record = htmlspecialchars($_GET['record']);
			$p_old_record = htmlspecialchars($_GET['oldRecord']);
			
			$check_record_exists = $p_record;
			if($p_record == ''){
				echo '<p>You Must Enter A Record Name!<p>';
				$error = 'true';
			}
			if($_GET['submit'] == 'update'){
				if($p_old_record == ''){
					echo '<p>You Must Enter An Old Record Name To Update!<p>';
					$error = 'true';
				}else{
					$check_record_exists = $p_old_record;
				}
				
			}
			
			//check if name exists
			$stmt1 = $dbc->prepare("SELECT records FROM records WHERE records = ?");
			$stmt1 -> bind_param('s', $check_record_exists);

			$seen_check = 'false';
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			while ($stmt1->fetch()){
        			if($name == $p_UserID){
        				$seen_check = 'true';
        				if($_GET['submit'] == 'add'){
        					echo $check_record_exists." Exists. Please Check Name.";
						    $error = 'true';
						}		
					}
				}
				if(($_GET['submit'] == 'delete' || $_GET['submit'] == 'update') && $seen_check == 'false'){
        					echo $check_record_existss." Does Not Exist. Please Check Name.";
						    $error = 'true';
				}	
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			$stmt1 -> close();
	
			//insert info into db
		    if($error != 'true'){
		    	
				if($_GET['submit'] == 'add'){
					//insert data into db. Use prepared statement 
					$stmt2 = $dbc -> prepare("INSERT INTO records (records) VALUES (?)");
					$stmt2 -> bind_param('s',$p_record);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You Added A New Record Source: '.$p_record.'.<br>';
						$submitted = 'true';
					}else{
						echo 'An Error Has Occured';
						mysqli_error($dbc);
					}
				}
				
				if($_GET['submit'] == 'update'){
					//update name into db
					$set_query = 'UPDATE Records SET records = ? WHERE records = ?';
					if($set_stmt = $dbc ->prepare($set_query)) {                 
	                	$set_stmt->bind_param('ss',$p_record,$p_old_record);
				
	                    if($set_stmt -> execute()){
							$set_rows_affected = $set_stmt ->affected_rows;
						
							$set_stmt -> close();
							if($set_rows_affected >= 0){
								echo "You Updated Record Source: ".$p_old_record.' TO '.$p_old_record.'<br>';
								$submitted = 'true';
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

				if($_GET['submit'] == 'delete'){
					$not_visible = 0; //not visible
					//update visible into db
					$delete_query = 'UPDATE Records SET visible = ? WHERE records = ?';
					if($delete_stmt = $dbc ->prepare($delete_query)) {                 
	                	$delete_stmt->bind_param('is',$not_visible,$p_record);
				
	                    if($delete_stmt -> execute()){
							$delete_rows_affected = $delete_stmt ->affected_rows;
						
							$delete_stmt -> close();
							if($delete_rows_affected >= 0){
								echo "You Deleted Record Source: ".$p_record.'<br>';
								$submitted = 'true';
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

<form class="registration" action="add_delete_records.php" method="GET">
	<p><i>* = required field   + = required only for update</i></p>
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>User Info:</b></LEGEND>
	
  	<div class="col-xs-6">
  	
	<!--Record Name-->
	<p>
	<label class="textbox-label">Record Source:*</label>
	<input type="text" name="record" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_record;} ?>">
	</p>
	
	<!--Old Record Name-->
	<p>
	<label class="textbox-label">Old Record Source:</label>
	<input type="text" name="oldRecord" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_old_record;} ?>">
	</p>

	</div><!--end of class = 'col-xs-6'-->

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="add"> Add </button>
	<button class="button" type="submit" name="submit" value="update">Update</button>
	<button class="button" type="submit" name="submit" value="delete">Delete</button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	</div><!--end of class = 'row'-->
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
