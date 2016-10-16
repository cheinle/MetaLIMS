<?php
		include ('../../database_connection.php');
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
?>
					
					<script type="text/javascript">
						var column_number = <?php echo(json_encode(htmlspecialchars($column_number))); ?>	
					
						var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  	var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  	var type = <?php echo(json_encode(htmlspecialchars($type))); ?>	
					  	var label = document.createElement("label");
					  	var linebreak = document.createElement("br"); 
					  	label.className="textbox-label";
					  	
					  
					  	var newInput = document.createElement("input");
					  	newInput.setAttribute("type", "text");
				      	newInput.setAttribute("name", thing_id);
				      	newInput.setAttribute("id", thing_id);
				      	newInput.setAttribute("value", "");
				     	//newInput.setAttribute("class", type);
				     	newInput.setAttribute("class", 'things');
					  	var required = <?php echo(json_encode(htmlspecialchars($required))); ?>	
						 
						if(required == 'Y'){
						  var node = document.createTextNode(label_text+" :*");
					  	  label.appendChild(node);
						  var required_element = document.getElementById("required_things"+column_number).appendChild(label);
						  document.getElementById("required_things"+column_number).appendChild(linebreak);
						  document.getElementById("required_things"+column_number).appendChild(newInput);
						  document.getElementById("required_things"+column_number).appendChild(linebreak);
						}else{
						  var node = document.createTextNode(label_text+" : ");
					  	 label.appendChild(node);
						  var element = document.getElementById("user_things"+column_number).appendChild(label);
						  document.getElementById("user_things"+column_number).appendChild(linebreak);
						  document.getElementById("user_things"+column_number).appendChild(newInput);
						  document.getElementById("user_things"+column_number).appendChild(linebreak);
						}
					</script>
<?php
					}
				}
				if($type == 'select'){
					if($visible == 1){
						$select_array = explode(';', $select_values);
?>
					<script type="text/javascript">
					  var column_number = <?php echo(json_encode(htmlspecialchars($column_number))); ?>	
					  var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  var label = document.createElement("label");
					  var linebreak = document.createElement("br"); 
					  label.className="textbox-label";
					  
					  
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
							}
							select.appendChild(opt);
						}	
				    	select.setAttribute("name", thing_id);
				    	select.setAttribute("id", thing_id);
				    	select.setAttribute("class", "things");
				    	select.setAttribute("value", "");	
						var required = <?php echo(json_encode(htmlspecialchars($required))); ?>	
						if(required == 'Y'){
							var node = document.createTextNode(label_text+" :*");
					  		label.appendChild(node);
							var element = document.getElementById("required_things"+column_number).appendChild(label);
					  	 	document.getElementById("required_things"+column_number).appendChild(linebreak);
					 	 	document.getElementById("required_things"+column_number).appendChild(select);
					 	 	document.getElementById("required_things"+column_number).appendChild(linebreak);
					 	 	
						}else{
							var node = document.createTextNode(label_text+" : ");
					  		label.appendChild(node);
							var element = document.getElementById("user_things"+column_number).appendChild(label);
					  	 	document.getElementById("user_things"+column_number).appendChild(linebreak);
					 	 	document.getElementById("user_things"+column_number).appendChild(select);
					 	 	document.getElementById("user_things"+column_number).appendChild(linebreak);
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