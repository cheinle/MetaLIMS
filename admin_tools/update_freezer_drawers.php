<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Alter Freezers/Drawers</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>
<body>
<?php include('../index.php'); ?>
<?php include('../functions/dropDown.php'); ?>
<div class="page-header">
<h3>Add Freezers/Drawers</h3>	
</div>
<script type="text/javascript">
			$(document).ready(function(){  
				               
                $('#update_type').change(function(){ //on change event
                var type = $('#update_type').val(); //<----- get the value from the parent select 
                $.ajax({
                    url     : root+'admin_tools/select_freezer_drawer_update_type.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { type: type}, //Data you are sending
                    success : function(data){$('#type').html(data)}, 
                    error   : function(){alert('An Error Has Occurred')} //error message
                })

			});
			
		});
		// submit form
		$(document).ready(function() {
		
		    // process the form
		    $('form').submit(function(event) {
		    	
			 var values = [];
			  //check that all of the fields are populated
			 var type = $('#update_type').val();
			 var inputs = document.getElementsByTagName("input");
             var txt = "";
             var valid = 'true';
             for (var i = 0; i < inputs.length; i++) {
                 txt = inputs[i].value;
                 if(txt != 'Go Back'){
                 	values.push(txt);
                 }
              
                 var name = inputs[i].getAttribute("name");
 				 var n = txt.length;
	             if(n == 0){
	             	valid = 'false';
	             	inputs[i].style.background = "blue";
				 }else{
				 	inputs[i].style.background = "white";
				 }
			 }
			 
			 //check selects are selected for required data
			var selects = document.getElementsByTagName("select");
            var i2;
            for (i2 = 1; i2 < selects.length; i2++) {//skip the first select which should be the update type
                 selected = selects[i2].value;
                 values.push(selected);
                 var name2 = selects[i2].getAttribute("name");
	             if(selected == '0'){
	                 selects[i2].style.background = "blue";
	                 valid = 'false';
	             }
	             else{
	                 selects[i2].style.background = "white";
	             }
			}
			if(valid == 'true'){
				
			   // process the form
			       $.ajax({
	                    url     : root+'admin_tools/process_freezer_drawer_update.php', //the url you are sending datas to which will again send the result
	                    async: false,
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { type: type, values:values}, //Data you are sending
	                    success : function(data){alert(data)}, // 
	                    error   : function(){alert('A Submission Error Has Occurred')} //error message,
	                }) 
			  }else{
			  	    alert("ERROR: Please Fill In All Fields");
			   	    event.preventDefault();
			  }
		    });
		});
</script>

<form class="registration" action="update_freezer_drawers.php" method="GET">
	<div class="container-fluid">
	<div class="row">
	<div class="col-xs-6">
	
	<p>
	<label class="textbox-label">Select Update Type:</label>
	<select id='update_type' name='update_type'>
	<option value='0'>-Select-</option>
	<option value='drawer'>Add Drawers</option>
	<option value='freezer'>Add Freezers</option>
	<option value='freezer_drawer'>Connect Drawers To Freezers</option>
	</select>
	</p>
	</div>
	</div>
	
	<div id = "type"></div>
	
 	
 	
	<!--submit button-->
	<button class="button" type="submit" name="submit" value="submit">Submit</button>
	<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
	<!----------------------------------------------------------------------------------------->
	
	</fieldset>
	
	
	</div><!--end of class = 'container-fluid'-->
</form>

</body>
</html>
