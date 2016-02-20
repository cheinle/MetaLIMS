<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../index.php');
include('insert_a_user_thing_js.php');

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Form Insert</title>
		<!--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>-->
	
	</head>
 
	<body>
	<div class="page-header">
	<h3>Add User UDF's to form</h3>
	
	<?php //button
		//add a select
			//tell me what you want the label to be and a comma seperated list of dropdown values
		//add a text box
			//tell me what you want the label to be
			
			
			
	?>

		<!--<div id="user_things">User's Things:<br>
		
		</div>-->
	
		<input class="button" id="submit" type="button" value="submit"><br>
		
		<script type="text/javascript">
				//load page
				$(document).ready(function() {
					//$("#submit").click(function() {

							//define variables..
							var label_text = $("#label").val();
							var type = 'text_input';
	
							 $.ajax({
			                    url     : 'build_a_thing_submit.php', //the url you are sending datas to which will again send the result
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { label_text:label_text,type: type}, //Data you are sending
			                    success : function(data){$('#user_things').html(data)},
			                    error   : function(){alert('An Error Has Occurred')} //error message

						});
						
					//});
				});
				
				////submit page 
				$(document).ready(function() {
					//$("#submit").click(function() {

							//define variables..
							var label_text = $("#label").val();
							var type = 'text_input';
	
							 $.ajax({
			                    url     : 'build_a_thing_submit.php', //the url you are sending datas to which will again send the result
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { label_text:label_text,type: type}, //Data you are sending
			                    success : function(data){$('#user_things').html(data)},
			                    error   : function(){alert('An Error Has Occurred')} //error message

						});
						
					//});
				});
		</script>
			
	</body>
	
</html>
