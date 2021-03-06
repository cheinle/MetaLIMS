<?php include('../index.php'); ?>
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Media Update</title>
<script>
	$(document).ready(function(){
   		$('[data-toggle="popover"]').popover({
        	placement : 'right'
    	});
	});
</script>
				
<style>
	.popover-content {
    	font-style: bold;
    	font-size: 14px;
	}
</style>

</head>
<body>
<div class="page-header">
<h3>Update Media Dropdown</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_medType = htmlspecialchars($_GET['medType']);
			
			if($p_medType == ''){
				echo '<p>You must enter a Media Type!<p>';
				$error = 'true';
			}
		
			//check if name exists
			$stmt1 = $dbc->prepare("SELECT media_type FROM media_type WHERE media_type = ?");
			$stmt1 -> bind_param('s', $p_medType);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_medType){
        				echo $p_medType." Exists. Please Check Name.";
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
	
			//insert info into db
		    if($error != 'true'){
		    	
				//insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO media_type (media_type) VALUES (?)");
				$stmt2 -> bind_param('s',$p_medType);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added a new Media Type: '.$p_medType;
					$submitted = 'true';
				}else{
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
		
			}
			echo '</div>';
		}
	?>

<form class="registration" action="update_media_type.php" method="GET">
	<p><i>* = required field </i></p>
	<fieldset>
	<LEGEND><b>Media Type Info:</b></LEGEND>
	<div class="col-xs-6">
	<p><a id="myLink" href="link">link</a></p>
	<script>
    	var link = "query_samples/query_select_mod.php#fragment-3";
    	link = root+link;
   	 	document.getElementById('myLink').setAttribute("href",link);
    	document.getElementById('myLink').innerHTML = 'Check if Media Exists';
	</script>
	
	<!--Media Type-->
	<p>
	<label class="textbox-label">Media Type:*</label>
	<input type="text" name="medType" class="fields" placeholder="e.g. filter or liquid media type/name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_medType;} ?>">
	</p>
	</div>

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="1"> Add </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	
	</fieldset>
</form>

</body>
</html>
