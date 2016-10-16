<script type="text/javascript">

				$(document).ready(function() {
					$("#submit").click(function() {
							
							//define variables..
							var label_text = document.getElementById('label').value;
							var type = document.getElementById('field_type').value;
							var options = document.getElementById('options').value;
							var required = document.getElementById('required').value;
							
							var valid = 'true';	
							if(label_text == ''){
									valid = 'false';
									document.getElementById('label').style.backgroundColor = 'blue';
							}else{
								document.getElementById('label').style.backgroundColor = 'white';
							}
							if(type == '0'){
								valid = 'false';
								document.getElementById('field_type').style.backgroundColor = 'blue';
							}
							else if(type == 'text_select' && options == ''){
								valid = 'false';
								document.getElementById('options').style.backgroundColor = 'blue';
								document.getElementById('field_type').style.backgroundColor = 'blue';
							}else{
								document.getElementById('options').style.backgroundColor = 'white';
								document.getElementById('field_type').style.backgroundColor = 'white';
							}
						
							if(required == '0'){
								valid = 'false';
								document.getElementById('required').style.backgroundColor = 'blue';
							}else{
								document.getElementById('required').style.backgroundColor = 'white';
							}
							
							
							
							if(valid == 'true'){
								 $.ajax({
				                    url     : 'add_a_user_thing_submit.php', //the url you are sending datas to which will again send the result
				                    type    : 'GET', //type of request, GET or POST
				                    data    : { label_text:label_text,type: type, options: options, required: required}, //Data you are sending
				                    success : function(data){alert(data)}, // On success, it will populate the 2nd select
				                    error   : function(){alert('ERROR: Check if possibly label name exists')} //error message
	
								});
							}else{
								alert("ERROR: Please Fill Out Required Fields");
							}
						
					});
				});
</script>