<?php 
include('../index.php');
include('../database_connection.php');
include('../functions/send_email.php');
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Update User Login</title>	
</head>
<body>
	<div class="page-header">
	<h3>Update User Login Info</h3>	
	</div>
<?php 
if(isset($_POST['submit'])){
	echo '<div class="border">';
	try{
		//start transaction
		$dbc->autocommit(FALSE);
		
		//Grab old user Info
		$old_user_info = array();
		$stmt = $dbc->prepare("SELECT user_id,admin,visible FROM users");
		if(!$stmt){;
			throw new Exception("ERROR: Prepare Failure");
		}
		if ($stmt->execute()){
			$stmt->bind_result($old_email_address,$old_role,$old_login_allow);
			while ($stmt->fetch()) {
				$old_user_info[$old_email_address]['email'] = $old_email_address;
				$old_user_info[$old_email_address]['role'] = $old_role;
				$old_user_info[$old_email_address]['login_allow'] = $old_login_allow;
			}
		}else{
			throw new Exception("ERROR: Execute Failure");
		}
		
		$stmt->close();
		
		
		
		//Update user info
		$user_list = $_POST['user_list'];
		$user_array = explode(',',$user_list);
		if (is_array($user_array) || is_object($user_array)){
		    foreach($user_array as $index => $uname){
		    
				$underscored_username = preg_replace('/[.]/', '_',$uname);
				$login_allow = $_POST[$underscored_username.'_login'];
				$role = $_POST[$underscored_username.'_role'];
				
				
				//if your user is the current user, do not allow to change your own login values? 
				if($uname == $_SESSION['username'] && $uname != 'admin@nanolims.com'){
					if( $role != $old_user_info[$uname]['role'] || $login_allow != $old_user_info[$uname]['login_allow']  ){
						echo '<script>alert("Warning: Admin cannot update his/her own login info");</script>';
						continue;
					}
				}

				$query = 'UPDATE users SET admin =?, visible = ? WHERE user_id = ?';
				if($stmt = $dbc ->prepare($query)) {                 
			    	$stmt->bind_param('sis',$role,$login_allow,$uname);
					if($stmt -> execute()){
						$rows_affected = $stmt ->affected_rows;
						$stmt -> close();
						if($rows_affected < 0){
							throw new Exception("ERROR: User ".$uname." was not updated");
						}else{

							//Send emails if new information is different from old information
							if( $role != $old_user_info[$uname]['role'] || $login_allow != $old_user_info[$uname]['login_allow']  ){
									
								//send email to user that you are updating their info
								$email_user_confirm = send_user_email($uname,$role,$login_allow);
								if($email_user_confirm == 'false'){
									throw new Exception("ERROR: Email to user(s) was not sent<br>");
								}
									
									
								//alert all admins through email that this has changed (to make sure it is ok)
								$email_admin_confirm = send_admin_email($uname,$role,$login_allow,$dbc,'update_user');
								if($email_admin_confirm == 'false'){
									throw new Exception("ERROR: Email to admin(s) was not sent<br>");
								}
								
								
							}
							
						}
					}
					else{
						throw new Exception("ERROR: Execute Failure");
					}
				}
				else{
					throw new Exception("ERROR: Prepare Failure");
				}	
			} 
		}
	
		echo '<script>alert("SUCCESS! User updates made");</script>';
		echo "Email(s) sent to notify user(s) of update made";
		$dbc->commit();
	}
	catch (Exception $e) { 
		if (isset ($dbc)){
	   	 	$dbc->rollback ();
	   		echo "Error:  " . $e; 
		}
	}	
	echo "</div>";
}

?>
<div id="inner_content" class="inner_content_div" align="center">
	<div id="datatable_div">
	<form onsubmit="return confirm(\'Do you want to submit the form?\');" action="update_user_login_info.php" method="POST" class="registration"><!--onsubmit not currently working-->
	<pre>*Note: Admin are not able to update his/her own login info (exception-admin@nanolims.com)</pre>		
	<table id="datatable" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>&nbsp;User</th>
					<th>&nbsp;Allow Login (Y/N)</th>
					<th>&nbsp;User role</th>
				</tr>
			</thead>
			<tbody>
				<?php
					
					$users = '';
					$stmt = $dbc->prepare("SELECT user_id,admin,visible FROM users");
					if(!$stmt){;
						die('prepare() failed: ' . htmlspecialchars($stmt->error));
					}
					if ($stmt->execute()){
						$stmt->bind_result($email_address,$role,$visible);
						$counter = 0;
						
						while ($stmt->fetch()) {
							$email_address = trim($email_address);
							echo "<tr>";
							echo "<td>".$email_address."</td>";
							
							echo "<td>";
							?>
							<input type="radio" name="<?php echo $email_address; ?>_login" value="1" <?php if($visible == 1){echo 'checked';}?>>Yes
							<input type="radio" name="<?php echo $email_address; ?>_login" value="0" <?php if($visible == 0){echo 'checked';}?>>No<br>
							<?php
							echo "</td>";
							
							echo "<td>";
							?>
							<select name="<?php echo $email_address; ?>_role">
							<option value="Y" <?php if($role == 'Y'){echo 'selected';}?>>Admin</option>
							<option value="N" <?php if($role == 'N'){echo 'selected';}?>>User</option>
							<?php
							echo "</select>";
							echo "</td>";
							echo "<tr>";
							
							if($counter == 0){
								$users = $email_address;
							}else{
								$users = $users.','.$email_address;
							}
							$counter++;
						}
					
					}else{
						die('execute() failed: ' . htmlspecialchars($stmt->error));
					}
					
					$stmt->close();
				?>
			</tbody>
		</table>
		<input type="text" name="user_list" style="visibility: hidden;" value="<?php echo $users;?>"\>
		<input type="submit" name = "submit" value="Update Users" class="button">
	</form>
	</div>
<div>&nbsp;</div>
</div> 
</body>
</html>
