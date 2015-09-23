<?php

			include('database_connection.php');
			$p_sample_name = htmlspecialchars($_GET['sample_name']);


			//grab list of sensors to choose from
			$query = "SELECT air_sampler_name FROM air_sampler";
			$result = mysqli_query($dbc, $query);
			if(!$result){
				$error = 'true';
				echo 'An error has occured';
				mysqli_error($dbc);
			}
			while($row = mysqli_fetch_assoc($result)){
				$array[] = htmlspecialchars($row['air_sampler_name']);
			}
			
			//grab all of the air sampler info for this sample
			$stmt1 = $dbc->prepare("SELECT air_sampler_name,start_date_time,end_date_time,total_date_time FROM sample_air_sampler WHERE sample_name = ?");
			$stmt1 -> bind_param('s', $p_sample_name);
  			if ($stmt1->execute()){
    			$stmt1->bind_result($air_sampler_name,$start,$end,$total);
				$counter = 0;
			
					
				
				
				echo '<fieldset><LEGEND><b>Air Sampler:</b></LEGEND>';
				echo '<div  id = "airSamp_div" name = "airSamp_div">';
    			while ($stmt1->fetch()){
    				$counter++;
					$x = $counter;
					
					//split start and end to their time and dates
					$explode_start = explode(" ",$start);
					$start_date = $explode_start[0];
					$start_time = $explode_start[1];
					
					$explode_end = explode(" ",$end);
					$end_date = $explode_end[0];
					$end_time = $explode_end[1];
					
					
        			#echo "Name:$part_sens_name $start_time $end_time<br>";
        			echo "<p>";
					echo "<label class='textbox-label'>Air Sampler ".$x.":*</label>";   
					echo "<select id='airSamp".$x."' name='airSamp".$x."'>";
					#echo "<option value='0'>-Select-</option>";
					foreach ($array as $key => $value) {
						$name = htmlspecialchars($value);
						$id = htmlspecialchars($value);
						if($id == $air_sampler_name){
							echo '<option selected="selected" value="'.$id.'">'.$name.'</option>';
						}
						else{
							#echo '<option value="'.$id.'">'.$name.'</option>';
						}
					}
					echo '</select>';
					?>

	   	 			
	   	 			<label class="textbox-label">Start Date/Time <?php echo $x ?>:*</label>
					<input type="text" id="sdate<?php echo $x ?>"  class = "shrtfields" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php echo $start_date; ?>"/>
					<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields"  placeholder="Time"  value="<?php echo $start_time?>"/>
					
					<label class="textbox-label">End Date/Time<?php echo $x ?>:*</label>
					<input type="text" id="edate<?php echo $x ?>" class = "shrtfields" placeholder = "Date" name="edate<?php echo $x ?>" value="<?php echo $end_date; ?>"/>
					<input type="text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields"  placeholder="Time" value="<?php echo $end_time;?>"/>
					
					<script type="text/javascript">
					$('#sdate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
					$('#edate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	
					var air_samp_num = <?php echo(json_encode($x)); ?>;
	    			$(document).ready(function(){
	        			$('input[name="stime'+air_samp_num+'"]').ptTimeSelect();
	        			timeFormat: "HH:mm"
	   	 			});
	   	 			
	   	 			$(document).ready(function(){
	   	 				$('input[name="etime'+air_samp_num+'"]').ptTimeSelect();
	        			timeFormat: "HH:mm"
	   	 			});
				</script>
				
				<h3 class="checkbox-header">Delete Sensor <?php echo $x ?></h3>
				<div class='vert-checkboxes'>
				<label class='checkbox-label'>DELETE</label>
				<input type='checkbox' name='delete<?php echo $x ?>' id='delete<?php echo $x ?>' value='DELETE'>
				</div><br />

				<?php
				}

			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
			
			
			//now get and populate the rest of the fields
	}
			
?>
		<input type="button" id="more_air_samplers" class="button" style="float:left;margin: 10px;" name ="more_air_samplers" value='Add More Air Samplers' /><br>
		<!--<div id="div1"></div>-->
		
		</div>
		</fieldset>
		
		<!--add more sensor info!-->
		<script type="text/javascript">
		var counter = <?php echo json_encode($counter); ?>;
		var num = counter;
		$(document).ready(function() {
	
	
      $('#more_air_samplers').click(function(event) {  //on click, append to correct place, perhaps after and in the first field set
    	//var counter = '1';
    	counter++;
    	num++;
       //create new elements
		var start_label = document.createElement("label");
		start_label.className="textbox-label";
		var end_label = document.createElement("label");
		end_label.className="textbox-label";
		var airSamp_label = document.createElement("label");
		airSamp_label.className="textbox-label";
		var checkbox_label = document.createElement("label");
		checkbox_label.className="checkbox-label";
		var h3 = document.createElement("h3");
		h3.className="checkbox-header";
		var div= document.createElement("div");
		div.className="vert-checkboxes";
		var input1 = document.createElement("input");
		var input2 = document.createElement("input");
		var input3 = document.createElement("input");
		var input4 = document.createElement("input");
		var select = document.createElement("select");
		var checkbox = document.createElement("input");
	  
					
		var node = document.createTextNode("Start Date/Time:"+ counter + ":*");
		start_label.appendChild(node);
						
		var node2 = document.createTextNode("Air Sampler " + counter + ":*");
		airSamp_label.appendChild(node2);
		
		var node3 = document.createTextNode("DELETE");
		checkbox_label.appendChild(node3);
		
		var node4 = document.createTextNode("Delete Sensor" + counter + ":");
		h3.appendChild(node4);
		
		var node5 = document.createTextNode("End Date/Time:"+ counter + ":*");
		end_label.appendChild(node5);
		
		var array = <?php echo json_encode($array); ?>;
		
		array.unshift("-Select-");
		for (index = 0; index < array.length; ++index) {
	   		var option = array[index];
	   		//alert(option);
			var opt = document.createElement('option');
			opt.appendChild(document.createTextNode(option));
			if(option == '-Select-'){
				opt.value = '0';
			}
			else{
				opt.value = option;
			}
			select.appendChild(opt);
		}		
		
		linebreak = document.createElement("br");
		linebreak2 = document.createElement("br");
		linebreak3 = document.createElement("br");
		linebreak4 = document.createElement("br");
		linebreak5 = document.createElement("br");
		linebreak6 = document.createElement("br");
		
					
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
    	
    	
    	input3.setAttribute("type", "text");
    	input3.setAttribute("name", "sdate"+ counter);
    	input3.setAttribute("id", "sdate"+ counter);
    	input3.setAttribute("value", "");
    	input3.setAttribute("class", "shrtfields");
			
    	input4.setAttribute("type", "text");
    	input4.setAttribute("name", "edate"+ counter);
    	input4.setAttribute("id", "edate"+ counter);
    	input4.setAttribute("value", "");
    	input4.setAttribute("class", "shrtfields");
    					
    	//select.setAttribute("class", "fields");
    	select.setAttribute("name", "airSamp"+ counter);
    	select.setAttribute("id", "airSamp"+ counter);
    	select.setAttribute("value", "");
    	
    	checkbox.setAttribute("type", "checkbox");
    	checkbox.setAttribute("name", "delete"+ counter);
    	checkbox.setAttribute("id", "delete"+ counter);
    	checkbox.setAttribute("value", "DELETE");
						
		//append the elements to where you want them in the DOM
		var element = document.getElementById("airSamp_div");
		
			
		/*you are trying to format your text boxes correctly using these
		 * 
		 */ 
		
		element.appendChild(airSamp_label);
		element.appendChild(select);
		
		element.appendChild(start_label);
		element.appendChild(linebreak3);
		element.appendChild(input3);
		element.appendChild(input1);
		element.appendChild(linebreak4);
		
		element.appendChild(end_label);
		element.appendChild(linebreak5);
		element.appendChild(input4);
		element.appendChild(input2);
		element.appendChild(linebreak6);
		
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
	   	 
	   	$('#sdate'+counter).datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#edate'+counter).datepicker({ dateFormat: 'yy-mm-dd' }).val();

	
	

    });
	var element2 = document.getElementById("airSamp_div");
	var air_num = document.createElement("input");	
    air_num.setAttribute("type", "text");
    air_num.setAttribute("name", "air_samp_num");
    air_num.setAttribute("value", num);
   	sens_num.setAttribute("style", "visibility:hidden");
   	element2.appendChild(air_num);
   
});


</script>

