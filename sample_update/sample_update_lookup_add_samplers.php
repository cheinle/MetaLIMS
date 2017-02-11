				<div class="col-xs-12 col-sm-6 col-md-6">

				<!--my sampler dropdown-->
				<label>Select Sampler:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="At this time, sampler types cannot be updated via the dropdown function. Sampler entries must be deleted using the delete check box and then re-entered. Also, please note that all blanks and cfu samples will have a Sampling duration of zero"></i></label>
				<?php 
				
				$p_sample_name = text_insert_update($parent_value,'sample_name',$dbc);


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
								echo "<div class=\"form-group\">";
								echo "<label class='col-md-3 control-label'>Sampler ".$x.":*</label>";  
								echo "<div class='col-md-8'>"; 
								echo "<select id='mySamp".$x."' name='mySamp".$x."' class='mySamp form-control'>";
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
								echo '</div>';
								echo '</div>';
								?>
			
				   	 			
				   	 			<div class="form-group">
									<label class="col-md-3 control-label">Start Date/Time <?php echo $x ?>:*</label>
										<div class="col-md-4">
											<input type="text" id="sdate<?php echo $x ?>"  class = "form-control input-md" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php echo $start_date; ?>"/>
										</div>
										<div class="col-md-4">
											<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="form-control input-md"  placeholder="Time"  value="<?php echo $start_time?>"/>
										</div>
								</div>
									
								<div class="form-group">
									<label class="col-md-3 control-label">End Date/Time<?php echo $x ?>:*</label>
										<div class="col-md-4">
											<input type="text" id="edate<?php echo $x ?>" class = "form-control input-md" placeholder = "Date" name="edate<?php echo $x ?>" value="<?php echo $end_date; ?>"/>
										</div>
										<div class="col-md-4">
											<input type="text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="form-control input-md"  placeholder="Time" value="<?php echo $end_time;?>"/>
										</div>
								</div>
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
							

							<div class="form-group">
							  <label class="col-md-4 control-label" for="checkboxes">Delete?</label>
							  <div class="col-md-4">
							  <div class="checkbox">
							    <label for="checkboxes-0">
							      <input type="checkbox" name='delete<?php echo $x ?>' id='delete<?php echo $x ?>' value='DELETE'>
							      Delete
							    </label>
								</div>
							  </div>
							</div>
							
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
				       //append the elements to where you want them in the DOM
					   var element = document.getElementById("mySamp_div"); //where append to
				       var ul_element = document.createElement("ul"); //append in a list
				       var input1 = document.createElement("input");
					   var input2 = document.createElement("input");
					   var input3 = document.createElement("input");
					   var input4 = document.createElement("input");
					   var select = document.createElement("select");
					   var checkbox = document.createElement("input");
					   
					   var form_group_div= document.createElement("div");
					   form_group_div.className="form-group";
					   
					   var form_group_div2= document.createElement("div");
					   form_group_div2.className="form-group";
					   
					   var form_group_div3= document.createElement("div");
					   form_group_div3.className="form-group";
				         
				       var form_group_div4= document.createElement("div");
					   form_group_div4.className="form-group";
				         

				       var div= document.createElement("div");
					   div.className="col-md-8";
					   
					   
					   var small_div= document.createElement("div");
					   small_div.className="col-md-4";
					   
					   var small_div2= document.createElement("div");
					   small_div2.className="col-md-4";
					   
					   var small_div3= document.createElement("div");
					   small_div3.className="col-md-4";
					   
					   var small_div4= document.createElement("div");
					   small_div4.className="col-md-4";
					   
					   var small_div5= document.createElement("div");
					   small_div5.className="col-md-4";
				         
				         
				       //create sampler dropdown 
				       var mySamp_label = document.createElement("label");
					   mySamp_label.className="col-md-3 control-label";
					   
				       var node2 = document.createTextNode("Sampler " + counter + ":*");
					   mySamp_label.appendChild(node2);
				       
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
				      
				        select.setAttribute("class", "form-control");
				    	select.setAttribute("name", "mySamp"+ counter);
				    	select.setAttribute("id", "mySamp"+ counter);
				    	select.setAttribute("value", "");
				      
				      
				      	element.appendChild(ul_element);
						ul_element.appendChild(form_group_div);
						form_group_div.appendChild(mySamp_label);
						form_group_div.appendChild(div);
						div.appendChild(select);
						
						
						//create start date/time
						var start_label = document.createElement("label");
						start_label.className="col-md-3 control-label";
						
						var node = document.createTextNode("Start Date/Time:"+ counter + ":*");
						start_label.appendChild(node);
										
						input3.setAttribute("type", "text");
				    	input3.setAttribute("name", "sdate"+ counter);
				    	input3.setAttribute("id", "sdate"+ counter);
				    	input3.setAttribute("value", "");
				    	input3.setAttribute("class", "form-control input-md");
						
						input1.setAttribute("type", "text");
				    	input1.setAttribute("name", "stime"+ counter);
				    	input1.setAttribute("id", "stime"+ counter);
				    	input1.setAttribute("value", "");
				    	input1.setAttribute("class", "form-control input-md");
				    	
				    	
				    	element.appendChild(ul_element);
						ul_element.appendChild(form_group_div2);
				    	form_group_div2.appendChild(start_label);
				    	small_div.appendChild(input3);
				    	form_group_div2.appendChild(small_div);
				    	small_div2.appendChild(input1);
				    	form_group_div2.appendChild(small_div2);
				    	
				   
						
						//create end date/time	
				      	var end_label = document.createElement("label");
						end_label.className="col-md-3 control-label";
				      
				      	var node5 = document.createTextNode("End Date/Time:"+ counter + ":*");
						end_label.appendChild(node5);
				    	
				    	input4.setAttribute("type", "text");
				    	input4.setAttribute("name", "edate"+ counter);
				    	input4.setAttribute("id", "edate"+ counter);
				    	input4.setAttribute("value", "");
				    	input4.setAttribute("class", "form-control input-md");
				    	
				    	input2.setAttribute("type", "text");
				    	input2.setAttribute("name", "etime"+ counter);
				    	input2.setAttribute("id", "etime"+ counter);
				    	input2.setAttribute("value", "");
				    	input2.setAttribute("class", "form-control input-md");
				    					
				    	element.appendChild(ul_element);
						ul_element.appendChild(form_group_div3);
				    	form_group_div3.appendChild(end_label);
				    	small_div3.appendChild(input4);
				    	form_group_div3.appendChild(small_div3);
				    	small_div4.appendChild(input2);
				    	form_group_div3.appendChild(small_div4);
				    	
						
						//create checkbox
						var checkbox_label = document.createElement("label");
						checkbox_label.className="col-md-4 control-label";
						
						var node3 = document.createTextNode("DELETE?");
						checkbox_label.appendChild(node3);
						
						var inner_checkbox_label = document.createElement("label");
						
						var checkbox_div = document.createElement("div");
						checkbox_div.className="checkbox";
						
						
						checkbox.setAttribute("type", "checkbox");
				    	checkbox.setAttribute("name", "delete"+ counter);
				    	checkbox.setAttribute("id", "delete"+ counter);
				    	checkbox.setAttribute("value", "DELETE");
				
									
									
						element.appendChild(ul_element);
						ul_element.appendChild(form_group_div4);
				    	form_group_div4.appendChild(checkbox_label);
				    	form_group_div4.appendChild(small_div5);
				    	small_div5.appendChild(checkbox_div);
				    	checkbox_div.appendChild(checkbox);
				    	
				    	
						
						//attach jquery date time plugin
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
