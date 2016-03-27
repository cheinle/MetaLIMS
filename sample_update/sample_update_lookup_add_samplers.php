				<div class="col-xs-6">
				<p>
				<!--my sampler dropdown-->
				<label class="textbox-label">Select Sampler:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="At this time, sampler types cannot be updated via the dropdown function. Sampler entries must be deleted using the delete check box and then re-entered. Also, please note that all blanks and cfu samples will have a Sampling duration of zero"></i></label><br/>
				<!-------------------------------------------------------------------------------------------->
				<!-----------------------------new my sampler info------------------------------------------->
				<?php 
				
				$p_sample_name = text_insert_update($parent_value,'sample_name',$dbc);
				//$parent_value = $p_sample_name;


				//grab list of sensors to choose from
				$query = "SELECT sampler_name FROM sampler";
				$result = mysqli_query($dbc, $query);
				if(!$result){
					$error = 'true';
					echo 'An error has occurred';
					mysqli_error($dbc);
				}
				while($row = mysqli_fetch_assoc($result)){
					$array[] = htmlspecialchars($row['sampler_name']);
				}
			
				//grab all of the my sampler info for this sample
				$stmt1 = $dbc->prepare("SELECT sampler_name,start_date_time,end_date_time,total_date_time FROM sample_sampler WHERE sample_name = ?");
				$stmt1 -> bind_param('s', $p_sample_name);
	  			if ($stmt1->execute()){
	    			$stmt1->bind_result($my_sampler_name,$start,$end,$total);
					$counter = 0;
				
							echo '<div  id = "mySamp_div" name = "mySamp_div">';
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
								echo "<label class='textbox-label-sampler'>Sampler ".$x.":*</label>";   
								echo "<select id='mySamp".$x."' name='mySamp".$x."' class='mySamp'>";
								#echo "<option value='0'>-Select-</option>";
								foreach ($array as $key => $value) {
									$name = htmlspecialchars($value);
									$id = htmlspecialchars($value);
									if($id == $my_sampler_name){
										echo '<option selected="selected" value="'.$id.'">'.$name.'</option>';
									}
									else{
										#echo '<option value="'.$id.'">'.$name.'</option>';
									}
								}
								echo '</select>';
								?>
			
				   	 			
				   	 			<label class="textbox-label-sampler">Start Date/Time <?php echo $x ?>:*</label>
								<input type="text" id="sdate<?php echo $x ?>"  class = "shrtfields" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php echo $start_date; ?>"/>
								<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields"  placeholder="Time"  value="<?php echo $start_time?>"/>
								
								<label class="textbox-label-sampler">End Date/Time<?php echo $x ?>:*</label>
								<input type="text" id="edate<?php echo $x ?>" class = "shrtfields" placeholder = "Date" name="edate<?php echo $x ?>" value="<?php echo $end_date; ?>"/>
								<input type="text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields"  placeholder="Time" value="<?php echo $end_time;?>"/>
								
								<script type="text/javascript">
								$('#sdate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
								$('#edate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				
								
				    			$(document).ready(function(){
				    				var my_samp_num = <?php echo(json_encode($x)); ?>;
				        			$('input[name="stime'+my_samp_num+'"]').ptTimeSelect();
				        			timeFormat: "HH:mm"
				   	 			
				   	 				$('input[name="etime'+my_samp_num+'"]').ptTimeSelect();
				        			timeFormat: "HH:mm"
				   	 			});
							</script>
							
							<h3 class="checkbox-header-sampler">Delete Sampler <?php echo $x ?></h3>
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
					
			?>
					<input type="button" id="more_my_samplers" class="button" style="float:left;margin-left: 40%;margin-bottom: 30px" name ="more_my_samplers" value='Add More Samplers' /><br>
					<!--<div id="div1"></div>-->
					

					<!--</fieldset>-->
					<!--add more sensor info!-->
						<script type="text/javascript">
						var counter = <?php echo json_encode($counter); ?>;
						var num = counter;
						$(document).ready(function() {
					
					
				      $('#more_my_samplers').click(function(event) {  //on click, append to correct place, perhaps after and in the first field set
				    	//var counter = '1';
				    	
				    	counter++;
				    	num++;
				    	
				    	if(counter <= 6){
				       //create new elements
				        var ul_element = document.createElement("ul");
						var start_label = document.createElement("label");
						//start_label.setAttribute("style", "color: pink;");
						start_label.className="textbox-label-sampler";
						var end_label = document.createElement("label");
						//end_label.setAttribute("style", "color: pink;");
						end_label.className="textbox-label-sampler";
						var mySamp_label = document.createElement("label");
						//mySamp_label.setAttribute("style", "color: pink;");
						mySamp_label.className="textbox-label-sampler";
						var checkbox_label = document.createElement("label");
						//checkbox_label.setAttribute("style", "color: pink;");
						checkbox_label.className="checkbox-label";
						var h3 = document.createElement("h3");
						//h3.setAttribute("style", "color: pink;");
						h3.className="checkbox-header-sampler";
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
										
						var node2 = document.createTextNode("Sampler " + counter + ":*");
						mySamp_label.appendChild(node2);
						
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
				    	select.setAttribute("name", "mySamp"+ counter);
				    	select.setAttribute("id", "mySamp"+ counter);
				    	select.setAttribute("value", "");
				    	
				    	checkbox.setAttribute("type", "checkbox");
				    	checkbox.setAttribute("name", "delete"+ counter);
				    	checkbox.setAttribute("id", "delete"+ counter);
				    	checkbox.setAttribute("value", "DELETE");
										
						//append the elements to where you want them in the DOM
						var element = document.getElementById("mySamp_div");
						
							
						/*you are trying to format your text boxes correctly using these
						 * 
						 */ 
						element.appendChild(ul_element);
						ul_element.appendChild(mySamp_label);
						ul_element.appendChild(select);
						
						ul_element.appendChild(start_label);
						ul_element.appendChild(linebreak3);
						ul_element.appendChild(input3);
						ul_element.appendChild(input1);
						ul_element.appendChild(linebreak4);
						
						ul_element.appendChild(end_label);
						ul_element.appendChild(linebreak5);
						ul_element.appendChild(input4);
						ul_element.appendChild(input2);
						ul_element.appendChild(linebreak6);
						
						ul_element.appendChild(h3);
						ul_element.appendChild(div);
							
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
	
					
						//if you added my samplers, change the number of my samplers
						var current_number_my_samplers = document.getElementById("my_samp_num");
						var current_number_my_samplers_value = current_number_my_samplers.value;
						current_number_my_samplers_value++;
						//alert(current_number_my_samplers_value);
						current_number_my_samplers.value = current_number_my_samplers_value;
						}
				    });
				    
				    //Check how many samplers you have on the page without adding any
					var element2 = document.getElementById("mySamp_div");
					var my_num = document.createElement("input");	
				    my_num.setAttribute("type", "text");
				    my_num.setAttribute("name", "my_samp_num");
				    my_num.setAttribute("id", "my_samp_num");
				    my_num.setAttribute("value", num);
				   	my_num.setAttribute("style", "visibility:hidden");
				   	element2.appendChild(my_num);
	
				});
				
				
				</script>
				</div><!--end of col-xs-6-->
