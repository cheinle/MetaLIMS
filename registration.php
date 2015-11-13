<?php
include('database_connection.php');
$first_name = htmlspecialchars($_POST['firstname']);
$last_name = htmlspecialchars($_POST['lastname']);
$email=htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if($_POST['admin'] == 'yes'){
	$admin_yn = 'Y';
}
else{
	$admin_yn = 'N';
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
		$password = sha1($password);
		$stmt2 = $dbc -> prepare("INSERT INTO users (user_id,first_name,last_name,password,admin) VALUES (?,?,?,?,?)");
		$stmt2 -> bind_param('sssss',$email,$first_name,$last_name,$password,$admin_yn);
		$stmt2 -> execute();
		$rows_affected2 = $stmt2 ->affected_rows;
		$stmt2 -> close();
					
		//check if add was successful or not. Tell the user
   		if($rows_affected2 > 0){
			echo " <center><font face='Verdana' size='2' color=red >Success! New Username Is: ".$email."<br></center></font>";
		
		}else{
			echo " <center><font face='Verdana' size='2' color=red >There Is Some System Problem In Setting Up Login. Please Contact Site-admin Or Retry. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";
		
		}
	}
}
?>
		
</body>
</html>
