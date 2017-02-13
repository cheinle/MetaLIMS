<?php
if(!isset($_SESSION)) { session_start(); }
$path = $_SESSION['include_path']; //same as $path
include ($path.'/functions/admin_check.php');
include('../index.php');
include('../database_connection.php'); 
include('../functions/send_email.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Users</title>
</head>
<body>
<div class="page-header">
<h3>Add Users</h3>	
</div>
<?php 	
	
		unset($_SESSION['orig_update_value']); 
		$submitted = 'false';
		//error checking 
		if(isset($_POST['submit'])){
			echo '<div class="border">';
			$error = 'false';

			//sanatize user input to make safe for browser
			$p_UserID = htmlspecialchars($_POST['UserID']);
			$p_firstName = htmlspecialchars($_POST['firstName']);
			$p_lastName = htmlspecialchars($_POST['lastName']);
			$p_password = htmlspecialchars($_POST['password']);
			
			
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
			if($p_password == ''){
				echo '<p>You Must Enter A User Password!<p>';
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
        				if($_POST['submit'] == 'add'){
        					echo "Username ".$p_UserID." already exists. Please check name.";
						    $error = 'true';
						}		
					}
				}
				//if($_POST['submit'] == 'delete' && $seen_check == 'false'){
        		//			echo $p_UserID." Does Not Exist. Please Check Name.";
				//		    $error = 'true';
				//}	
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			$stmt1 -> close();
	
			//insert info into db
		    if($error != 'true'){
		    	
				if($_POST['submit'] == 'add'){
					//insert data into db. Use prepared statement 
					$visible = 1;
					$password_hash = password_hash($p_password, PASSWORD_BCRYPT); //uses bcrypt, a 60 Char encryption
					$stmt2 = $dbc -> prepare("INSERT INTO users (user_id,first_name,last_name,password,visible) VALUES (?,?,?,?,?)");
					$stmt2 -> bind_param('ssssi',$p_UserID,$p_firstName,$p_lastName,$password_hash,$visible);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You added a new user: '.$p_UserID.'. Please have user reset password using \'Forgot your password?\' on login page<br>';
						$submitted = 'true';
						
						$role = 'user';
						$login_allow = $visible;
						//send email to user that you are updating their info
						$email_user_confirm = send_user_registration_email($p_UserID,$role,$login_allow);
						if($email_user_confirm == 'false'){
							throw new Exception("ERROR: Email to user(s) was not sent<br>");
						}
						
					}else{
						echo 'An Error Has Occurred';
						mysqli_error($dbc);
					}
				}
			}
		}
		echo '</div>';
	?>

<form class="registration" action="add_users.php" method="POST">
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>User Info:</b></LEGEND>
	<p><i>&nbsp* = required field</i></p>
	
  	<div class="col-xs-6">
  	<pre>Note: User can reset their password using the 'Forgot your password?' feature on login page. 
To update user to admin or change login capability please use <a href="update_user_login_info.php">Update User Login Info</a> 
Users are automatically set to 'user' and are able to login</pre>
	<!--User Name-->
	<p>
	<label class="textbox-label">Email Address (Username):*</label>
	<input type="text" name="UserID" placeholder="Name" value="<?php if(isset($_POST['submit']) && $submitted != 'true'){echo $p_UserID;} ?>">
	</p>
	
	<p>
	<label class="textbox-label">First Name:*</label>
	<input type="text" name="firstName" placeholder="Name" value="<?php if(isset($_POST['submit']) && $submitted != 'true'){echo $p_firstName;} ?>">
	</p>
	
	
	<p>
	<label class="textbox-label">Last Name:*</label>
	<input type="text" name="lastName" placeholder="Name" value="<?php if(isset($_POST['submit']) && $submitted != 'true'){echo $p_lastName;} ?>">
	</p>
	
	
	<p>
	<label class="textbox-label">User Password:*</label>
	<input type="password" name="password" placeholder="Password" value="">
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
