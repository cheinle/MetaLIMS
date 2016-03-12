		<!--transaction time-->
				<input type="text" style="visibility:hidden" class="hidden" name="transaction_time" id="transaction_time" value="<?php echo $transaction_time ?>"/>
				
				<!--see if sample is part of a pool-->
				<input type="text" style="visibility:hidden" class="hidden" name="part_of_pool" id = "part_of_pool"  value="<?php echo text_insert_update($parent_value,'part_of_pool',$dbc); ?>"/>
				
				
				<br>* = required field <br>
				+ = required for my samples (incudes fungal/bacterial isolates when applicable)<br>
				<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>
				<div id='samplename_availability_result'></div>  
				<fieldset>
				<LEGEND><b>Sample Name</b></LEGEND>
				<div class="col-xs-6">
				<p>
				<label class="textbox-label">Sample Name:*</label>
				<input type="text" name="sample_name" id="sample_name" data-toggle="popover" title="Tip:" data-content="Unable to edit sample name. Please select Go Back button to select a different sample or go to Insert Sample tab to enter a new sample. Sample Name is automatically re-created if name components are updated" placeholder="yyyy/mm/dd[project name][sample_type][sample number-000]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_name',$dbc);}?>" readonly />
				</p>
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
				</div><!--end of col-xs-6-->
				</fieldset>
				
				
				<div id="fragment-1">
				<fieldset>
				<LEGEND><b>Sample Collection Info</b></LEGEND>
				<div class="col-xs-6">	
				<p>
				<label class="textbox-label">Sample Number:*</label>
				<input type="text" name="sample_number" id="sample_number"  placeholder="[001]" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'sample_num',$dbc);}?>" />
				</p>
				
				<!--Barcode insert field-->
				<p>
				<label class="textbox-label">Barcode:(optional)</label><br>
				<input type="text" name="barcode" id="barcode" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'barcode',$dbc);}?>"/>
				</p>

				<p>
				<!--Project Name Dropdown-->
				<label class="textbox-label">Select Project Name:*</label><br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('projName', 'project_name', 'project_name','project_name','project_name',"$parent_value",$root);
				?>
				</p>
				
				<!--location dropdown-->
				<p>
				<label class="textbox-label">Select Location:*</label><br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('loc', 'location', 'loc_name','loc_name','location_name',"$parent_value",$root);
				?>
				</p>
				
				<!--relative location dropdown-->
				<p>
				<label class="textbox-label">Select Relative Location:*</label><br/>
				<?php
				//$select_name,$table_name,$field_name,value,$s_field_name,$sample_name
				dropDown_update('rloc', 'relt_location', 'loc_name','loc_name','relt_loc_name',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<!--media type dropdown-->
				<label class="textbox-label">Media Type:*</label><br/>
				<?php
				//url or $_GET name, table name, field name, select_id, s field name, sample name
				dropDown_update('media', 'media_type', 'media_type','media_type','media_type',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<!--Collector Name input-->
				<label class="textbox-label">Enter Collector Name(s):*</label>
				<p class="clone"> <input type="text" name="collector[]" id="collector" class='input' placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'collector_name',$dbc);} ?>"/></p>
				</p>
				
				<!--Sampling Type insert field-->
				<p>
				<label class="textbox-label">Sample Type:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Please See FAQ"></i></label><br>
				<?php 
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update('sType', 'sample_type', 'sample_type_name','sample_type_id','sample_type',"$parent_value",$root);
				?>
				</p>
				
				<p>
				<label class="textbox-label">Flow Rate-Start/End of Day:+<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Coriolis or SASS: 300 l/m. Spin Air: 20-100 l/m"></i></label><br>
				<input type="text" name="fRate" id="fRate"  class = "shrtfields" placeholder="Enter A Flow Rate for SOD" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'flow_rate',$dbc);} ?>">
				<input type="text" name="fRate_eod" id="fRate_eod"  class = "shrtfields" placeholder="Enter A Flow Rate for EOD" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'flow_rate_eod',$dbc);} ?>">
				</p>

				
				<p>
				<!--storage freezer-->
				<label class="textbox-label">Select Storage Location:*</label><br/>
				<?php
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update_for_storage('oStore_temp', 'freezer', 'freezer_id','freezer_id', 'original',"$parent_value",'0',$root);
				dropDown_update_for_storage('oStore_name', 'drawer', 'drawer_id','drawer_id', 'original',"$parent_value",'1',$root);
				?>
				</p>
				
				<p>
				<label class="textbox-label">Height Above Floor:+<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title=" Coriolis-113.5cm: SASS-156cm: Spin Air-151cm (all on tripods)" id='example'></i>
				</label><br>
				<input type="text" name="sampling_height" id="sampling_height" placeholder="Enter A Height Above Floor (cm)" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'sampling_height',$dbc);} ?>">
				</p>
				
				<!--Invisible Project Name Dropdown-->
				<input type="text" style="visibility:hidden" name="orig_projName" id="orig_projName" placeholder="Enter A Barcode" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'project_name',$dbc);}?>"/>
				

				</div><!--end of col-xs-6-->
				
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
					echo 'An error has occured';
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
				</fieldset>
				</div><!--end of fragment-1-->
			
				
				<!-----------------------------------------fragment 2---------------------------------------->
				<div id="fragment-2">
				<fieldset>
				<div id="dna_extraction">
					
					<LEGEND><b>DNA Extraction Info</b></LEGEND>
					<div class="col-xs-6">
					
					<p>
					<label class="textbox-label">DNA Extraction Date:</label><br>
					<input type="text" id="d_extr_date"  name="d_extr_date" value="<?php echo text_insert_update_dt($parent_value,'d_extraction_date','date');?>"/>
					<script>
					$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
					</script>
					
					<p>
					<!--DNA Extraction Kit dropdown-->
					<label class="textbox-label">Select DNA Extraction Kit:</label>
					<br/>
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name','dna_extract_kit_name',"$parent_value",$root);
					?>
					</p>
					
					<!--DNA Concentration-->
					<p>
					<label class="textbox-label">DNA Concentration (ng/ul):</label><br>
					<input type="text" name="dConc" id="dConc" placeholder="Enter A DNA Concentration. Note: (0 = ND = <0.0050ng/ul)" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_conc',$dbc);} ?>">
					</p>
					
						<!--Volume of DNA-->
					<p>
					<label class="textbox-label">Volume of DNA Elution (ul):</label><br>
					<input type="text" name="dVol" id="dVol" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume',$dbc);} ?>">
					</p>
	
	
					<!--Instrument used to measure DNA concentration-->
					<p>
					<label class="textbox-label">Instrument/Kit Used to Measure DNA Concentration:</label><br>
					<?php
					//url or $_GET name, table name, field name
					dropDown_update('dInstru', 'quant_instruments', 'kit_name','kit_name','d_conc_instrument',"$parent_value",$root);
					?>
					</p>
	
					<!--Volume of DNA to measure DNA conc-->
					<p>
					<label class="textbox-label">Volume of DNA Used for Measure DNA Concentration(ul):</label><br>
					<input type="text" name="dVol_quant" id="dVol_quant" placeholder="Enter A Volume" value="<?php if(isset($_GET['submit'])){echo text_insert_update($parent_value,'d_volume_quant',$dbc);}?>">
					</p>
					<!------------------------------------------------------------>
					<!--DNA -->
					<p>
					<label class="textbox-label">Location of DNA Extract:</label><br>
					</p>
					<?php
					//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
					dropDown_update_for_storage('dStore_temp', 'freezer', 'freezer_id','freezer_id', 'dna_extr',"$parent_value",'0',$root);
					dropDown_update_for_storage('dStore_name', 'drawer', 'drawer_id','drawer_id', 'dna_extr',"$parent_value",'1',$root);
					?>
					</p>
					
					<p>
					<!--Extractor Name input-->
					<label class="textbox-label">Enter Name of Person(s) Who Extracted DNA:</label>
					<p class="clone2"> <input type="text" name="dExtrName[]" id="dExtrName" class="input"  placeholder="Comma Seperated Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'dExtrName',$dbc);} ?>"/></p>
					<!--<p><a href="#" class="add2" rel=".clone2">Add More Names</a></p>
					</p>
					<script type="text/javascript">
					$(document).ready($(function(){
						var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
							$('a.add2').relCopy({ append: removeLink}); 
						})
					);
					</script>-->
					
					<p>
					<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3><br>
	 				<div class="vert-checkboxes">
	 				<label class="checkbox-label"><input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" class = "orig_sample_exist" value="false" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>No</label><br />
					</div>
					</p>
					
					<p>
					<h3 class="checkbox-header">Does DNA Extraction Sample Exist?:</h3><br>
					<div class="vert-checkboxes">
	 				<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>Yes,DNA Sample Exisits</label><br />
					<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?>/>No, DNA Has Not Been Extracted</label><br />
					<label class="checkbox-label"><input type="radio" name="DNA_sample_exist" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'DNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?>/>No, DNA Sample Is Used Up</label><br />
					</div>
					</p>
					</div>
				</div>

				<div class="col-xs-6">
				<div id="rna_extraction">
				<LEGEND><b>RNA Extraction Info</b></LEGEND>
				
				<p>
				<label class="textbox-label">RNA Extraction Date:</label><br>
				<input type="text" id="r_extr_date"  name="r_extr_date" value="<?php echo text_insert_update_dt($parent_value,'r_extraction_date','date');?>"/>
				<script>
				$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				</script>
				
				<p>
				<!--RNA Extraction dropdown-->
				<label class="textbox-label">Select RNA Extraction Kit:</label>
				<br/>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name','rna_extract_kit_name',"$parent_value",$root);
				?>
				</p>
				
				<!--RNA Concentration-->		
				<p>
				<label class="textbox-label">RNA Concentration (ng/ul):</label><br>
				<input type="text" name="rConc" id="rConc"  placeholder="Enter an RNA Concentration" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_conc',$dbc);} ?>">
				</p>
				
				<!--RNA Volume-->
				<p>
				<label class="textbox-label">Volume of RNA Elution (ul):</label><br>
				<input type="text" name="rVol" id="rVol" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume',$dbc);} ?>">
				</p>
		
				<!--Instrument used to measure RNA concentration-->
				<p>
				<label class="textbox-label">Instrument/Kit Used to Measure RNA Concentration:</label><br>
				<?php
				//url or $_GET name, table name, field name
				dropDown_update('rInstru', 'quant_instruments', 'kit_name','kit_name','r_conc_instrument',"$parent_value",$root);
				?>
				</p>
				
				<!--RNA Volume-->
				<p>
				<label class="textbox-label">Volume of RNA for Quantification(ul):</label><br>
				<input type="text" name="rVol_quant" id="rVol_quant" placeholder="Enter A Volume" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'r_volume_quant',$dbc);} ?>">
				</p>
				
				<p>
				<label class="textbox-label">Location of RNA Extract:</label><br>
				</p>
				<?php
				//$select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name
				dropDown_update_for_storage('rStore_temp', 'freezer', 'freezer_id','freezer_id', 'rna_extr',"$parent_value",'0',$root);
				dropDown_update_for_storage('rStore_name', 'drawer', 'drawer_id','drawer_id', 'rna_extr',"$parent_value",'1',$root);
				?>
				
				<p>
				<!--Extractor Name input-->
				<label class="textbox-label">Enter Name of Person(s) Who Extracted RNA:</label>
				<p class="clone3"> <input type="text" name="rExtrName[]" id="rExtrName" class="input" placeholder="Comma Sepearted Names" value="<?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'rExtrName',$dbc);} ?>"/></p>
				<!--<p><a href="#" class="add3" rel=".clone3">Add More Names</a></p>
				</p>
				
				<script type="text/javascript">
				$(document).ready($(function(){
					var removeLink = ' <a class="remove" href="#" onclick="$(this).parent().slideUp(function(){ $(this).remove() }); return false">remove</a>';
						$('a.add3').relCopy({ append: removeLink}); 
					})
				);
				</script>-->
				
				<p>
				<h3 class="checkbox-header">Does Original RNA Sample Still Exist?:</h3>
				<div class="vert-checkboxes">
 				<label class="checkbox-label"><input type="checkbox" class = "orig_sample_exist" <?php $check_exists = text_insert_update_stinfo($parent_value, 'orig_sample_exists','storage_info',$root); if($check_exists == 'false'){ echo 'checked';} ?>/>No</label><br />
				</div>
				</p>
				
				<p>
				<h3 class="checkbox-header">Does RNA Extraction Sample Exist?:</h3><br>
 				<div class="vert-checkboxes">
 				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="one" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'one'){ echo 'checked';}  ?>/>Yes,RNA Sample Exisits</label><br />
				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="two" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'two'){ echo 'checked';}  ?>/>No, RNA Has Not Been Extracted</label><br />
				<label class="checkbox-label"><input type="radio" name="RNA_sample_exist" value="three" <?php $check_exists = text_insert_update_stinfo($parent_value, 'RNA_sample_exists','storage_info',$root); if($check_exists == 'three'){ echo 'checked';} ?>/>No, RNA Sample Is Used Up</label><br />
				</div>
				</p>
				</div>
			
				
				
				</fieldset>
				</div><!-- end of fragment 2-->
				
				<!-----------------------------------------fragment 3----------------------------------------->
				<div id="fragment-3">
					<fieldset>
						<LEGEND><b>Analysis</b></LEGEND>
						<p><a href="/series/dynamic/mymicrobiomes/update_tables/update_seq_info.php">Fill Out Sequencing Submission Info</a></p>
						<p>
						<!--Sequencing2 Dropdown-->
						<label class="textbox-label">Select Analysis Pipeline:</label>
						<br/>
						<?php
						//url or $_GET name, table name, field name
						dropDown_update('anPipe', 'analysis', 'analysis_name','analysis_name','analysis_name',$parent_value,$root);
						?>
						</p>
					</fieldset>
				</div><!--end fragment-3-->
				
				
				<!-----------------------------------------fragment 4----------------------------------------->
				<div id="fragment-4">
					<fieldset>
					<LEGEND><b>User Created Fields</b></LEGEND>
						<div class="col-xs-6">
							<input type="text" style="visibility:hidden" class="hidden" name="build_type" id="build_type" value="update"/>
							<input type="text" style="visibility:hidden" class="hidden" name="parent_value" id="parent_value" value="<?php echo $parent_value;?>"/>
							<div id="required_things">
							
							</div>
							<div id="user_things">
								
							</div>
						</div>
					</fieldset>
				</div><!--end fragment-4-->

				
				<!-----------------------------------------fragment 5------------------------------------------>
				<div id="fragment-5">
					<fieldset>
					<LEGEND><b>Notes</b></LEGEND>
						<div class="col-md-12">
							<p>
							<label class="textbox-label">Sample Notes:(optional)</label>
							<textarea class="form-control" from="sample_form_update" rows="3" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if (isset($_GET['submit'])){echo text_insert_update($parent_value,'notes',$dbc);} ?></textarea>
							</p>
						</div><!--close col-md-12-->
					</fieldset>
				</div> <!--close fragement-5-->
			</div><!--tabs-->
				
				
				
			<p>
			<button class="button" type="submit" name="submit" value="2">Update </button>
			<button class="button" type=button onClick="parent.location='<?php echo $root;?>sample_update/sample_update_lookup_jquery.php'" value='Go Back'>Go Back</button>
			</p>
				
		</form>
		<script>
		$( "#tabs" ).tabs();
		</script>
		<script type="text/javascript">
			   var name_check = 'true';
			   function validate(from) {
			   		var valid = 'true';
				    if(check_form() == 'false'){
				    	valid = 'false';
				    }
				    if(check_form_required() == 'false'){
				    	valid = 'false';
				    }
				    if(check_required_user_things() == 'false'){
				    	valid = 'false';
				    }		
				    check_sample_name_update();
				    if(name_check == 'false'){
				    	alert('Sample Name Not Valid. Please Check Project Name And Sample Number');
				    	valid = 'false';
				    }
				   

				    if(valid === 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Sure You Want To Submit?');
				    }
				}
				
				function check_form(){
					var index;
					var valid = 'true';
					//var x = document.getElementById('my_samp_num').value;
					//var x = 1;//must have a way to check if my sampler...and a way to not delete all
					
					//var mySamp_class = document.getElementsByClassName('mySamp');//should tell you how many my samplers are on the screen
					//var x = mySamp_class.length;
					var x = document.getElementById("my_samp_num").value;

					if(x == 0){//this should never happen...
						valid = 'false';
						alert('ERROR!! There Are No Samplers. Please Add Some');
					}
					else{
						//create a contains method to check if mySamp is entered twice
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						var seen = [];
						//validate mySamp data
						for (index = 1; index <= x; ++index) {
	   	 					var mySamp_name = 'mySamp'+index;
	   	 					//check that mySamp is picked 
	   	 					var mySamp_name_value = document.getElementById(mySamp_name).value;
	   	 					if(mySamp_name_value == '0' || mySamp_name_value == 'Needs to be added'){
	   	 						alert("Whoops! Sensor Name Is Not Valid");
	   	 						document.getElementById(mySamp_name).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						//check to see if mySamp name is already input
	   	 						if(seen.contains(mySamp_name_value)){
	   	 							document.getElementById(mySamp_name).style.backgroundColor = 'blue';
	   	 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
	   	 							valid = 'false';
	   	 						}
	   	 					    else{
	   	 							seen.push(mySamp_name_value);
	   	 							document.getElementById(mySamp_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}
	   	 				
	   	 					//check start and end date/times are entered and make sense
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						alert("Whoops! Please Enter A Start Time");
	   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;
	   	 					if(end_time_value == ''){
	   	 						alert("Whoops! Please Enter An End Time");
	   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					
	   	 					var start_date = 'sdate'+index;
	   	 					var start_date_value = document.getElementById(start_date).value;
	   	 					if(start_date_value == ''){
	   	 						alert("Whoops! Please Enter An Starting Date");
	   	 						document.getElementById(start_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					var end_date = 'edate'+index;
	   	 					
	   	 					var end_date_value = document.getElementById(end_date).value;
	   	 					if(end_date_value.length == '0'){
	   	 						alert("Whoops! Please Enter An End Date");
	   	 						document.getElementById(end_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 				
							
							if(start_time_value != '' && start_date_value != '' && end_time_value != '' && end_date_value != ''){
								
								//first check if date time values make sense
								var p_start = start_date_value+' '+start_time_value;
								var p_end = end_date_value+' '+end_time_value;

								if((p_start) && (p_end)){
									
									var ts1 = Date.parse(p_start);
									var ts2 = Date.parse(p_end);
									var seconds_diff = ts2 - ts1;
									var time = (seconds_diff/3600);
									time = (time/1000); 

									var p_time = time.toFixed(2);
									var mySamp_check = mySamp_name.match(/^Coriolis.*/);

									if(p_time < 0){
										valid = 'false';
										alert("Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else if(p_time > 6.5 && mySamp_check  != null){//check if coriolis sampling is greater than 6 hours
										valid = 'false';
										alert("Sampling Is Greater Than 6 Hours For Coriolis Sampling. Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else{
										document.getElementById(start_time).style.backgroundColor = 'white';
										document.getElementById(end_time).style.backgroundColor = 'white';
										document.getElementById(start_date).style.backgroundColor = 'white';
										document.getElementById(end_date).style.backgroundColor = 'white';
									}
								}
							}
						}
					}	
					return valid;
					
				}
			</script>
	
	<?php }?>
	
	

</body>
</html>
