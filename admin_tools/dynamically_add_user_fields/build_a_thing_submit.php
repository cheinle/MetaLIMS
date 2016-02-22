<?php
		include ('../../database_connection.php');

		$stmt = $dbc->prepare("SELECT label_name,type,select_values,thing_id, visible FROM create_user_things");
		if(!$stmt){
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		if ($stmt->execute()){
			$stmt->bind_result($label_name,$type,$select_values,$thing_id,$visible);
			while ($stmt->fetch()) {
				if($type == 'text_input'){
					if($visible == 1){
?>
					<script type="text/javascript">
					  var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  var label = document.createElement("label");
					  label.className="textbox-label";
					  var node = document.createTextNode(label_text+" : ");
					  label.appendChild(node);
					  
					  var newInput = document.createElement("input");
					  //newInput.type="text";
					  //newInput.name= thing_id;
					  newInput.setAttribute("type", "text");
				      newInput.setAttribute("name", thing_id);
				      newInput.setAttribute("id", thing_id);
				      newInput.setAttribute("value", "");
				      newInput.setAttribute("class", "things");
						
					  var linebreak = document.createElement("br");  
					
					  var element = document.getElementById("user_things").appendChild(label);
					  document.getElementById("user_things").appendChild(linebreak);
					  document.getElementById("user_things").appendChild(newInput);
					  document.getElementById("user_things").appendChild(linebreak);
					</script>
<?php
					}
				}
				if($type == 'select'){
					if($visible == 1){
						$select_array = explode(';', $select_values);
?>
					<script type="text/javascript">
					  var thing_id = <?php echo(json_encode(htmlspecialchars($thing_id))); ?>	
					  var label_text = <?php echo(json_encode(htmlspecialchars($label_name))); ?>	
					  var label = document.createElement("label");
					  label.className="textbox-label";
					  var node = document.createTextNode(label_text+" : ");
					  label.appendChild(node);
					  
					  var select = document.createElement("select");
					  var array = <?php echo json_encode($select_array); ?>;
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
				    	select.setAttribute("name", thing_id);
				    	select.setAttribute("id", thing_id);
				    	select.setAttribute("class", "things");
				    	select.setAttribute("value", "");	
	
 						linebreak = document.createElement("br");  
					
					 	 var element = document.getElementById("user_things").appendChild(label);
					  	 document.getElementById("user_things").appendChild(linebreak);
					 	 document.getElementById("user_things").appendChild(select);
					 	 document.getElementById("user_things").appendChild(linebreak);
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