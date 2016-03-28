<?php include('../database_connection.php'); ?>
<?php include('../index.php'); ?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Daily Weather</title>
</head>
<body>
<div class="page-header">
<h3>Update Daily Weather</h3>
</div>
<?php 	
		//error checking 
		if(isset($_GET['submit'])){
			echo '<div class="border">';
			//print_r($_GET);
			$error = 'false';
			$submitted = 'false';
			
			//sanatize user input to make safe for browser
			$p_date = htmlspecialchars($_GET['date']);
			$p_tot_dur_rain = htmlspecialchars($_GET['tot_dur_rain']);
			$p_tot_amt_rain = htmlspecialchars($_GET['tot_amt_rain']);
			$p_tot_rain_events = htmlspecialchars($_GET['tot_rain_events']);
			
			//check and process hourly rain info
			$count_hr;
			$count_amt;
			$count_dur;
			
			//hours
			include('check_hourly_rain_entry.php');
			if(isset($_GET['hours'])){
				$array=$_GET['hours'];
				$regrex_check = "/(2[0-3]|[1][0-9]|[1-9]):[0-5][0-9]/";
				$check = check_hourly_rain_entry($array,'false',$regrex_check);
				if($check['boolean'] == 'false'){
					echo '<p>You must Enter valid Hours(s). Please check entry(s)<p>';
					$error = 'true';
					$name_check = 'true';
				}
				else{
					$p_hours = $check['cat_name'];
					$count_hr = $check['cat_count'];
				}
			}
			
			//amt
			if(isset($_GET['amt'])){
				$array=$_GET['amt'];
				$regrex_check = "/^[0-9]?[0-9]\.[0-9][0-9]?$/";
				$check = check_hourly_rain_entry($array,'false',$regrex_check);
				if($check['boolean'] == 'false'){
					echo '<p>You must Enter valid Amount(s). Please check entry(s)<p>';
					$error = 'true';
					$name_check = 'true';
				}
				else{
					$p_amt = $check['cat_name'];
					$count_amt = $check['cat_count'];
				}
			}
			
			//duration
			if(isset($_GET['dur'])){
				$array=$_GET['dur'];
				$regrex_check = "/^([0-9]|[0-9][0-9])$/";
				$check = check_hourly_rain_entry($array,'false',$regrex_check);
				if($check['boolean'] == 'false'){
					echo '<p>You must Enter valid Duration Time(s). Please check entry(s)<p>';
					$error = 'true';
					$name_check = 'true';
				}
				else{
					$p_dur = $check['cat_name'];
					$count_dur = $check['cat_count'];
				}
			}

			//now check if sizes of each array are not the same size, give an error
			if(!(($count_hr == $count_amt) && ($count_hr == $count_dur))){
				echo "you were not the same:".$count_hr.'<br>'.$count_amt.'<br>'.$count_dur.'<br>';
			}

			//checks if date exists
			if($p_date == ''){
					echo '<p>You must a daily weather date!<p>';
					$error = 'true';
			}
			
			if(!isset($_GET['rainyn'])){
				echo '<p>You must enter if it has rained or not rained!<p>';
					$error = 'true';
			}
			else{
				$p_rainyn = htmlspecialchars($_GET['rainyn']);
				
				//check that if the user said no rain, that they don't report rain info
				if($p_rainyn == 'no'){
					if(($p_tot_dur_rain) || ($p_tot_amt_rain) || ($p_hours)||($p_tot_rain_events)){
						echo '<p>Please check input. Check if all rain info is correct<p>';
						$error = 'true';
					}
				
				}
				elseif($p_rainyn == 'yes'){
						if(!($p_tot_dur_rain) || !($p_tot_amt_rain) || !($p_hours) ||!($p_tot_rain_events)){
							echo '<p>Please check input. Check if all raining info is included/correct<p>';
							$error = 'true';
						}
						
						//check format of input for first section
						if (!preg_match("/^\d+$/", $p_tot_dur_rain)){
			   				echo "Error: Please check input for total duration it rained.".'<br>';
							$error = 'true';
						}
						if (!preg_match("/^[0-9]?[0-9]?[0-9]\.[0-9][0-9]?$/", $p_tot_amt_rain)){
			   				echo "Error: Please check input for total amount it rained.".'<br>';
							$error = 'true';
						}
						if (!preg_match("/^[0-9]?[0-9]$/", $p_tot_rain_events)){
			   				echo "Error: Please check input for how many times it rained".'<br>';
							$error = 'true';
						}
			
				}
				else{//rain data should be defective
					if(($p_tot_dur_rain) || ($p_tot_amt_rain) || ($p_hours)||($p_tot_rain_events)){
						echo '<p>Please check input. Check if all rain info is correct<p>';
						$error = 'true';
					}
					else{
						$p_tot_dur_rain = '99';
						$p_tot_amt_rain = '999.99';
						$p_tot_rain_events = '99';
					
						$p_hours = '0';
						$p_amt = '0';
						$p_dur = '0';
						
					}
					
				}
			}
			
			//check if air sampler name exists
			$stmt1 = $dbc->prepare("SELECT daily_date FROM daily_weather WHERE daily_date = ?");
			$stmt1 -> bind_param('s', $p_date);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			if($name == $p_date){
        				echo $p_date." Exists. Please Check Name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
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
		    	
				//note: at this time these cannot be null(required) but format the fields correctly in case we do switch to allow null
		    	//set to null any non-required fields that are not populated
				
				if ($p_tot_dur_rain == '') {$p_tot_dur_rain = '0';}
				if ($p_tot_amt_rain == '') {$p_tot_amt_rain = '0';}
				if ($p_tot_rain_events == '') {$p_tot_rain_events = '0';}
				
				if ($count_hr == '0') {$p_hours = '0';}
				if ($count_amt == '0') {$p_amt = '0';}
				if ($count_dur == '0') {$p_dur = '0';}
				
				
				//insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO daily_weather (daily_date, rain_boolean, total_amt_rain,total_dur_rain,num_rain_events,hours_rained,hour_amt_rained,hour_dur_rained) VALUES (?,?,?,?,?,?,?,?)");
				$stmt2 -> bind_param('ssssssss', $p_date,$p_rainyn, $p_tot_amt_rain, $p_tot_dur_rain, $p_tot_rain_events,$p_hours,$p_amt,$p_dur);
				
				$stmt2 -> execute();
				$rows_affected2 = $stmt2 ->affected_rows;

				/* close statement */
				$stmt2 -> close();
				
				//check if add was successful or not. Tell the user
		   		if($rows_affected2 > 0){
					echo 'You added new Daily Weather Info for:'.$p_date;
					$submitted = 'true';
				}else{
					echo 'An error has occurred';
					mysqli_error($dbc);
				}
			}
			echo '</div>';
		}
	?>
</pre>
	
	<form class="navbar-form" action="update_daily_weather.php" method="GET">
	<p><i>* = required field </i></p>
	
		<fieldset>
		<LEGEND><b>Daily Rain Info</b></LEGEND>
		<p>
		<label>Daily Weather DATE:*</label><br>
		<p><input type="text" id="datepicker"  name="date" class="fields" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_date;} ?>"/></p>
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		</p>
		
		
		<p>
		<label>Did it rain at all today?:* </label><br>
		<div style="border:1px solid gray;float:left;width:4.20in">
		Yes: <input type="radio" name="rainyn" value="yes" <?php if(isset($_GET['rainyn']) && $_GET['rainyn'] == 'yes'){echo 'checked="checked"';}?>/> No: <input type="radio" name="rainyn" value="no" <?php if(isset($_GET['rainyn']) && $_GET['rainyn'] == 'no'){echo 'checked="checked"';}?>/>  	
		Defective Rain Data: <input type="radio" name="rainyn" value="defective" <?php if(isset($_GET['rainyn']) && $_GET['rainyn'] == 'defective'){echo 'checked="checked"';}?>/><br>  	
		</div><br>
		</p>
		
		<p>
		<label>Total Duration of Rain During Day:</label><br>
		<input type="text" name="tot_dur_rain" class="fields" placeholder="Enter Total Duration of Rain (mins)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_tot_dur_rain;} ?>">
		</p>
		
		<p>
		<label>Total Amount of Rain During Day:</label><br>
		<input type="text" name="tot_amt_rain" class="fields" placeholder="Enter Total Amount of Rain (mm) (e.g. 2.0)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_tot_amt_rain;} ?>">
		</p>
		
		<p>
		<label>How Many Times Rained in the Day:</label><br>
		<input type="text" name="tot_rain_events" class="fields" placeholder="Enter Number of Hours it Rained (e.g. 4)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_tot_rain_events;} ?>">
		</p>
		</fieldset>
		
		<fieldset>
		<LEGEND><b>Hourly Rain Info</b></LEGEND>
		<p>
		<!--Hours it rained-->
		<label for="hours_rained" style='width:4.20in'>Enter Hour it Rained: (24hr)</label>
		<label for="pool_extract" style='width:4.20in'>Enter Amount of Rain for Corresponding Rain Hour:</label>
		<label for="pool_extract" style='width:4.20in'>Enter Duration of Rain for Corresponding Rain Hour:</label>
		
		
		<p class="clone"> 
		<input type="text" name="hours[]" class='input' style='width:4.20in;' placeholder="e.g. 22:00" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_hours;} ?>"/>
		<input type="text" name="amt[]" class='input' style='width:4.20in;' placeholder="e.g. 2.2 (mm's)" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_amt;} ?>"/>
		<input type="text" name="dur[]" class='input' style='width:4.20in;' placeholder="e.g. 20 (mins)" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_dur;} ?>"/>
		</p>
		
		<p><a href="#" class="add" rel=".clone">Add More Hourly Rain Info</a></p>
		</p>
		<br>
		
		<script type="text/javascript">
				$(document).ready($(function(){
					var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
						$('a.add').relCopy({ append: removeLink}); 
					})
				);
		</script>
		
		</fieldset>

		<!--submit button-->
		<p><button class="btn btn-success" type="submit" name="submit" value="1"> Add </button></p>
		<p><input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" /></p>
		
	</form>
	
	

	
</body>
	
</html>
