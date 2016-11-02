<?php 
include('database_connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/////////////////////////////////////////////////////////////////////////////////
/********************Allow Access For Admin Only *******************************/
//Change flag to true if you need to restrict database access (allows admin only)
$database_down = 'false';
/////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////
/********************Set Variable For Document Root Path************************/
//if you did not change the git zip file name and placed folder in webroot, 
//this will be your path
$path_in_webroot = '/NanoLIMS/'; 
/////////////////////////////////////////////////////////////////////////////////


//Process login
if (isset($_POST['login_button'])){	
	try{
		//start transaction
		mysqli_autocommit($dbc,FALSE);
		$password_validated = false;
		$password = $_POST['password'];
		
		//to prevent brute force attack
		$bad_login_limit = 3;
		$lockout_time = 600;
		
		
		//check password and admin status
		$stmt = $dbc->prepare("SELECT admin,password,first_name,last_name,session_id,first_failed_login,failed_login_count FROM users WHERE user_id = ? AND visible = '1'");
		if(!$stmt){;
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		$stmt->bind_param("s",$_POST['email']);
		$result = 0;
		
		$password_hash;
		$old_session_id;
		$first_name;
		$last_name;
		$admin_user;
		$first_failed_login;
		$failed_login_count;
		if ($stmt->execute()){
			$stmt->bind_result($admin,$user_password,$fname,$lname,$session_id,$failed_login_time,$login_count);
			while ($stmt->fetch()) {
				$result++;
				$old_session_id = $session_id;
				$first_name = $fname;
				$last_name = $lname;
				$admin_user = $admin;
				$password_hash = $user_password;
				$first_failed_login = $failed_login_time;
				$failed_login_count = $login_count;
			}
		}
		$stmt->close();

	    if ($result > 0){
			$time_difference = time()-$first_failed_login;
		    if (password_verify($password, $password_hash)) {
		    	//if failed attempt is greater than login limit and you are still within lockout time then don't allow login
				if(($failed_login_count >= $bad_login_limit) && ($time_difference < $lockout_time)) {
				  $_SESSION['message'] = 'You are currently locked out. Please wait 10min and try again';
				} else{
					$password_validated = true;
				}
				
			}else{
					if ($time_difference > $lockout_time) {
					   // first unsuccessful login since $lockout_time on the last one expired
					    $first_failed_login = time(); // commit to DB
					    $failed_login_count = 1; // commit to db
					    
					    $stmt = $dbc -> prepare("UPDATE users SET first_failed_login = ?, failed_login_count = ? WHERE user_id = ?");
						$stmt -> bind_param('iis', $first_failed_login,$failed_login_count,$_POST['email']);
						$stmt -> execute();
						$stmt -> close();
						//$_SESSION['message'] = 'You are currently locked out. Please wait 10min and try again';
						$_SESSION['message'] = 'Login invalid. Please try again';
				  } 
				  else {
				  		//initiate brute force login prevention
						if(($failed_login_count >= $bad_login_limit) && ($time_difference < $lockout_time)) {
						  $_SESSION['message'] = 'You are currently locked out. Please wait 10min and try again';
						}
						else{
						    $failed_login_count++; 
							$stmt = $dbc -> prepare("UPDATE users SET failed_login_count = ? WHERE user_id = ?");
							$stmt -> bind_param('is', $failed_login_count,$_POST['email']);
							$stmt -> execute();
							$stmt -> close();
							
							$_SESSION['message'] = 'Login invalid. Please try again';
				  		}
				  	}
				  	
			}	
			
	    }else{
	    	$_SESSION['message'] = 'Login invalid. Please try again';
	    }
	
		
		if($password_validated == true){
				//store current session id
				if(session_id()){
					session_commit();
				}
				session_start();
				session_regenerate_id(true); 
				$new_session_id = session_id();
				session_commit();

				
				//check if old session id is the same as the new session id
				if($new_session_id != $old_session_id){
					
					//set new session id into the db and and destroy old session (so logs the other person out)
			
					//start old session and destroy it
					session_id($old_session_id);
					session_start();
					session_destroy();
					session_commit();
	
					session_id($new_session_id);
					session_start();
					
					$stmt2 = $dbc -> prepare("UPDATE users SET session_id = ? WHERE user_id = ?");
					$stmt2 -> bind_param('ss', $new_session_id,$_POST['email']);
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
						
					//check if add was successful or not. Tell the user
				    if($rows_affected2 < 0){
				    	throw new Exception("An Error Has Occurred. Please Notify Admin");		
					}
					$_SESSION['username'] = $_POST['email'];
					$_SESSION['session_id'] = $new_session_id;
					$_SESSION['first_name'] = $first_name;
					$_SESSION['last_name'] = $last_name;
					

					//************ PATH VARIABLES Set Here Using Above $path_in_webroot*******//
					$logout_path = $path_in_webroot; 
					$_SESSION['include_path'] = $_SERVER['DOCUMENT_ROOT'].$path_in_webroot;
					
					//$_SESSION['include_path'] = dirname(dirname(__FILE__)).'\';
					$_SESSION['link_root'] = $path_in_webroot;
					/////////////////////////////////////////////

						
					//if you need restrict access to database for any reason
					//destroy session for anyone who is not the developer
					if($database_down == 'true'){
						//if($_SESSION['username'] == $admin_user){
						if($admin_user == 'Y'){
							$url = $_SERVER["HTTP_HOST"].$_SESSION['link_root']."home_page.php"; 
							header("Location:http://".$url);
						}
						else{
							$_SESSION['message'] = 'Database Is Currently Down For Maintenance. Sorry For Any inconvenience Caused. Please See Admin For Details';
							//session_destroy();
							//$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
							//header("Location: http://".$url);
							//exit();
						}
					}
					else{
						$url = $_SERVER["HTTP_HOST"].$_SESSION['link_root']."home_page.php"; 
						header("Location: http://".$url);
					}
				}//end if new session id != old session id
			}
			$dbc->commit();
	}//end try
	catch (Exception $e) { 
		if (isset ($dbc)){
			$dbc->rollback ();
			echo '<script>Alert.render("ERROR: Unable to login. Please check username and password");</script>';
		}
	}
}//end login

//Process registration
if (isset($_POST['registration_button'])){	
	  session_start();
	  include('functions/send_email.php');

	  include('database_connection.php');
	  $password = $_POST['password'];
	  $email = $_POST['email'];
	  $visible = '0'; //default not visible
	  $validated = false;
	  $first_name = $_POST['firstname'];
	  $last_name = $_POST['lastname'];

	  $admin_yn = 'N'; //default
	  if(isset($_POST['admin']) && $_POST['admin'] == 'yes'){ //This is what the user requested
		$admin_yn = 'Y';
	  }
	 
	 try{
	 	
		//start transaction
		$dbc->autocommit(FALSE);
		
	 
		  $user_exists = 'FALSE';
		  if($email!="" && $password!=""){
			  	$stmt = $dbc -> prepare("SELECT user_id FROM users WHERE user_id = ?");
				if(!$stmt){;
					throw new Exception("ERROR: Prepare failure<br>");
				}
									
				$stmt -> bind_param('s',$email);
				if(!$stmt -> execute()){
					throw new Exception("ERROR: Execute failure<br>");
				}else{
					$stmt->bind_result($existing_username);
					if($stmt->fetch()) {
						$user_exists = 'TRUE';
					}		
				}
				$stmt->close();
			
			}
			if($user_exists == 'FALSE'){
			
			  	$password_hash = password_hash($password, PASSWORD_BCRYPT); //uses bcrypt, a 60 Char encryption
			  	$stmt = $dbc -> prepare("INSERT INTO users (user_id,first_name,last_name,password,admin,visible) VALUES (?,?,?,?,?,?)");
				if(!$stmt){;
					throw new Exception("ERROR: Prepare failure<br>");
				}
									
				$stmt -> bind_param('sssssi',$email,$first_name,$last_name,$password_hash,$admin_yn,$visible);
				
				if(!$stmt -> execute()){
					throw new Exception("ERROR: Execute failure<br>");
				}else{
					$rows_affected = $stmt ->affected_rows;
					$stmt -> close();
					if($rows_affected > 0){
						$validated = true;
						
					}
	
					//alert all admins through email
					$email_admin_confirm = send_admin_email($email,'user',0,$dbc,'new_user');
					if(!$email_admin_confirm){
						throw new Exception("ERROR: Email to admin(s) was not sent<br>");
					}
				}
		
			    if($validated){
			    		
						$_SESSION['message'] = 'Registration submitted. Admin will email you when registration is complete';
						//header('location: welcome_new_user.php');
				}
			    else{
			      		$_SESSION['message'] = 'Invalid registration. Please see admin';
						//header('location: registration.php');
			    }
			 }else{
			 	$message = 'Invalid username. Username '.$email.' exists. Please try another name.';
			 	$_SESSION['message'] = $message;
				//header('location: registration.php');
			 }
			$dbc->commit();
		}

		catch (Exception $e) { 
			if (isset ($dbc)){
		   	 	$dbc->rollback ();
		   		echo "Error:  " . $e; 
			}
		}
}


?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Login Form</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="aquired/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="aquired/assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<!--<link href="aquired/assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>-->
	<!--<link rel="stylesheet" type="text/css" href="aquired/assets/plugins/select2/select2_metro.css" />-->
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="aquired/assets/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="images/favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<!-- PUT YOUR LOGO HERE -->
		<?php 
			if(isset($_SESSION['message'])){
			    echo $_SESSION['message'];
			    unset($_SESSION['message']);
			}
		?>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<form class="form-vertical login-form" action="login.php" method="POST">
			<h3 class="form-title">Login to your account</h3>
			<div class="alert alert-error hide">
				<button class="close" data-dismiss="alert"></button>
				<span>Enter any username and password.</span>
			</div>
			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Username</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-user"></i>
						<input class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="email"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<!--<label class="checkbox">
				<input type="checkbox" name="remember" value="1"/> Remember me
				</label>-->
				<button type="submit" class="btn blue pull-right" name="login_button" value="login">
				Login <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
			<div class="forget-password">
				<h4>Forgot your password ?</h4>
				<p>
					no worries, click <!--<a href="/series/dynamic/airmicrobiomes/password_reset/reset_password.php">--><a href="javascript:;"  id="forget-password">here</a>
					to reset your password.
				</p>
			</div>
			<div class="create-account">
				<p>
					Don't have an account yet ?&nbsp; 
					<a href="javascript:;" id="register-btn" >Create an account</a>
				</p>
			</div>
		</form>
		<!-- END LOGIN FORM -->        
		<!-- BEGIN FORGOT PASSWORD FORM -->
		<form class="form-vertical forget-form" action="password_reset/forgot_passwordck.php" method="post">
			<h3 >Forget Password ?</h3>
			<p>Enter your e-mail address below to reset your password.</p>
			<div class="control-group">
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-envelope"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" autocomplete="off" name="email" />
					</div>
				</div>
			</div>
			<div class="form-actions">
				<button type="button" id="back-btn" class="btn">
				<i class="m-icon-swapleft"></i> Back
				</button>
				<button type="submit" class="btn blue pull-right">
				Submit <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
		</form>
		<!-- END FORGOT PASSWORD FORM -->
		<!-- BEGIN REGISTRATION FORM -->
		<form class="form-vertical register-form" action="login.php" method="post">
			<h3 >Sign Up</h3>
			<p>Enter your personal details below:</p>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">First Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-font"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="First Name" name="firstname"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Last Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-font"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Last Name" name="lastname"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">Email</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-envelope"></i>
						<input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-lock"></i>
						<input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-ok"></i>
						<input class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword"/>
					</div>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<label class="checkbox">
					<input type="checkbox" name="admin" value="yes"/> Sign-Up As System Admin</a>
					</label>  
					<div id="register_tnc_error"></div>
				</div>
			</div>
			<div class="form-actions">
				<button id="register-back-btn" type="button" class="btn">
				<i class="m-icon-swapleft"></i>  Back
				</button>
				<button type="submit" id="register-submit-btn" class="btn green pull-right" value="registration" name="registration_button">
				Sign Up <i class="m-icon-swapright m-icon-white"></i>
				</button>            
			</div>
		</form>
		<!-- END REGISTRATION FORM -->
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		2014 &copy; <a href="http://www.justukfreebies.co.uk/">Just UK Freebies</a> Login Form
	</div>
	<!-- END COPYRIGHT -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   <script src="aquired/assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="aquired/assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="aquired/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      
	<script src="aquired/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!--<script src="aquired/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>-->
	<!--[if lt IE 9]>
	<script src="aquired/assets/plugins/excanvas.min.js"></script>
	<script src="aquired/assets/plugins/respond.min.js"></script>  
	<![endif]-->   
	<script src="aquired/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="aquired/assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="aquired/assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<!--<script src="aquired/assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>-->
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="aquired/assets/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<script src="aquired/assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="aquired/assets/plugins/select2/select2.min.js"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="aquired/assets/scripts/app.js" type="text/javascript"></script>
	<script src="aquired/assets/scripts/login-soft.js" type="text/javascript"></script>      
	<!-- END PAGE LEVEL SCRIPTS --> 
	<script>
		jQuery(document).ready(function() {     
		  App.init();
		  Login.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->
	<div style="position:absolute; bottom:0px; left:0px; "><a href="http://www.justukfreebies.co.uk/website-templates/free-responsive-login-form-template/">Free Website Templates</a></div>
</body>
<!-- END BODY -->
</html>
