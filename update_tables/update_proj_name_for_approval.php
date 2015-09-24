<?php include('../database_connection.php');
		//turn on error reporting
		error_reporting(E_ALL);
		ini_set('display_errors', '1'); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>project name update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Add New Project Name - Approval Step</h3>	
</div>
	<?php 
		
		
		//error && type checking 
		if(isset($_GET['submit'])){
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
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
  			
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			if($name == $p_projName){
        				echo "Project Name '".$p_projName."' exits. Please check name.";
						$error = 'true';
					}
				}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();

			//if name does not exist, send email for approval
		    if($error != 'true'){
				$email_to=$admin_user;
				$email_subject="Project Name Approval";
				$email_message="Please Approve Project: '".$p_projName."' with the following description:\n '".$p_description."' \n Project Abbrev:'".$p_abName."' Reply-to:'".$email_address;
				$headers = "From: admin\r\n".
				"Reply-To:".$email_address."\r\n" .
				"X-Mailer: PHP/" . phpversion();
				if(mail($email_to, $email_subject, $email_message, $headers)){
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
		}
	?>
	<form class="registration" action="update_proj_name_for_approval.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
	
		<fieldset>
		<LEGEND><b>Project Name Info:</b></LEGEND>
		<p>Note: Project Name Must Be Between 3-19 Characters And Contain No Spaces Or Special Characters Other Than Hypehns</p>
		<p><a id="myLink" href="link">link</a></p>
		<script>
	    	var link = "query_select_mod.php#projects";
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
