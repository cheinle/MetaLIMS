<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Remove A Location</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root; ?>
<div class="page-header">
<h3>Remove A Location</h3>	
</div>
	<?php 
		//Check if samples still exist for location
		//if samples exists, tell user they have to update the location name for each of the other samples
		//(bulk update?)
		//list out the samples for the user (xls?)
		
		//if there are no samples for this location name, go ahead and change visible to 0

	
	
	
		//error && type checking 
		if(isset($_GET['submit'])){
			
			$error = 'false';
			$submitted = 'false';
			
			
			if($p_delete_entry_name == ''){
					echo '<p>You Must Enter A Location Name To Delete!<p>';
					$error = 'true';
			}
				
			//check samples exists for this project
			if($dbc->prepare("SELECT sample_name FROM sample WHERE location_name = ?")){
				$stmt1 -> bind_param('s', $p_delete_entry_name);
	
	  			if ($stmt1->execute()){
	  			
	    			$stmt1->bind_result($name);
	    			while ($stmt1->fetch()){
	        			echo "Name: $name <br>";
						$error = 'true';
					}
				} 
				else {
					$error = 'true';
	    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				}
			}
			else {
				$error = 'true';
	    		die('execute() failed: ' . htmlspecialchars($stmt1->error));	
			}
			$stmt1 -> close();


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
						
						echo 'An error has occured';
						mysqli_error($dbc);
						
					}
				}
			}
		}
	?>
	<form class="registration" action="update_proj_name.php" method="GET">
	* = required field <br>
	<strong>Note to Admin:</strong> Please Email User For Suggestions on Changes to Name/Abbrev if needed. <br>
	Add button will automatically generate an approval email to sender notifying them of approval
	
		<fieldset>
		<LEGEND><b>Project Name Info:</b></LEGEND>
		<p>Note: Project Name Must Be Between 3-19 Characters And Contain No Spaces Or Special Characters Other Than Hyphens</p>
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
