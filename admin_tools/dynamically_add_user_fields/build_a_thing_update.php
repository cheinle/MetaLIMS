<?php
		include ('../../database_connection.php');
		include('../../functions/text_insert_update_things.php');
		
		
		//Find how many fields there will be so can split evenly between 2 columns
		$stmt = $dbc->prepare("SELECT count(thing_id) FROM create_user_things WHERE visible = ?");
		if(!$stmt){
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		$visible_flag = 1;
		$total_things = 0;
		$stmt->bind_param('i',$visible_flag);
		if ($stmt->execute()){
			$stmt->bind_result($number_of_things);
			while ($stmt->fetch()) {
				$total_things = $number_of_things;
			}
		}
		$half_of_things = $total_things/2;
		
		//Build form of user things
		$parent_value = $_GET['parent_value'];

		$stmt = $dbc->prepare("SELECT label_name,type,select_values,thing_id, visible, required FROM create_user_things ORDER BY LENGTH(label_name),label_name");
		if(!$stmt){
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		if ($stmt->execute()){
			$stmt->bind_result($label_name,$type,$select_values,$thing_id_number,$visible,$required);
			$counter = 0;
			$column_number = 1;
			while ($stmt->fetch()) {
				$counter++;	
				if($counter > $half_of_things){
					$column_number = 2;	
				}
				
				$thing_id = 'thing'.$thing_id_number; //changed from storing as 'thing1' to '1'
				
				if($type == 'text_input' || $type == 'numeric_input'){
					if($visible == 1){
						$value = text_insert_update_things($parent_value,$thing_id_number);
						
?>
					<script type="text/javascript">
						var column_number = <?php echo(json_encode(htmlspecialchars($column_number))); ?>	
						var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  	var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  	var type = <?php echo(json_encode(htmlspecialchars($type))); ?>	
					  	
					  	var label = document.createElement("label");
					  	label.className="col-md-3 control-label";
					  	
					  	var div = document.createElement("div"); 
					  	div.className="col-md-8";
					 
					  	var form_group_div= document.createElement("div");
					   	form_group_div.className="form-group";
					  
					  	var newInput = document.createElement("input");
					  	newInput.setAttribute("type", "text");
				      	newInput.setAttribute("name", thing_id);
				      	newInput.setAttribute("id", thing_id);
				      	
				      	var value = <?php echo(json_encode(htmlspecialchars($value))); ?>	
				      	newInput.setAttribute("value", value);
				     	newInput.setAttribute("class", type+" form-control input-md");

					  	var required = <?php echo(json_encode(htmlspecialchars($required))); ?>	
						 
						if(required == 'Y'){
						  var node = document.createTextNode(label_text+" :*");
					  	  label.appendChild(node);

						  var required_element = document.getElementById("required_things"+column_number);
						  required_element.appendChild(form_group_div);
						  form_group_div.appendChild(label);
						  form_group_div.appendChild(div);
						  div.appendChild(newInput);

						}else{
						  var node = document.createTextNode(label_text+" : ");
					  	  label.appendChild(node);
					  	  
					  	  var non_required_element = document.getElementById("user_things"+column_number);
						  non_required_element.appendChild(form_group_div);
						  form_group_div.appendChild(label);
						  form_group_div.appendChild(div);
						  div.appendChild(newInput);
						}
					</script>
<?php
					}
				}
				if($type == 'select'){
					if($visible == 1){
						//	dropDown_update('anPipe', 'analysis', 'analysis_name','analysis_name','analysis_name',$parent_value,$root);
						$value = text_insert_update_things($parent_value,$thing_id_number);
						$select_array = explode(';', $select_values);
?>
					<script type="text/javascript">
					  var column_number = <?php echo(json_encode(htmlspecialchars($column_number))); ?>	
					  var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  var label = document.createElement("label");
					  label.className="col-md-3 control-label";
					  
					  var div = document.createElement("div"); 
					  div.className="col-md-8";
					  
					  var form_group_div= document.createElement("div");
					  form_group_div.className="form-group";
					  
					  var selected_value = <?php echo(json_encode(htmlspecialchars($value))); ?>;
					  var select = document.createElement("select");
					  var array = <?php echo json_encode($select_array); ?>;
					  array.unshift("-Select-");
						for (index = 0; index < array.length; ++index) {
					   		var option = array[index];
					   		//alert(option);
							var opt = document.createElement('option');
							opt.appendChild(document.createTextNode(option));
							if(option == '-Select-'){
								opt.value = '';
							}
							else{
								opt.value = option;
							
				    			if(opt.value == selected_value){
				    				opt.setAttribute("selected", "selected");
				    			}
							}
							select.appendChild(opt);
						}	
				    	select.setAttribute("name", thing_id);
				    	select.setAttribute("id", thing_id);
				    	//select.setAttribute("class", "things");
				    	select.setAttribute("class", "select form-control");
				    	select.setAttribute("value", "");
				    	
				    		
						var required = <?php echo(json_encode(htmlspecialchars($required))); ?>	
						if(required == 'Y'){
							var node = document.createTextNode(label_text+" :*");
					  		label.appendChild(node);
					  		
					  		var required_select = document.getElementById("required_things"+column_number);
							required_select.appendChild(form_group_div);
							form_group_div.appendChild(label);
							form_group_div.appendChild(div);
							div.appendChild(select);
					  		
					 	 	
						}else{
							var node = document.createTextNode(label_text+" : ");
					  		label.appendChild(node);
					  		
					  		var non_required_select = document.getElementById("user_things"+column_number);
							non_required_select.appendChild(form_group_div);
							form_group_div.appendChild(label);
							form_group_div.appendChild(div);
							div.appendChild(select);
						}
					 	
					</script>
<?php
					}
				}	
			
			}
		}
		else{
			$error_check = 'true';
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
				
?>