<?php include('database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mark Sequencing Submission Recieved</title>
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
<?php include('index.php'); ?>
<div class="page-header">
<h3>Mark Sequencing Submission Recieved</h3>	
</div>
<?php 	
		$p_id = htmlspecialchars($_GET['id']);
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_date = htmlspecialchars($_GET['date']);
			
			//insert info into db
		    if($error != 'true'){
		    	
				//insert data into db. Use prepared statement 
				$query = 'UPDATE sequencing2 SET results_recieved = ? WHERE sequencing_info = ?';
				if($stmt = $dbc ->prepare($query )) {   
					$stmt -> bind_param('ss',$p_date, $p_id);
					
					if($stmt -> execute()){
						$rows_affected = $stmt ->affected_rows;
						$stmt -> close();
						
						//check if add was successful or not. Tell the user
				   		if($rows_affected > 0){
							echo 'You Updated Date Sequencing Results Were Recieved: '.$p_id.''.$p_date.'<br>';
							$submitted = 'true';
						}else{
							echo 'An error has occurred';
							mysqli_error($dbc);
						}
					}
					else{
						echo 'An error has occurred';
						mysqli_error($dbc);
					}
				}
				else{
					echo 'An error has occurred';
					mysqli_error($dbc);
				}
		
			}
		}
	?>

<form class="registration" action="recieved_seq_results.php" method="GET">
	<p><i>* = required field </i></p>
	<fieldset>
	<LEGEND><b>Date Recieved Sequencing Submission <?php echo $p_id;?>:</b></LEGEND>
	<div class="col-xs-6">
	<p>
		
	<label class="textbox-label">Sequencing Submission:</label>
	<input type="text" id="datepicker"  name="id" value="<?php echo $p_id;?>">
	
	<label class="textbox-label">SelectDate:</label>
	<input type="text" id="seq_date_recieved"  name="date"></p>
	<script>
		$('#seq_date_recieved').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	
	</p>
	</div>

	<!--submit button-->
	<button class="button" type="submit" name="submit" value="1"> Update </button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	
	
	</fieldset>
</form>

</body>
</html>
