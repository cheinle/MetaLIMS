<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Sample Type</title>
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
<h3>Add Sample Type</h3>	
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_sampleType = htmlspecialchars($_GET['sampleType']);
			$p_sampleID = htmlspecialchars($_GET['sampleID']);
			$p_sampleType = ucfirst($p_sampleType);
			$p_sampleID = strtoupper($p_sampleID);
			
			if($p_sampleType == ''){
				echo '<p>You Must Enter A Sample Type!<p>';
				$error = 'true';
			}
			
			if($p_sampleID == ''){
				echo '<p>You Must Enter A Sample ID!<p>';
				$error = 'true';
			}
			
			//check format of ID
			$regrex_check  = '/[A-Z]{1,3}$/';
			if (!preg_match("$regrex_check", $p_sampleID)){
				echo '<p>ERROR: You Must Enter A Valid Sample ID. Please Check ID<br><p>';
				$error = 'true';
			}
		
			//check if name exists
			$stmt1 = $dbc->prepare("SELECT sample_type_id,sample_type_name FROM sample_type WHERE sample_type_name = ? OR sample_type_id = ?");
			$stmt1 -> bind_param('ss', $p_sampleType,$p_sampleID);

  			if ($stmt1->execute()){
    			$stmt1->bind_result($id,$name);
    			if ($stmt1->fetch()){
        			if($name == $p_sampleType){
        				echo $p_sampleType." Exists. Please Check Name.<br>";
						$error = 'true';
					}
					if($id == $p_sampleID){
        				echo $p_sampleID." Exists. Please Check ID.<br>";
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
				$stmt2 = $dbc -> prepare("INSERT INTO sample_type (sample_type_id,sample_type_name) VALUES (?,?)");
				$stmt2 -> bind_param('ss',$p_sampleID,$p_sampleType);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You Added A New Sample Type: '.$p_sampleType.' With ID '.$p_sampleID.'<br>';
					$submitted = 'true';
				}else{
					
					echo 'An Error Has Occured';
					mysqli_error($dbc);
					
				}
		
			}
			echo '</div>';
		}
	?>

<form class="registration" action="add_sample_type.php" method="GET">
	<p><i>* = required field </i></p>
	<div class="container-fluid">
	<fieldset>
	<div class="row">
	<LEGEND><b>Sample Type Info:</b></LEGEND>
	

  	<div class="col-xs-6">
  	<p class = "adjust"><i>Note: Sample Type ID Is Used As A Unique Sample Type Identifier In the Sample Name</i></p>
	<br>

	<!--Sample Type Name-->
	<p>
	<label class="textbox-label">Sample Type Name:*</label>
	<input type="text" name="sampleType" class="fields" placeholder="Name" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_sampleType;} ?>">
	</p>
	
	<!--Sample Type ID-->
	<p>
	<label class="textbox-label">Sample Type ID:*</label>
	<input type="text" name="sampleID" class="fields" placeholder="1-3 letter abbreviation" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_sampleID;} ?>">
	</p>
	
	</div><!--end of class = 'col-xs-6'-->

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="1"> Add </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	</div><!--end of class = 'row'-->
	</fieldset>
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
