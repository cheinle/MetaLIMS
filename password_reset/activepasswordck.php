<?Php
//***************************************
// This is downloaded from www.plus2net.com //
/// You can distribute this code with the link to www.plus2net.com ///
//  Please don't  remove the link to www.plus2net.com ///
// This is for your learning only not for commercial use. ///////
//The author is not responsible for any type of loss or problem or damage on using this script.//
/// You can use it at your own risk. /////
//*****************************************
//include "include/session.php";
include('../database_connection.php'); // database connection details stored here
include('../index.php');
 // database connection details stored here
//////////////////////////////
$ak=$_POST['ak'];
$userid=$_POST['userid'];
$todo=$_POST['todo'];
$password=$_POST['password'];
$password2=$_POST['password2'];

?>
<html>
<head>
<meta charset="utf-8">
<title>Active Password</title>
</head>

<body>
<?php

//$userid=mysql_real_escape_string($userid);
//$ak=mysql_real_escape_string($ak);


$tm=time()-86400;

$stmt2 = $dbc->prepare("SELECT user_id FROM users WHERE pkey = ? AND user_id = ? AND time > ? AND status='pending'");
$stmt2 -> bind_param('ssi', $ak,$userid,$tm);
				
if ($stmt2->execute()){
	$stmt2->bind_result($col1);
	$stmt2->store_result();
	$stmt2->fetch();
	$no = $stmt2->num_rows;
	echo $no;

	if($no <>1){
		echo "<center><font face='Verdana' size='2' color=red><b>Wrong activation </b></font> "; 
		exit;
	}

	////////////////////// Show the change password form //////////////////


	if(isset($todo) and $todo=="new-password"){
		//$password=mysql_real_escape_string($password);

		//Setting flags for checking
		$status = "OK";
		$msg="";

		if ( strlen($password) < 3 or strlen($password) > 12 ){
		$msg=$msg."Password must be more than 3 char legth and maximum 12 char lenght<BR>";
		$status= "NOTOK";}					
		
		if ( $password <> $password2 ){
		$msg=$msg."Both passwords are not matching<BR>";
		$status= "NOTOK";}					

		if($status<>"OK"){ 
			echo "<font face='Verdana' size='2' color=red>$msg</font><br><center><input type='button' value='Retry' onClick='history.go(-1)'></center>";
		}
		else{ // if all validations are passed.
			$password=sha1($password); // Encrypt the password before storing
			
			
			// Update the new password now //
			$set_query = 'UPDATE users SET password = ? WHERE user_id =?';
			if($set_stmt = $dbc ->prepare($set_query)) {                 
		    	$set_stmt->bind_param('ss',$password, $userid);
				$set_stmt -> execute();
				$set_stmt->store_result();
				$no = $stmt2->num_rows;
				
				if($no==1){
				
					$tm=time();
					// Update the key so it can't be used again. 
					$done = 'done';
					$pending= 'pending';
					$set_query2 = 'UPDATE users SET status= ? WHERE pkey = ? AND user_id =? AND status = ?';
					if($set_stmt2 = $dbc ->prepare($set_query2)) {                 
		    			$set_stmt2->bind_param('ssss',$done,$password,$userid,$pending);
						$set_stmt2 -> execute();
						echo "<font face='Verdana' size='2' ><center>Thanks <br> Your new password is stored successfully. </font></center>";
					}
					else{
						echo "<font face='Verdana' size='2' color=red><center>Sorry <br> Failed to store new password Contact Site Admin</font></center>";
					} // end of if plus_signup is updated with new password
				}
			} // end of if status <> 'OK'
		}
	}
}

echo '<center><a href='.$root.'login.php>Login</a>';
?>


</body>

</html>
