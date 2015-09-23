<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Air Sampler Update</title>
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
<h3>Update Air Sampler Dropdown</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_airSamp = htmlspecialchars($_GET['airSamp']);
			$p_serNum = htmlspecialchars($_GET['serNum']);
			$p_airID = htmlspecialchars($_GET['airID']);
			

			if($p_airSamp == ''){
					echo '<p>You must enter an Air Sampler Name!<p>';
					$error = 'true';
			}
			if($p_airID == ''){
				echo '<p>You must enter an Air Sampler Identifier!<p>';
				$error = 'true';
			}
			
			if ($p_serNum == '') {$p_serNum = NULL;}
			
			//re-create name to be more descriptive
			$p_airSamp = $p_airSamp.$p_airID;
			echo "New Air Sampler Name: ".$p_airSamp.'<br>';
			
			//check if air sampler name exists
			$stmt1 = $dbc->prepare("SELECT air_sampler_name FROM air_sampler WHERE air_sampler_name = ?");
			$stmt1 -> bind_param('s', $p_airSamp);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_airSamp){
        				echo $p_airSamp." exits. Please check name.";
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
				$stmt2 = $dbc -> prepare("INSERT INTO air_sampler (air_sampler_name, serial_num,air_sampler_identifier) VALUES (?,?,?)");
				$stmt2 -> bind_param('sss', $p_airSamp,$p_serNum,$p_airID);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added a new Air Sampler:'.$p_airSamp;
					$submitted = 'true';
				}else{
					
					echo 'An error has occured';
					mysqli_error($dbc);
					
				}
		
			}
		}
	?>
	
<form class="registration" action="update_air_sampler.php" method="GET">
	<p><i>* = required field </i></p>
	<fieldset>
	<LEGEND><b>Air Sampler Info:</b></LEGEND>
	<div class="col-xs-6">
	<p><a href="/series/dynamic/airmicrobiomes/query_select.php#airSamplers">Check if Air Sampler Exisits</a></p>
	
	<!--Air Sampler Name-->
	<p>
	<label class="textbox-label">Air Sampler Name:*</label>
	<input type="text" name="airSamp" placeholder="Enter an Air Sampler Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_airSamp;} ?>"<br>
	</p>	
	
	<!--ID-->		
	<p>
	<label class="textbox-label">Unique Unit Number/Identifier:*</label>
	<input type="text" name="airID" id="airID" data-toggle="popover" title="What is an Air Sampler Identifier?:" 
		data-content="A descriptive identifier to add to the air sampler name. For example if you are using the Coriolis marked '1', your
		Descriptive Identifier may be '1'." 
		placeholder="Enter A Descriptive Identifier (e.g. 1)" 
		value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_airID;} ?>" 
	/>
	</p>
	
	
	<!--Serial Number-->
	<p>
	<label class="textbox-label">Serial Number:</label>
	<input type="text" name="serNum" placeholder="Enter A Serial Number" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_serNum;} ?>">
	</p>
	
	</div>
	<!--submit button-->
	<button class="button" type="submit" name="submit" value="1"> Add </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	</fieldset>
</form>

</body>
</html>
