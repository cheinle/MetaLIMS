<?php 
if(!isset($_SESSION)) { session_start(); }
$path = $_SESSION['include_path']; //same as $path
include ($path.'/functions/admin_check.php');
include('../index.php');
include('../database_connection.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Project Name Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<div class="page-header">
<h3>Add New Project Name - Admin Only</h3>	
</div>
	<?php 
		//error && type checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//get username and update entered by with
			$p_addedBy = $_SESSION['first_name'].' '.$_SESSION['last_name']; 
		
		
			//sanatize user input to make safe for browser
			$p_projName = htmlspecialchars($_GET['projName']);
			$p_abName = htmlspecialchars($_GET['abName']);
			$p_description = htmlspecialchars($_GET['description']);
			$p_subEmail = htmlspecialchars($_GET['subEmail']);
		
		
			if($p_projName == ''){
					echo '<p>You must enter a Project Name!<p>';
					$error = 'true';
			}
			else{
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
			if($p_addedBy == ''){
					echo '<p>You must enter your name!<p>';
					$error = 'true';
			}
			if($p_description == ''){
					echo '<p>You must enter a description of the project!<p>';
					$error = 'true';
			}
			if($p_subEmail == ''){
					echo '<p>You must enter the email of the submitter!<p>';
					$error = 'true';
			}
				
			//check if project name exists
			$stmt1 = $dbc->prepare("SELECT project_name FROM project_name WHERE project_name = ?");
			$stmt1 -> bind_param('s', $p_projName);
				
  			if ($stmt1->execute()){
  			
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_projName){
        				echo $p_projName." exits. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			//echo "Name exisits: No results".'<br>';//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
			
			//create seq_id from project name
			#include($path.'functions/create_seq_id.php');
			#$seq_id = create_seq_id($p_projName);
			//no longer creating seq id. user supplied
			$seq_id = $p_abName;
			
			//check if seq ID exisits. 
			$stmt2 = $dbc->prepare("SELECT seq_id_start FROM project_name WHERE seq_id_start = ?");
			$stmt2 -> bind_param('s', $seq_id);
			#$stmt2->bind_result($col1);
				
  			if ($stmt2->execute()){
  			
    			$stmt2->bind_result($name);
    			if ($stmt2->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $seq_id){
        				echo $seq_id." exits. Please check name.";
						$error = 'true';
					}
				}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			$stmt2 -> close();

			//insert info into db
		    if($error != 'true'){
		    			
				$stmt2 = $dbc -> prepare("INSERT INTO project_name (project_name,added_by,description,seq_id_start) VALUES (?,?,?,?)");
				if(!$stmt2){
					
					echo "Prepare failed: (" . $dbc->errno . ") " . $dbc->error;
				}
				
				else{
					$stmt2 -> bind_param('ssss', $p_projName,$p_addedBy,$p_description,$seq_id);
					
					$stmt2 -> execute();
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You added new Project Name Info: '.$p_projName.' with project sequence ID '.$seq_id.'<br>';
						$submitted = 'true';
						/////////////////send email to user///////////////
						$email_address = $admin_user;
						$email_to=$p_subEmail;
						$email_subject="Project Name Approval-Approved";
						$email_message="Approved Project: ".$p_projName." with the following description:\n ".$p_description." \n Project Abbrev:'".$p_abName." has been entered into the database. If needed, please reply-to:".$email_address;
						$headers = "From: admin\r\n".
						"Reply-To: .".$email_address."\r\n'" .
						"X-Mailer: PHP/" . phpversion();
						if(mail($email_to, $email_subject, $email_message, $headers)){
							echo '<script>
			    			alert("Email sent to submitter reguarding project name approval. Thank You!");
							</script>';
							
						}
						else{
							echo '<script>
			    			alert("Email not sent :( Please contact database admin. Thank you!");
							</script>';
						}		
						
						//////////////////////////////////////////////////
						
						
					}else{
						
						echo 'An error has occurred';
						mysqli_error($dbc);
						
					}
				}
			}
		}
		echo '</div>';
	?>
	<form class="registration" action="update_proj_name.php" method="GET">
		<fieldset>
		<LEGEND><b>Project Name Info:</b></LEGEND>
		<p><i>&nbsp* = required field</i></p>
		
		<pre>Note: Project Name must be between 3-19 characters and contain no spaces or special characters other than hyphens
Project abbreviation is used to create sample names for downstream sequencing submission
Submission will automatically generate an approval email to sender notifying them that their project has been added</pre>
		<p><a id="myLink" href="link">link</a></p>
		<script>
	    	var link = "query_samples/query_select_mod.php#projects";
	    	link = root+link;
	   	 	document.getElementById('myLink').setAttribute("href",link);
	    	document.getElementById('myLink').innerHTML = 'Check Existing Project Names';
		</script>
		
		<div class="col-xs-6">
		<!--Project Name-->
		<p>
		<label class="textbox-label">Project Name:*</label>
		<input type="text" name="projName" class="fields" placeholder="Enter Max 19 Characters" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_projName;} ?>"<br>
		</p>
		
		<p>
		<label class="textbox-label">Project Abbreviated Name:*</label>
		<input type="text" name="abName" class="fields" placeholder="Enter 3-5 Character" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_abName;} ?>"<br>
		</p>
		</div>
		
		<div class="col-xs-12">
		<!--Description-->
		<p>
		<label class="textbox-label">Description:*</label>
		<textarea class="form-control" from="sample_form" rows="3" name="description" placeholder = "Enter Date, Description, and Who Requested Project"><?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {echo $p_description;} ?></textarea>
		</p>
		</div>
		
		<div class="col-xs-6">
		<!--submitters email-->
		<p>
		<label class="textbox-label">Submitter's Email:*</label>
		<input type="text" name="subEmail" placeholder="Enter Email" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_subEmail;} ?>"<br>
		</p>
		</div>
		
		<!--submit button-->
		<button class="button" type="submit" name="submit" value="1"> Add </button>
		<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
