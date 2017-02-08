<?php include('../index.php'); ?>
<?php include('../database_connection.php');
		//turn on error reporting
		error_reporting(E_ALL);
		ini_set('display_errors', '1'); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Project Name Update</title>
</head>
<body>
<div class="page-header">
<h3>Add New Project Name - Approval Step</h3>	
</div>
	<?php 
		
		
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//get user email address
			$email_address = $_SESSION['username'];
		
			//sanatize user input to make safe for browser
			$p_projName = htmlspecialchars($_GET['projName']);
			$p_abName = htmlspecialchars($_GET['abName']);
			$p_description = htmlspecialchars($_GET['description']);
		
			if($p_projName == ''){
					echo '<p>You Must Enter A Project Name!<p>';
					$error = 'true';
			}else{
				//check that project name is 19chars or less
				$regrex_check = '/^[A-Za-z0-9-]{3,19}$/'; 
				if(preg_match($regrex_check,$p_projName) == false){
					echo '<p>Project Name Must Be 3-19 Chars Or Less And Contain No Illegal Characters!<p>';
					$error = 'true';
				}
				
			}
			if($p_abName == ''){
					echo '<p>You Must Enter A Project Abbreviation!<p>';
					$error = 'true';
			}else{
				//check that abbrev is 3-5 characters
				$regrex_check2 = '/^[A-Za-z0-9]{3,5}$/'; 
				if(preg_match($regrex_check2,$p_abName) == false){
					echo '<p>Project Name Must Be 3-5 Characters!<p>';
					$error = 'true';
				}
			}
			if($p_description == ''){
					echo '<p>You Must Enter A Description Of The Project!<p>';
					$error = 'true';
			}
				
			//check if project name exists
			$stmt1 = $dbc->prepare("SELECT project_name FROM project_name WHERE project_name = ?");
			$stmt1 -> bind_param('s', $p_projName);

  			if ($stmt1->execute()){
  			
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			if($name == $p_projName){
        				echo "Project Name '".$p_projName."' Exists. Please Check Name.";
						$error = 'true';
					}
				}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			#echo 'done';
			$stmt1 -> close();

			//if name does not exist, send email for approval
		    if($error != 'true'){
	
					//get all admin's email
					$email_address_list = '';
					$admin = 'Y';
					$admin_visible = 1;
					$counter = 0;
					$query = "SELECT user_id FROM users WHERE admin = ? and visible = ?";
					$stmt = $dbc -> prepare($query);
					if(!$stmt){;
						die('prepare() failed: ' . htmlspecialchars($stmt->error));
						//throw new Exception("ERROR: Email to admins(s) was not sent<br>");
					}
										
					$stmt -> bind_param('si',$admin,$admin_visible);
					if(!$stmt -> execute()){
						die('execute() failed: ' . htmlspecialchars($stmt->error));
						//return $sent_success;
					}else{
						$stmt->bind_result($admin_email);
						while($stmt->fetch()) {
							if($counter == 0){
								$email_address_list = $admin_email;
							}else{
								$email_address_list = $email_address_list.', '.$admin_email;
							}
							 $counter++;
						}		
					}
					$stmt->close();
					
					$subject = "Admin Notice: MetaLIMS Project Approval Request";
			       	$message = "User <b>{$email_address}</b> has requested approval for the following project:<br>Project Name:".$p_projName."<br>Description:".$p_description."<br>Project Abbrev:".$p_abName."<br>Reply-to:".$email_address."<br/><br/><br/>";
			        $message = wordwrap($message, 70, "\r\n");

						
			        $headers = 'From: no-reply@metalims' . "\r\n" .
			            	   'MIME-Version: 1.0'."\r\n".
			                   'Content-Type: text/html; charset=UTF-8'."\r\n";
			
					
			    	if(mail($email_address_list, $subject, $message, $headers)){
			    		echo '<script>
		    			alert("Email sent for Project Name Approval. Information regarding your project name approval will be sent shortly. Thank You!");
						</script>';
					}
					else{
						echo '<script>
	    				alert("Email not sent :( Please contact database admin. Thank you!");
						</script>';
				}		
			}
			echo '</div>';
		}
	?>
	<form class="registration" action="update_proj_name_for_approval.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
	
		<fieldset>
		<LEGEND><b>Project Name Info:</b></LEGEND>
		<p>Note: Project Name Must Be Between 3-19 Characters And Contain No Spaces Or Special Characters Other Than Hyphens</p>
		<p><a id="myLink" href="link">link</a></p>
		<script>
	    	var link = "query_samples/query_select_mod.php#fragment-3";
	    	link = root+link;
	   	 	document.getElementById('myLink').setAttribute("href",link);
	    	document.getElementById('myLink').innerHTML = 'Check Existing Project Names';
		</script>
		
		
		<div class="col-xs-6">
		<!--Project Name-->
		<p>
		<label class="textbox-label">Project Name:*</label>
		<input type="text" name="projName" placeholder="Enter Max 19 Characters" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_projName;} ?>"<br>
		</p>
		
		<p>
		<label class="textbox-label">Project Abbreviated Name:*</label>
		<input type="text" name="abName" placeholder="Enter 3-5 Character" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_abName;} ?>"<br>
		</p>
		
		</div>
		
		<div class="col-xs-12">
		<!--Description-->
		<p>
		<label class="textbox-label">Description:*</label>
		<textarea class="form-control" from="sample_form" rows="3" name="description" placeholder = "Enter A Brief Description of Your Project"><?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {echo $p_description;} ?></textarea>
		</p>
		</div>
		
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1">Submit for Approval </button>
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
