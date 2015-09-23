<html>
<head>
<meta charset="utf-8">
<title>Forgot Password</title>
    <style type="text/css">
    
      img {border-width: 0}
      * {font-family:'Lucida Grande', sans-serif;}
    </style>
  </head>
  <?php
	if(isset($_SESSION['userid'])){
		echo " You have login as $_SESSION[username], you can <a href=logout.php>logout</a> here";
	}else {
    	echo "<body>
		<form action='forgot_passwordck.php' method=post>
			<table border='0' cellspacing='0' cellpadding='0' align=center>
 			<tr bgcolor='#f1f1f1' > <td colspan='2' align='center'><font face='verdana, arial, helvetica' size='2' align='center'>&nbsp;Forgot Password ?<BR>Enter your email address</font></td> </tr>
 			<tr id='cat'>
  			<tr bgcolor='#ffffff'> <td><font face='verdana, arial, helvetica' size='2' align='center'>  &nbsp;Email  &nbsp; &nbsp;
			</font></td> <td  align='center'><font face='verdana, arial, helvetica' size='2' >
			<input type ='text' class='bginput' name='email' ></font></td></tr>


			<tr bgcolor='#f1f1f1'> <td  colspan='2' align='center'><font face='verdana, arial, helvetica' size='2' align='center'>  
			<input type='submit' value='Submit'> <input type='reset' value='Reset'></font></td> </tr>

			<tr> <td bgcolor='#ffffff' ><font face='verdana, arial, helvetica' size='2' align='center'> &nbsp;<a href=/series/dynamic/airmicrobiomes/login.php>Login</a></font></td> <td bgcolor='#ffffff' align='center'><font face='verdana, arial, helvetica' size='2' ></font></td></tr>



</table></center></form>";
}
?>

</body>

</html>
