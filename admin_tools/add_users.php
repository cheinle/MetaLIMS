<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Users</title>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Add Users</h3>	
</div>
<?php 	

		unset($_SESSION['orig_update_value']); 
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input to make safe for browser
			$p_UserID = htmlspecialchars($_GET['UserID']);
			$p_firstName = htmlspecialchars($_GET['firstName']);
			$p_lastName = htmlspecialchars($_GET['lastName']);
			
			
			if($p_UserID == ''){
				echo '<p>You Must Enter A User Name!<p>';
				$error = 'true';
			}
			if($p_firstName == ''){
				echo '<p>You Must Enter A First Name!<p>';
				$error = 'true';
			}
			if($p_lastName == ''){
				echo '<p>You Must Enter A Last Name!<p>';
				$error = 'true';
			}
			
			//check if name exists
			$stmt1 = $dbc->prepare("SELECT user_id FROM users WHERE user_id = ?");
			$stmt1 -> bind_param('s', $p_UserID);

			$seen_check = 'false';
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			while ($stmt1->fetch()){
        			if($name == $p_UserID){
        				$seen_check = 'true';
        				if($_GET['submit'] == 'add'){
        					echo $p_UserID." Exists. Please Check Name.";
						    $error = 'true';
						}		
					}
				}
				if($_GET['submit'] == 'delete' && $seen_check == 'false'){
        					echo $p_UserID." Does Not Exist. Please Check Name.";
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
					$password = sha1($p_UserID.'!@VE_$eyeNce');
					$stmt2 = $dbc -> prepare("INSERT INTO users (user_id,first_name,last_name,password) VALUES (?,?,?,?)");
					$stmt2 -> bind_param('ssss',$p_UserID,$p_firstName,$p_lastName,$password);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You Added A New User: '.$p_UserID.'. Please Have User Use Reset Password Feature To Set Password<br>';
						$submitted = 'true';
					}else{
						echo 'An Error Has Occurred';
						mysqli_error($dbc);
					}
				}
			}
		}
	?>

<form class="registration" action="add_users.php" method="GET">
	<p><i>* = required field</i></p>
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>User Info:</b></LEGEND>
	<p>Note: Email address will become username</p>
	
  	<div class="col-xs-6">
  	
	<!--User Name-->
	<p>
	<label class="textbox-label">Email Address:*</label>
	<input type="text" name="UserID" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_UserID;} ?>">
	</p>
	
	<p>
	<label class="textbox-label">First Name:*</label>
	<input type="text" name="firstName" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_firstName;} ?>">
	</p>
	
	
	<p>
	<label class="textbox-label">Last Name:*</label>
	<input type="text" name="lastName" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_lastName;} ?>">
	</p>

	</div><!--end of class = 'col-xs-6'-->

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="add"> Add </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	</div><!--end of class = 'row'-->
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
