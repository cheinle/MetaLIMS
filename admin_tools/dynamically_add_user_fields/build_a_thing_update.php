<?php
		include ('../../database_connection.php');
		include('../../functions/text_insert_update_things.php');
		
		$parent_value = $_GET['parent_value'];

		$stmt = $dbc->prepare("SELECT label_name,type,select_values,thing_id, visible, required FROM create_user_things");
		if(!$stmt){
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		if ($stmt->execute()){
			$stmt->bind_result($label_name,$type,$select_values,$thing_id,$visible,$required);
			$counter = 0;
			$column_number = 1;
			while ($stmt->fetch()) {
				if($type == 'text_input' || $type == 'numeric_input'){
					if($visible == 1){
						$counter++;	
						if($counter > 10){
							$column_number = 2;	
						}
						$value = text_insert_update_things($parent_value,$thing_id);
						
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
					  	//newInput.type="text";
					  	//newInput.name= thing_id;
					  	newInput.setAttribute("type", "text");
				      	newInput.setAttribute("name", thing_id);
				      	newInput.setAttribute("id", thing_id);
				      	var value = <?php echo(json_encode(htmlspecialchars($value))); ?>	
				      	newInput.setAttribute("value", value);
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
						//	dropDown_update('anPipe', 'analysis', 'analysis_name','analysis_name','analysis_name',$parent_value,$root);
						$value = text_insert_update_things($parent_value,$thing_id);
						$select_array = explode(';', $select_values);
?>
					<script type="text/javascript">
					  var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  var label = document.createElement("label");
					  var linebreak = document.createElement("br"); 
					  label.className="textbox-label";
					  
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