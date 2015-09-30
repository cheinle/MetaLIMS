<?php include('../database_connection.php');
error_reporting(E_ALL); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Data Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/themes/redmond/jquery-ui.css" />
		
</head>

<body>
<?php 

include('../functions/dropDown_update_for_daily_data.php');
include('../functions/text_insert_update_storage_info.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;
include($path.'/index.php');
#$parent_value = '2015-04-08';
 ?>
<div class="page-header">
<h3>Update Daily Data</h3>
</div>

<form name="form_name" id = "form_name" class="registration" onsubmit="return validate(this)" action="update_daily_data3.php" method="GET">
<p><i>* = required field </i></p>
<?php 	
		$submitted = 'false';
		//error checking 
		if(isset($_GET['submit'])){
			$error = 'false';

			//sanatize user input and check for required fields
			$p_mydate = htmlspecialchars($_GET['mydate']);
			$parent_value = $p_mydate;


			//grab list of sensors to choose from
			$query = "SELECT part_sens_name FROM particle_counter";
			$result = mysqli_query($dbc, $query);
			if(!$result){
				$error = 'true';
				echo 'An error has occured';
				mysqli_error($dbc);
			}
			while($row = mysqli_fetch_assoc($result)){
				$array[] = htmlspecialchars($row['part_sens_name']);
			}
			
			//grab all of the daily data info for this date
			$stmt1 = $dbc->prepare("SELECT part_sens_name,start_time,end_time FROM daily_data2_particle_counter WHERE daily_date = ?");
			$stmt1 -> bind_param('s', $p_mydate);
  			if ($stmt1->execute()){
    			$stmt1->bind_result($part_sens_name,$start_time,$end_time);
				$counter = 0;
				echo '<fieldset><LEGEND><b>Sensor Data:</b></LEGEND>';
				echo '<div  id = "sensor_data" name = "sensor_data">';
    			while ($stmt1->fetch()){
    				$counter++;
					$x = $counter;
        			#echo "Name:$part_sens_name $start_time $end_time<br>";
        			echo "<p>";
					echo "<label class='textbox-label'>Sensor Number".$x.":*</label>";   
					echo "<select id='sensor".$x."' name='sensor".$x."'>";
					#echo "<option value='0'>-Select-</option>";
					foreach ($array as $key => $value) {
						$name = htmlspecialchars($value);
						$id = htmlspecialchars($value);
						if($id == $part_sens_name){
							echo '<option selected="selected" value="'.$id.'">'.$name.'</option>';
						}
						else{
							#echo '<option value="'.$id.'">'.$name.'</option>';
						}
					}
					echo '</select>';
					?>
					
					
					<p>
					<label class="textbox-label">Start/End Time:*</label><br>
					<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields" value="<?php echo $start_time;?>"/>
					<input type = "text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields" value="<?php echo $end_time;?>"/>
					<h3 class="checkbox-header">Delete Sensor <?php echo $x ?></h3>
					<div class='vert-checkboxes'>
					<label class='checkbox-label'>DELETE</label>
					<input type='checkbox' name='delete<?php echo $x ?>' id='delete<?php echo $x ?>' value='DELETE'>
					</div><br />
					
				
					<script type="text/javascript">
					var num_sensors = <?php echo(json_encode($x)); ?>;
    				$(document).ready(function(){
        				$('input[name="stime'+num_sensors+'"]').ptTimeSelect();
        				timeFormat: "HH:mm"
   	 				});
   	 			
	   	 			$(document).ready(function(){
	   	 				$('input[name="etime'+num_sensors+'"]').ptTimeSelect();
	        			timeFormat: "HH:mm"
	   	 			});
				</script>
				</p></p>
				<?php
				}

			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt1->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
			
			
			//now get and populate the rest of the fields
	}
			
?>
		<input type="button" id="more_sensors" class="button" style="float:left;margin: 10px;" name ="more_sensors" value='Add More Sensors' /><br>
		<!--<div id="div1"></div>-->
		
		</div>
		</fieldset>
		<fieldset>
		<LEGEND><b>Weather Data:(Optional)</b></LEGEND>
		
		<input type="text" style="visibility:hidden" name="mydate" id="mydate" value="<?php echo text_insert_update_stinfo($parent_value,'daily_date','daily_data2');?>"/>
		
		<div id = 'inline1'>
		<p>
		<label class="textbox-label">Average Temperature:</label>
		<input type="text" name="temp" id = "temp" placeholder="Enter An Avg Temp in Celsius" value="<?php echo text_insert_update_stinfo($parent_value,'temp','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Temperature:</label><br>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('temp_record', 'records', 'records','records','temp_record', $parent_value);
		?>
		</p>
		</div>
		
		<div id = 'inline2'>
		<p>
		<label class="textbox-label">Average Relative Humidity:</label>
		<input type="text" name="hum" id = "hum" placeholder="Enter An Avg Humidity" value="<?php echo text_insert_update_stinfo($parent_value,'hum','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Humidity:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('hum_record', 'records', 'records','records','hum_record', $parent_value);
		?>
		</p>
		</div>
		
		<div id = 'inline3'>
		<p>
		<label class="textbox-label">Average CO2:</label>
		<input type="text" name="co2" id="co2" placeholder="Enter An Avg CO2" value="<?php echo text_insert_update_stinfo($parent_value,'co2','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input CO2:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('co2_record', 'records', 'records','records','co2_record', $parent_value);
		?>
		</p>
		</div>
		
		<div id = 'inline4'>
		<p>
		<label class="textbox-label">Average Windspeed:</label>
		<input type="text" name="wind" id="wind" placeholder="Enter An Avg Windspeed (m/s)" value="<?php echo text_insert_update_stinfo($parent_value,'wind','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Windspeed:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('wind_record', 'records', 'records','records','wind_record', $parent_value);
		?>
		</p>
		</div>
		
		<div id = 'inline5'>
		<p>
		<label class="textbox-label">Average Rainfall:</label>
		<input type="text" name="rain" id="rain" placeholder="Enter An Avg Rainfall (mm)" value="<?php echo text_insert_update_stinfo($parent_value,'rain','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Rain:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('rain_record', 'records', 'records','records','rain_record', $parent_value);
		?>
		</p>
		</div>
		
		<div id = 'inline6'>
		<p>
		<label class="textbox-label">Average Haze:</label>
		<input type="text" name="haze" id="haze" placeholder="Enter An Avg Haze (PSI)" value="<?php echo text_insert_update_stinfo($parent_value,'haze','daily_data2');?>"
		</p>
		
		<p>
		<label class="textbox-label">Record Used To Input Haze:</label>
		<?php
		//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
		dropDown_update_for_daily_data('haze_record', 'records', 'records','records','haze_record', $parent_value);
		?>
		</p>
		</div>
		</fieldset>
		
		
		<fieldset>
		<LEGEND><b>Daily Notes:(Optional)</b></LEGEND>
		<p>
		<label class="textbox-label">Sample Notes:</label>
		<br>
		<textarea class="form-control" from="sample_form" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php echo text_insert_update_stinfo($parent_value,'notes','daily_data2');?></textarea>
		</p>
		</fieldset>
		<!--add more sensor info!-->
<script type="text/javascript">
var counter = <?php echo json_encode($counter); ?>;
var num = counter;
$(document).ready(function() {
	//var counter = <?php echo json_encode($counter); ?>;
	
    $('#more_sensors').click(function(event) {  //on click, append to correct place, perhaps after and in the first field set
    	//var counter = '1';
    	counter++;
    	num++;
       //create new elements
		var time_label = document.createElement("label");
		time_label.className="textbox-label";
		var sensor_label = document.createElement("label");
		sensor_label.className="textbox-label";
		var checkbox_label = document.createElement("label");
		checkbox_label.className="checkbox-label";
		var h3 = document.createElement("h3");
		h3.className="checkbox-header";
		var div= document.createElement("div");
		div.className="vert-checkboxes";
		var input1 = document.createElement("input");
		var input2 = document.createElement("input");
		var select = document.createElement("select");
		var checkbox = document.createElement("input");
	  
					
		var node = document.createTextNode("Start/End Time:");
		time_label.appendChild(node);
						
		var node2 = document.createTextNode("Sensor Number" + counter + ":*");
		sensor_label.appendChild(node2);
		
		var node3 = document.createTextNode("DELETE");
		checkbox_label.appendChild(node3);
		
		var node4 = document.createTextNode("Delete Sensor" + counter + ":");
		h3.appendChild(node4);
		
		var array = <?php echo json_encode($array); ?>;
		//alert(array);
		for (index = 1; index <= array.length; ++index) {
	   		var option = array[index];
	   		//alert(option);
			var opt = document.createElement('option');
			opt.appendChild(document.createTextNode(option));
			opt.value = option;
			select.appendChild(opt);
		}		
		
		linebreak = document.createElement("br");
		linebreak2 = document.createElement("br");
		linebreak3 = document.createElement("br");
		linebreak4 = document.createElement("br");
		
					
		//add attributes to your new elements
		
		input1.setAttribute("type", "text");
    	input1.setAttribute("name", "stime"+ counter);
    	input1.setAttribute("id", "stime"+ counter);
    	input1.setAttribute("value", "");
    	input1.setAttribute("class", "shrtfields");
			
    	input2.setAttribute("type", "text");
    	input2.setAttribute("name", "etime"+ counter);
    	input2.setAttribute("id", "etime"+ counter);
    	input2.setAttribute("value", "");
    	input2.setAttribute("class", "shrtfields");
    					
    	//select.setAttribute("class", "fields");
    	select.setAttribute("name", "sensor"+ counter);
    	select.setAttribute("id", "sensor"+ counter);
    	select.setAttribute("value", "");
    	
    	checkbox.setAttribute("type", "checkbox");
    	checkbox.setAttribute("name", "delete"+ counter);
    	checkbox.setAttribute("id", "delete"+ counter);
    	checkbox.setAttribute("value", "DELETE");
						
		//append the elements to where you want them in the DOM
		var element = document.getElementById("sensor_data");
		
			
		/*you are trying to format your text boxes correctly using these
		 * 
		 */ 
		
		element.appendChild(sensor_label);
		element.appendChild(select);
		element.appendChild(time_label);
		element.appendChild(linebreak3);
		element.appendChild(input1);
		element.appendChild(input2);
		element.appendChild(linebreak4);
		element.appendChild(h3);
		element.appendChild(div);
			
		div.appendChild(checkbox_label);
		div.appendChild(checkbox);
		
    	$(document).ready(function(){
        	$('input[name="stime'+counter+'"]').ptTimeSelect();
        	timeFormat: "HH:mm"
   	 	});
   	 			
	   	$(document).ready(function(){
	   		$('input[name="etime'+counter+'"]').ptTimeSelect();
	        timeFormat: "HH:mm"
	   	 });

    });
	
   
});
</script>
		<script type="text/javascript">
				//grab the list of sensor info and append to dom
		
				//vailidate form
			    function validate(from) {
			    	
			    	//if you tried to submit, check the entire page for color?
			    	//return valid is false if you find it
			    	
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Are you sure you want to submit?');
				    }
				}
				
				function check_form(){
					
					var index;
					var valid = 'true';
					var x = num; 
					
					if(x == 0){
						valid = 'false';
					}
					else{
						//validate sensor data
						//check to see if sensor name is already input
						
						//create a contains method
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}

						var seen = [];
						for (index = 1; index <= x; ++index) {
	   	 					var sensor_name = 'sensor'+index;

	   	 					//check that sensor is picked 
	   	 					var sensor_name_value = document.getElementById(sensor_name).value;

	   	 					if(sensor_name_value == '0' || sensor_name_value == 'Needs to be added' || sensor_name_value == 'N/A' || sensor_name_value == '(pooled)' || sensor_name_value == 'test_sensor'){
	   	 						alert(sensor_name_value+" Is Not A Valid Sensor");
	   	 						document.getElementById(sensor_name).style.backgroundColor = 'yellow';
	   	 						valid = 'false'
	   	 					}
	   	 					else{
	   	 						//check to see if sensor name is already input
	   	 						if(seen.contains(sensor_name_value)){
	   	 							document.getElementById(sensor_name).style.backgroundColor = 'yellow';
	   	 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
	   	 							valid = 'false'
	   	 						}
	   	 					    else{
	   	 							seen.push(sensor_name_value);
	   	 							document.getElementById(sensor_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}

	   	 					//check that start and end date are entered
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						document.getElementById(start_time).style.backgroundColor = 'yellow';
	   	 						valid = 'false'
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;
	   	 					if(end_time_value == ''){
	   	 						document.getElementById(end_time).style.backgroundColor = 'yellow';
	   	 						valid = 'false'
	   	 					}
	   	 					else{
	   	 						document.getElementById(end_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					//check that start time is earlier than end time
	   	 					if(start_time_value > end_time_value){
	   	 						alert("Whoops! Please Check Start And End Times");
	   	 						document.getElementById(start_time).style.backgroundColor = 'yellow';
	   	 						document.getElementById(end_time).style.backgroundColor = 'yellow';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 						document.getElementById(end_time).style.backgroundColor = 'white';
	   	 					}
						}
					}
						//validate other info
	   	 				var num_divs = '6';
	   	 				for (index_div = 1; index_div <= num_divs; ++index_div) {
		   	 				var divs = document.getElementById('inline'+index_div);
		   	 				
		   	 				var inputs = divs.getElementsByTagName('input');
		   	 				var selects = divs.getElementsByTagName('select');
	
	
		   	 				for (index = 0; index < inputs.length; ++index) {
	    						//deal with inputs[index] element
	    						//assume same number of input and select fields.
	    						input_value = inputs[index].value
	    						select_value = selects[index].value
	    						
	    						
	    						//check if the dropdown exists, the input exists also (and vice versa)
	    						if((input_value == '' && select_value != '0') || ((input_value != '' && select_value == '0'))){
		   	 						inputs[index].style.backgroundColor = 'yellow';
		   	 						selects[index].style.backgroundColor = 'yellow';
		   	 						valid = 'false'
		   	 					}
		   	 					else if(input_value != '' &&  select_value != '0'){
		   	 						//check if input is there it is the correct format
		   	 						var input_check = input_value;
		   	 						var input_id = inputs[index].id;

		   	 						if(input_id == 'temp'){
		   	 							if(!input_check.match(/^\s*(?=.*[0-9])\d{0,2}(?:\.\d{1,3})?\s*$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! Temperature Should Be Up To 2 Decimal Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 							
		   	 						}
		   	 						
		   	 						else if(input_id == 'co2'){
		   	 							if(!input_check.match(/^\d{1,4}$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! CO2 Should Be A Whole Number Up To 4 Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 						
		   	 						else if(input_id == 'haze'){
		   	 							if(!input_check.match(/^[0-9]{1,2}$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! Haze Should Be A Whole Number Up To 2 Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 
		   	 						else{
		   	 							if(!input_check.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,3})?\s*$/)){
		   	 								inputs[index].style.backgroundColor = 'red';
		   	 								selects[index].style.backgroundColor = 'red';
		   	 								valid = 'false'
		   	 								alert("Whoops! "+input_check+"Should Be A Decimal Up To 3 Decimal Places");
		   	 							}
		   	 							else{
		   	 								inputs[index].style.backgroundColor = 'white';
		   	 								selects[index].style.backgroundColor = 'white';
		   	 							}
		   	 						}
		   	 						
		   	 					}
		   	 					else{//if there is no input for either input or dropdown
		   	 						inputs[index].style.backgroundColor = 'white';
		   	 						selects[index].style.backgroundColor = 'white';
		   	 					}
							}
						}
					   
						var element = document.getElementById("sensor_data");
						var sens_num = document.createElement("input");	
				    	sens_num.setAttribute("type", "text");
				    	sens_num.setAttribute("name", "sens_num");
				    	sens_num.setAttribute("value", num);
				   		sens_num.setAttribute("style", "visibility:hidden");
				   		element.appendChild(sens_num);
				    	//alert(num);///you are not catching the number of sensors added
					return valid;
				}
			
			</script>
			<input type='submit' class="button" id="sub"  name ="submit" value='Update Samples' />
		    <input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		
	</form>

	

	
</body>
	
</html>
