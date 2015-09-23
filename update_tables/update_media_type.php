<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Media Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

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
<?php include('../index.php'); ?>
<div class="page-header">
<h3>Update Media Dropdown</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
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
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_medType){
        				echo $p_medType." exits. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
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
					
					echo 'An error has occured';
					mysqli_error($dbc);
					
				}
		
			}
		}
	?>

<form class="registration" action="update_media_type.php" method="GET">
	<p><i>* = required field </i></p>
	<fieldset>
	<LEGEND><b>Media Type Info:</b></LEGEND>
	<div class="col-xs-6">
	<p><a href="/series/dynamic/airmicrobiomes/query_select.php#airSamplers">Check if Media Type Exisits</a></p>

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
