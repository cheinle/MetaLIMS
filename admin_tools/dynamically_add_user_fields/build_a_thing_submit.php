<?php
		include ('../database_connection.php');

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
					  var node = document.createTextNode(label_text+" : ");
					  label.appendChild(node);
					  
					  var newInput = document.createElement("input");
					  newInput.type="text";
					  newInput.name= thing_id;
						
					  var linebreak = document.createElement("br");  
					
					  var element = document.getElementById("user_things").appendChild(label);
					  document.getElementById("user_things").appendChild(linebreak);
					  document.getElementById("user_things").appendChild(newInput);
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