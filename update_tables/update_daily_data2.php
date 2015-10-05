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
include('../config/path.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;
include($path.'/index.php');
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
			$p_mylocation = htmlspecialchars($_GET['loc']);
			$parent_value = $p_mydate;


			//grab list of sensors to choose from
			$query = "SELECT part_sens_name FROM particle_counter";
			$result = mysqli_query($dbc, $query);
			if(!$result){
				$error = 'true';
				echo 'An Error Has Occurred';
				mysqli_error($dbc);
			}
			while($row = mysqli_fetch_assoc($result)){
				$array[] = htmlspecialchars($row['part_sens_name']);
			}

			//grab list of records to choose from
			$query_records = "SELECT records FROM records";
			$result_records = mysqli_query($dbc, $query_records);
			if(!$result_records){
				$error = 'true';
				echo 'An Error Has Occurred';
				mysqli_error($dbc);
			}
			while($row_records = mysqli_fetch_assoc($result_records)){
				$array_records[] = htmlspecialchars($row_records['records']);
			}
			
			//grab all of the daily data info for this date
			$stmt1 = $dbc->prepare("SELECT part_sens_name,start_time,end_time,avg_measurement,record_source FROM daily_data2_particle_counter WHERE daily_date = ? AND location = ?");
			$stmt1 -> bind_param('ss', $p_mydate,$p_mylocation);
  			if ($stmt1->execute()){
    			$stmt1->bind_result($part_sens_name,$start_time,$end_time,$avg_measurement,$record);
				$counter = 0;
				
				echo '<fieldset><LEGEND><b>Sensor Data: '.$p_mydate.' '.$p_mylocation.'</b></LEGEND>';
				echo '<div class="col-xs-6">';
				echo '<div  id = "sensor_data" name = "sensor_data">';
    			while ($stmt1->fetch()){
    				$counter++;
					$x = $counter;
        			echo "<p>";
					echo "<label class='textbox-label'>Sensor Number".$x.":*</label>";   
					echo "<select id='sensor".$x."' name='sensor".$x."'>";
					foreach ($array as $key => $value) {
						$name = htmlspecialchars($value);
						$id = htmlspecialchars($value);
						if($id == $part_sens_name){
							echo '<option selected="selected" value="'.$id.'">'.$name.'</option>';
						}
					}
					echo '</select>';
					?>
					
					
					<p>
					<label class="textbox-label">Start/End Time:*</label><br>
					<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields" value="<?php echo $start_time;?>"/>
					<input type = "text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields" value="<?php echo $end_time;?>"/>
					
					
				
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
				
				<label class="textbox-label">Average Sensor Measurement<?php echo $x ?>:</label><br>
				<input type="text" name="measurement<?php echo $x ?>" id = "measurement<?php echo $x ?>" class="fields" placeholder="Enter An Avg Measurement" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo text_insert_update_daily_data($parent_value,'avg_measurement','daily_data2_particle_counter',$p_mylocation,$root);}?>">
				</p>
				
				<p>
				<label class="textbox-label">Record Source For Sensor Measurement<?php echo $x ?>:</label><br>
				<?php
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date
				dropDown_update_for_daily_data('record'.$x, 'records', 'records','records','record_source', $parent_value,$p_mylocation);
				//dropDown('record'.$x, 'records', 'records','records',$submitted);
				?>
				</p>
				
				<h3 class="checkbox-header">Delete Sensor Info <?php echo $x ?></h3>
				<div class='vert-checkboxes'>
				<label class='checkbox-label'>DELETE</label>
				<input type='checkbox' name='delete<?php echo $x ?>' id='delete<?php echo $x ?>' value='DELETE'>
				</div><br />
				
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
		</div>
		</fieldset>
		
		<fieldset>
		<LEGEND><b>Daily Notes:(Optional)</b></LEGEND>
		<p>
		<label class="textbox-label">Sample Notes:</label>
		<br>
		<textarea class="form-control" from="sample_form" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php echo text_insert_update_daily_data($parent_value,'notes','daily_data2',$p_mylocation,$root);?></textarea>
		</p>
		
		
		<input type="text" style="visibility:hidden" name="mydate" id="mydate" value="<?php echo text_insert_update_daily_data($parent_value,'daily_date','daily_data2',$p_mylocation,$root);?>"/>
		<input type="text" style="visibility:hidden" name="loc" id="loc" value="<?php echo text_insert_update_daily_data($parent_value,'location','daily_data2',$p_mylocation,$root);?>"/>
		
		</fieldset>
		<!--add more sensor info!-->
		<script type="text/javascript">
		var counter = <?php echo json_encode($counter); ?>;
		var num = counter;
		$(document).ready(function() {
	
		    $('#more_sensors').click(function(event) {  //on click, append to correct place, perhaps after and in the first field set
		    	//var counter = '1';
		    	counter++;
		    	num++;
		       //create new elements
				var time_label = document.createElement("label");
				time_label.className="textbox-label";
				var sensor_label = document.createElement("label");
				sensor_label.className="textbox-label";
				var measurement_label = document.createElement("label");
				measurement_label.className="textbox-label";
				var record_label = document.createElement("label");
				record_label.className="textbox-label";
				var checkbox_label = document.createElement("label");
				checkbox_label.className="checkbox-label";
				var h3 = document.createElement("h3");
				h3.className="checkbox-header";
				var div= document.createElement("div");
				div.className="vert-checkboxes";
				var input1 = document.createElement("input");
				var input2 = document.createElement("input");
				var input_measurement = document.createElement("input");
				var select = document.createElement("select");
				var select_record = document.createElement("select");
				var checkbox = document.createElement("input");
			  
							
				var node = document.createTextNode("Start/End Time:");
				time_label.appendChild(node);
								
				var node2 = document.createTextNode("Sensor Number" + counter + ":*");
				sensor_label.appendChild(node2);
				
				var node_measurement = document.createTextNode("Measurement Number" + counter + ":*");
				measurement_label.appendChild(node_measurement);
				
				var node_record = document.createTextNode("Record Number" + counter + ":*");
				record_label.appendChild(node_record);
				
				var node3 = document.createTextNode("DELETE");
				checkbox_label.appendChild(node3);
				
				var node4 = document.createTextNode("Delete Sensor" + counter + ":");
				h3.appendChild(node4);
				
				var array = <?php echo json_encode($array); ?>;
				var opt = document.createElement('option');
				opt.appendChild(document.createTextNode('-Select-'));
				opt.value = '0';
				select.appendChild(opt);
				for (var index = 1; index <= array.length; index++) {
			   		var option = array[index];
			   		//alert(option);
					var opt = document.createElement('option');
					opt.appendChild(document.createTextNode(option));
					opt.value = option;
					select.appendChild(opt);
				}		
				
				var array_records = <?php echo json_encode($array_records); ?>;
				var opt = document.createElement('option');
				opt.appendChild(document.createTextNode('-Select-'));
				opt.value = '0';
				select_record.appendChild(opt);
				for (var index = 1; index <= array_records.length; index++) {
			   		var option = array_records[index];
			   		//alert(option);
					var opt = document.createElement('option');
					opt.appendChild(document.createTextNode(option));
					opt.value = option;
					select_record.appendChild(opt);
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
		    	
		    	input_measurement.setAttribute("type", "text");
		    	input_measurement.setAttribute("name", "measurement"+ counter);
		    	input_measurement.setAttribute("id", "measurement"+ counter);
		    	input_measurement.setAttribute("value", "");
		    	//input_measurement.setAttribute("class", "fields");
		    					
		    	//select.setAttribute("class", "fields");
		    	select.setAttribute("name", "sensor"+ counter);
		    	select.setAttribute("id", "sensor"+ counter);
		    	select.setAttribute("value", "");
		    	
		    	select_record.setAttribute("name", "record"+ counter);
		    	select_record.setAttribute("id", "record"+ counter);
		    	select_record.setAttribute("value", "");
		    	
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
				element.appendChild(measurement_label);
				//element.appendChild(linebreak3);
				element.appendChild(input_measurement);
				element.appendChild(record_label);
				element.appendChild(select_record);
				//element.appendChild(linebreak3);
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
				 function validate(form) {
			    	
			    	//if you tried to submit, check the entire page
			    	//return valid is false if you find erro
			    	var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';	
				    }
				    if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Sure You Want To Add: '+date+'??? Action Cannot Be Easily Undone');
				    }
				}
				
				function check_form(){
					var valid = 'true';
					var x = counter;
   	 				
   	 				//check selects are selected for required data
					var selects = document.getElementsByTagName("select");
		            var i2;
		             for (i2 = 0; i2 < selects.length; i2++) {
		                 selected = selects[i2].value;
		                 var name2 = selects[i2].getAttribute("name");
		                
			                 if(selected == '0'){
			                 	selects[i2].style.backgroundColor = "blue";
			                    valid = 'false';
			                 }
			                 else{
			                 	selects[i2].style.backgroundColor = "white";
			                 }

					}

					 //grab all inputs
		             var inputs = document.getElementsByTagName("input");
		             var txt = "";
		             var i;
		             for (i = 0; i < inputs.length; i++) {
		                 txt = inputs[i].value;
		                 var name = inputs[i].getAttribute("name");
		                 //check if your input is empty
			             var n = txt.length;
			             if(n == 0){
			             	inputs[i].style.background = "blue";
			                valid = 'false';
		                 }else{
							inputs[i].style.background = "white";
						}
					}
				
					if(valid == 'true'){ //if your form is still valid, go ahead and do some more checks
						//create a contains method to check if sensor is entered more than once
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						
						var seen = [];
						//validate sensor data
						for (var index = 1; index <= x; index++) {
	   	 					var sensor_name = 'sensor'+index;
	   	 					//check that sensor is picked 
	   	 					var sensor_name_value = document.getElementById(sensor_name).value;
	   	 					
	 						//check to see if sensor name is already input
	 						if(seen.contains(sensor_name_value)){
	 							document.getElementById(sensor_name).style.backgroundColor = 'blue';
	 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
	 							valid = 'false';
	 						}
	 					    else{
	 							seen.push(sensor_name_value);
	 							document.getElementById(sensor_name).style.backgroundColor = 'white';
	 						}
	   	 					
	   	 					//check that start time is earlier than end time
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;

	   	 					if(start_time_value > end_time_value){
	   	 						alert("Whoops! Please Check Start And End Times");
	   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
	   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 						document.getElementById(end_time).style.backgroundColor = 'white';
	   	 					}
		   	 				
		   	 				//check avg sensor measurement is a 2 digit decimal
		   	 				var measurement = 'measurement'+index;
	   	 					var measurement_value = document.getElementById(measurement).value;
	

 							if(!measurement_value.match(/^\s*(?=.*[0-9])\d{0,4}(?:\.\d{1,2})?\s*$/)){
 								document.getElementById(measurement).style.backgroundColor = 'blue';
 								valid = 'false'
 								alert("Whoops! Measurement Should Be No More Than 2 Decimal Places And 6 Digits");
 							}
 							else{
 								document.getElementById(measurement).style.backgroundColor = 'white';
 							}
	   	 					
						}
					}
					var element = document.getElementById("sensor_data");
					var sens_num = document.createElement("input");	
				    sens_num.setAttribute("type", "text");
				    sens_num.setAttribute("name", "sens_num");
				    sens_num.setAttribute("value", num);
				   	//sens_num.setAttribute("style", "visibility:hidden");
				   	element.appendChild(sens_num);
				   	
				   	
					return valid;
				
				}
			
			</script>
			<input type='submit' class="button" id="sub"  name ="submit" value='Update Samples' />
		    <input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		
	</form>

	

	
</body>
	
</html>
