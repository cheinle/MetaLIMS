<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Alter Freezers/Drawers</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<?php include('../functions/dropDown.php'); ?>
<div class="page-header">
<h3>Alter Freezers/Drawers</h3>	
</div>
<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';
			
						//if option 1, add drawer/freezer combo to freezer_drawer table
						if($_GET['submit'] == 'add_first'){
							if($_GET['freezer'] && $_GET['drawer']){
								$p_freezer= htmlspecialchars($_GET['freezer']);
								if($p_freezer == '0'){
									echo '<p>You Must Select A Freezer Name!<p>';
									$error = 'true';
								}
								
								$p_drawer= htmlspecialchars($_GET['drawer']);
								if($p_drawer == '0'){
									echo '<p>You Must Select A Drawer Name!<p>';
									$error = 'true';
								}
							
								//check if name exists
								$stmt = $dbc->prepare("SELECT freezer_id,drawer_id FROM freezer_drawer WHERE drawer_id = ? AND freezer_id =?");
								$stmt -> bind_param('ss', $p_drawer,$p_freezer);
					
					  			if ($stmt->execute()){
					    			$stmt->bind_result($drawer_name,$freezer_name);
					    			if ($stmt->fetch()){
					        			if($drawer_name == $p_drawer && $freezer_name == $p_freezer){
					        				echo $p_drawer." Exists For Freezer:".$p_freezer."Please Check Name.";
											$error = 'true';
										}
									}
								} 
								else {
									$error = 'true';
					    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
									
								}
								$stmt -> close();
			
								//insert info into db
							    if($error != 'true'){
							    	
									if($_GET['submit'] == 'add'){
										//insert data into db. Use prepared statement 
										$stmt_add = $dbc -> prepare("INSERT INTO freezer_drawer (drawer_id,freezer_id) VALUES (?,?)");
										$stmt_add  -> bind_param('ss',$p_drawer,$p_freezer);
										
										$stmt_add  -> execute();
										$rows_affected_add  = $stmt_add  ->affected_rows;
										$stmt_add  -> close();
										
										//check if add was successful or not. Tell the user
								   		if($rows_affected_add  > 0){
											echo 'You Added A New Drawer: '.$p_drawer.' To Freezer'.$p_freezer.'<br>';
											$submitted = 'true';
										}else{
											echo 'An Error Has Occured';
											mysqli_error($dbc);
										}
									}
								
								}
							}
						}
						
			
						//if option 2 is selected, update drawer name 
						if($_GET['submit'] == 'add_second' || $_GET['submit'] == 'update'){
							//////////////////////////////////////////////////////////////////////////////////////
							//sanatize user input to make safe for browser
							$p_drawer = htmlspecialchars($_GET['drawer']);
							$p_drawer = ucfirst($p_drawer);
							
							if($p_drawer == ''){
								echo '<p>You Must Enter A Drawer Name!<p>';
								$error = 'true';
							}
							
							if($_GET['submit'] == 'update'){
								$p_oldDrawer = htmlspecialchars($_GET['oldDrawer']);
								if($p_oldDrawer == ''){
									echo '<p>You Must Enter An Old Drawer Name!<p>';
									$error = 'true';
								}
							}
							
							
							//check if name exists
							$stmt1 = $dbc->prepare("SELECT drawer_id FROM drawer WHERE drawer_id = ?");
							$stmt1 -> bind_param('s', $p_drawer);
				
				  			if ($stmt1->execute()){
				    			$stmt1->bind_result($name);
				    			if ($stmt1->fetch()){
				        			if($name == $p_drawer){
				        				echo $p_drawer." Exists. Please Check Name.";
										$error = 'true';
									}
								}
							} 
							else {
								$error = 'true';
				    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
								
							}
							$stmt1 -> close();
					
							//insert info into db
						    if($error != 'true'){
						    	
								if($_GET['submit'] == 'add'){
									//insert data into db. Use prepared statement 
									$stmt2 = $dbc -> prepare("INSERT INTO drawer (drawer_id) VALUES (?)");
									$stmt2 -> bind_param('s',$p_drawer);
									
									$stmt2 -> execute();
									$rows_affected2 = $stmt2 ->affected_rows;
									$stmt2 -> close();
									
									//check if add was successful or not. Tell the user
							   		if($rows_affected2 > 0){
										echo 'You Added A New Drawer: '.$p_drawer.'<br>';
										$submitted = 'true';
									}else{
										echo 'An Error Has Occured';
										mysqli_error($dbc);
									}
								}
			
			
								if($_GET['submit'] == 'update'){
									//update name into db
									$set_query = 'UPDATE drawer SET drawer_id = ? WHERE drawer_id = ?';
									if($set_stmt = $dbc ->prepare($set_query)) {                 
					                	$set_stmt->bind_param('ss',$p_drawer,$p_oldDrawer);
								
					                    if($set_stmt -> execute()){
											$set_rows_affected = $set_stmt ->affected_rows;
										
											$set_stmt -> close();
											if($set_rows_affected >= 0){
												echo "You Updated Drawer Name To: ".$p_drawer.'<br>';
												$submitted = 'true';
											}
											else{	
												echo 'An Error Has Occured';
												mysqli_error($dbc);
											}
										}
										else{
											echo 'An Error Has Occured';
											mysqli_error($dbc);
										}
									}
									else{
										echo 'An Error Has Occured';
										mysqli_error($dbc);
									}
								}
							}
						}
			}
			
			
			
			
			
			
			
			
	?>

<form class="registration" action="add_drawers.php" method="GET">
	<p><i>* = required field   + = required only for update</i></p>
	<div class="container-fluid">

 	<!---------------------------------------Freezer-------------------------------------->
  	<div class="row">
	<LEGEND><b>Option 1: Add New Drawer:</b></LEGEND>
	
  	<div class="col-xs-6">
	<!--Drawer Name-->
	<p>
	<label class="textbox-label">New Drawer Name:*</label>
	<input type="text" name="newDrawer" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_drawer;} ?>">
	</p>
	
	<!--Old Drawer Name-->
	<p>
	<label class="textbox-label">Old Drawer Name:+</label>
	<?php dropDown('drawer', 'drawer', 'drawer_id','drawer_id',$submitted);?></p></p>
	</div><!--end of class = 'col-xs-6'-->
	</div><!--end of row-->
	<!----------------------------------------------------------------------------->
	
	
	
	
	
	<!---------------------------------------Drawer-------------------------------------->
	<div class="row">
	<LEGEND><b>Option 2: Add New Drawer:</b></LEGEND>
  	<div class="col-xs-6">
	<!--Freezer Name-->
	<p>
	<label class="textbox-label">New Freezer Name:*</label>
	<input type="text" name="freezer" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_freezer;} ?>">
	</p>
	
	<!--Old Freezer Name-->
	<p>
	<label class="textbox-label">Old Freezer Name:+</label>
	<?php dropDown('freezer', 'freezer', 'freezer_id','freezer_id',$submitted);?>	
	</div><!--end of class = 'col-xs-6'-->
	</div><!--end of row-->
	
	

	<!----------------------------------------------------------------------------------------->
	
	
	
	
	
	
	<!---------------------------------------Drawer Freezer-------------------------------------->
	<div class="row">
	<LEGEND><b>Option 3: Add Drawer To Freezer:</b></LEGEND>
  	<div class="col-xs-6">
  		
  	<!--Freezer To Add Drawer To-->
	<label class="textbox-label">Pick A Freezer:</label>
	<p>
	<?php dropDown('freezer', 'freezer', 'freezer_id','freezer_id',$submitted);?>	
	</p>
  	
  	<label class="textbox-label">Pick A Drawer:</label>
	<p>
  	<?php dropDown('drawer', 'drawer', 'drawer_id','drawer_id',$submitted);?>
  	</p>
  	
  	</div><!--end col-xs-6-->
 	</div><!--end row-->
 	
 	
	<!--submit button-->
	<button class="button" type="submit" name="submit" value="add_second"> Add </button>
	<button class="button" type="submit" name="submit" value="update">Update</button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	<!----------------------------------------------------------------------------------------->
	
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
