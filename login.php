<?php 
include('database_connection.php');


/* 
 * Set to logout after set time (time set in index.php). Will log user out from Location if same user tries to login at a different locations
 */
	/////////////////////////////////////////////////////////////////////////////////
	//change flag to true if you need to restrict database access (allows admin only)
	$database_down = 'false';
	/////////////////////////////////////////////////////////////////////////////////
	
	
try{
	//start transaction
	mysqli_autocommit($dbc,FALSE);
	if($_POST) {
		include('path.php');
		$stmt1 = $dbc->prepare("SELECT * FROM users WHERE user_id = ? AND password = SHA1(?)");
		$stmt1 -> bind_param('ss', $_POST['email'],$_POST['password']);
				
	 	if ($stmt1->execute()){
	 		
	 		 $count_check = $stmt1->fetch();
             $size =sizeof($count_check);

			 //check that one entry was returned
             if($size == 1) {
              
			  	//go on to grab the old session id stored in the db
				$meta = $stmt1->result_metadata(); 
		   		while ($field = $meta->fetch_field()){ 
		        	$params[] = &$row[$field->name]; 
		    	} 
		
		    	call_user_func_array(array($stmt1, 'bind_result'), $params); 
			
				$old_session_id;
				$first_name;
				$last_name;
				$admin_user;
				$count = 0;
				$stmt1->execute(); //process is foward-curser so need to reset
				while($stmt1->fetch()){
					$count++;
					$old_session_id = $row['session_id'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$admin_user = $row['admin'];
			   	}
	
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
					

					/***************************************************************************************************
					 * Change this to point to where these files are stored in your document root directory. Leave as '/'
					 * if files are in document root
					 * *************************************************************************************************/
					//$logout_path = '/series/dynamic/am_production/'; /*change here*/
					//************CREATE PATH VARIABLES*******//
					//define('INCLUDE_PATH', $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/am_production/');
					//$_SESSION['include_path'] = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/am_production/';
	
					//define('LINK_ROOT', '/series/dynamic/am_production/');
					//$_SESSION['link_root'] = '/series/dynamic/am_production/';
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
							
							session_destroy();
							$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
							header("Location: http://".$url);
							exit();
						}
					}
					else{
						$url = $_SERVER["HTTP_HOST"].$_SESSION['link_root']."home_page.php"; 
						header("Location: http://".$url);
					}
				}
			}
		}
	}
	$dbc->commit();
}
catch (Exception $e) { 
	if (isset ($dbc)){
		$dbc->rollback ();
		echo '<script>Alert.render("ERROR: Unable To Login. Please Notify Admin");</script>';
		echo "Final Error:  " . $e; 
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
			if($database_down == 'true'){
				echo "Database Is Currently Down For Maintenance. Sorry For Any inconvenience Caused. Please See Admin For Details";
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
				<button type="submit" class="btn blue pull-right">
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
		<form class="form-vertical register-form" action="registration.php" method="post">
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
				<button type="submit" id="register-submit-btn" class="btn green pull-right">
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
