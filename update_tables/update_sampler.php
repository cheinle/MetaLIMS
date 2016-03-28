<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sampler Update</title>
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
<?php include('../index.php');
?>
<div class="page-header">
<h3>Update Sampler Dropdown</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_samp = htmlspecialchars($_GET['samp']);
			$p_serNum = htmlspecialchars($_GET['serNum']);
			$p_ID = htmlspecialchars($_GET['ID']);
			

			if($p_samp == ''){
					echo '<p>You must enter a  Sampler Name!<p>';
					$error = 'true';
			}
			if($p_ID == ''){
				echo '<p>You must enter a Sampler Identifier!<p>';
				$error = 'true';
			}
			
			if ($p_serNum == '') {$p_serNum = NULL;}
			
			//re-create name to be more descriptive
			$p_samp = $p_samp.$p_ID;
			echo "New Sampler Name: ".$p_samp.'<br>';
			
			//check if air sampler name exists
			$stmt1 = $dbc->prepare("SELECT sampler_name FROM sampler WHERE sampler_name = ?");
			$stmt1 -> bind_param('s', $p_samp);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_samp){
        				echo $p_samp." Exists. Please Check Name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name Exists: No results <br>";//no result came back so free to enter into db, no error
					
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
				$stmt2 = $dbc -> prepare("INSERT INTO sampler (sampler_name, serial_num,sampler_identifier) VALUES (?,?,?)");
				$stmt2 -> bind_param('sss', $p_samp,$p_serNum,$p_ID);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added a new Sampler: '.$p_samp;
					$submitted = 'true';
				}else{
					
					echo 'An error has occurred';
					mysqli_error($dbc);
					
				}
		
			}
			echo '</div>';
		}
	?>
	
<form class="registration" action="update_sampler.php" method="GET">
	<p><i>* = required field </i></p>
	<fieldset>
	<LEGEND><b>Sampler Info:</b></LEGEND>
	<div class="col-xs-6">
	<p><a id="myLink" href="link">link</a></p>
	<script>
    var link = "query_select_mod.php#samplers";
    link = root+link;
    document.getElementById('myLink').setAttribute("href",link);
    document.getElementById('myLink').innerHTML = 'Check if Sampler Exists';
	</script>
	
	<!--Air Sampler Name-->
	<p>
	<label class="textbox-label">Sampler Name:*</label>
	<input type="text" name="samp" placeholder="Enter a Sampler Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_samp;} ?>"<br>
	</p>	
	
	<!--ID-->		
	<p>
	<label class="textbox-label">Unique Unit Number/Identifier:*</label>
	<input type="text" name="ID" id="ID" data-toggle="popover" title="What is an Air Sampler Identifier?:" 
		data-content="A descriptive identifier to add to the air sampler name. For example if you are using the Coriolis marked '1', your
		Descriptive Identifier may be '1'." 
		placeholder="Enter A Descriptive Identifier (e.g. 1)" 
		value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_ID;} ?>" 
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
