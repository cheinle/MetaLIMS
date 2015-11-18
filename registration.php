<?php
include('database_connection.php');
$first_name = htmlspecialchars($_POST['firstname']);
$last_name = htmlspecialchars($_POST['lastname']);
$email=htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if($_POST['admin'] == 'yes'){ //This is what the user requested
	$admin_yn = 'Y';
}
else{
	$admin_yn = 'N';
}
$admin_yn_default = 'N'; //by default, set user as not an admin

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
	<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="../assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="../assets/plugins/select2/select2_metro.css" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="../assets/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<?php
$status = "OK";

if($first_name == '' || $last_name == '' || $email == '' || $password == ''){
	$status = "NOTOK";
	echo " <center><font face='Verdana' size='2' color=red >There Is Some System Problem In Setting Up Login. Please Try Again Or Contact Admin. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";
			
}


echo "<br><br>";
if($status=="OK"){
	//check if you are the first user entered. If you are, then automatically set you as an admin and visible, else email admin and set to invisible
	//do a check when signing in to see if visible
	//once admin has approved and set to visible, let user know...
	$first_user = 'false';
	$admin_email = '';
	$stmt1 = $dbc->prepare("SELECT user_id from users WHERE admin = Y");	
	
  	if ($stmt->execute()){
    	$stmt->bind_result($col);
		$stmt->store_result();
		if ($stmt->fetch()){
			$admin_email = $col;		
		}
		$no = $stmt->num_rows;
		if($no == 0){
				$first_user = 'true';
		}
	}
			
	//check if username exists
	$stmt1 = $dbc->prepare("SELECT user_id FROM users WHERE user_id = ?");
	$stmt1 -> bind_param('s', $email);
				
  	if ($stmt1->execute()){
    	$stmt1->bind_result($col1);
		$stmt1->store_result();
		$stmt1->fetch();
		$no = $stmt1->num_rows;

		
		if ($no > 0) {
			echo "<center><font face='Verdana' size='2' color=red><b>ERROR</b><br> Sorry Your Username (email address) Already Exists In Our Database. Please Check With Admin<BR><BR></center>"; 
			exit;
		}
		$visible = 0; //default not visible
		if($first_user == 'true'){
			$visible = 1; //visible
			$admin_yn_default = 'Y';
		}
		$password = sha1($password);
		$stmt2 = $dbc -> prepare("INSERT INTO users (user_id,first_name,last_name,password,admin,visible) VALUES (?,?,?,?,?,?)");
		$stmt2 -> bind_param('sssssi',$email,$first_name,$last_name,$password,$admin_yn_default,$visible);
		$stmt2 -> execute();
		$rows_affected2 = $stmt2 ->affected_rows;
		$stmt2 -> close();
					
		//check if add was successful or not. Tell the user
   		if($rows_affected2 > 0){
			echo " <center><font face='Verdana' size='2' color=red >Success! New Username Is: ".$email."<br></center></font>";
		
		}else{
			echo " <center><font face='Verdana' size='2' color=red >There Is Some System Problem In Setting Up Login. Please Contact Site-admin Or Retry. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";
		
		}
		
		
		//send email to admin to request approval
		//send email to user to let them know approval is pending
		if($first_user = 'false'){
			//email user
			$mail_user_success = mail($email,"User Registration awaiting approval","You have registered as User ".$email." and are on the waiting list awaiting approval\n User's email: ".$email." and Admin Access Is: ".$admin_yn);
			if(!$mail_user_success) {
				 echo "Warning: User Mail delivery failed";
			}
			
			//email admin
			$mail_admin_success = mail($admin_email,"User Registration awaiting approval","User ".$email." has registered and is on the waiting list. Please approve\n User's email: ".$email." . Admin Access Requested: ".$admin_yn." . Please email user when approved");
			if(!$mail_admin_success) {
				 echo "Warning: Admin Mail delivery failed";
			}
			
		}
	}	
	
	$stmt->close();
	$stmt1->close();
}
?>
		
</body>
</html>
