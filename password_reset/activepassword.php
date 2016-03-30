<?php
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
//////////////////////////////
$ak=$_GET['ak'];
$userid=$_GET['userid'];

?>
<html>
<head>
<meta charset="utf-8">
<title>Activate Password</title>
</head>

<body >
<?Php
//$userid=mysql_real_escape_string($userid);
//$ak=mysql_real_escape_string($ak);


	$tm=time()-86400; // Durationg within which the key is valid is 86400 sec. 

	$stmt2 = $dbc->prepare("SELECT user_id FROM users WHERE pkey = ? AND user_id = ? AND time > ? AND status='pending'");
		$stmt2 -> bind_param('ssi',$ak,$userid,$tm);
				
  		if ($stmt2->execute()){
	    	$stmt2->bind_result($col1);
			$stmt2->store_result();
			$stmt2->fetch();
			$no = $stmt2->num_rows;

			echo " No of records = ".$no; 

			if($no <>1){
				echo "<center><font face='Verdana' size='2' color=red><b>Wrong activation </b></font> "; 
				exit;
			}

			////////////////////// Show the change password form //////////////////
			
			
			echo "<form action='activepasswordck.php' method=post><input type=hidden name=todo value=new-password>
			<input type=hidden name=ak value=$ak>
			<input type=hidden name=userid value=$userid>
			<table border='0' cellspacing='0' cellpadding='0' align=center>
			 <tr bgcolor='#f1f1f1' > <td colspan='2' align='center'><font face='verdana, arial, helvetica' size='2' align='center'>&nbsp;<b>New  Password</b> </font></td> </tr>
			
			<tr bgcolor='#ffffff' > <td ><font face='verdana, arial, helvetica' size='2' align='center'>  &nbsp;New Password  
			</font></td> <td  align='center'><font face='verdana, arial, helvetica' size='2' >
			<input type ='password' class='bginput' name='password' ></font></td></tr>
			
			<tr bgcolor='#f1f1f1' > <td ><font face='verdana, arial, helvetica' size='2' align='center'>  &nbsp;Re-enter New Password  
			</font></td> <td  align='center'><font face='verdana, arial, helvetica' size='2' >
			<input type ='password' class='bginput' name='password2' ></font></td></tr>
			
			<tr bgcolor='#ffffff' > <td colspan=2 align=center><input type=submit value='Change Password'><input type=reset value=Reset></form></font></td></tr>
			
			";
			
			
			echo "</table>";
			
			
		}
	
		?>


</body>

</html>
