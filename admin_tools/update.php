<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
include ('../index.php');
include ('table_exclude_list.php');


?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Update</title>
	</head>
 
	<body>
		<div class="page-header">
	    <h3>Update</h3>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){  
				               
                $('#table').change(function(){ //on change event
                var table_value = $('#table').val(); //<----- get the value from the parent select 
                $.ajax({
                    url     : root+'admin_tools/select_entries_for_update.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { table_value: table_value}, //Data you are sending
                    success : function(data){$('#entry').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
                })
              })

			});
			
			
			$(document).ready(function(){  
			 $('#entry').change(function(){ //on change event
                var entry_value = $('#entry').val(); //<----- get the value from the parent select 
                var table_value = $('#table').val();
                $.ajax({
                    url     : root+'admin_tools/dynamically_create_update_form.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { entry_value: entry_value, table_value: table_value}, //Data you are sending
                    success : function(data){$('#col2').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
                })
               })
			});
			
	
		

		// submit form
		$(document).ready(function() {
		
		    // process the form
		    $('form').submit(function(event) {
		    	
		     //grab the original primary key value which is in a hidden input
		      var original_value = document.getElementById("original").value;
			  
			  //check that all of the fields are populated
			 var table_name = $('#table').val()
			 var inputs = document.getElementsByTagName("input");
			 var input_values = [];
             var txt = "";
             var valid = 'true';
             var pk = inputs[0].getAttribute("name"); //assume that your primary key is the first input
             for (var i = 0; i < inputs.length; i++) {
                 txt = inputs[i].value;
             
                 
                 var name = inputs[i].getAttribute("name");
                 var type = inputs[i].getAttribute("type");
                 if(type == 'hidden'){
                 	continue;
                 }
                 
 				 var n = txt.length;
 				 var new_txt = name+'%'+txt;
 				 input_values.push(new_txt);
	             if(n == 0){
	             	valid = 'false';
	             	inputs[i].style.background = "blue";
				 }else{
				 	inputs[i].style.background = "white";
				 }
			  }
			  if(valid == 'true'){
			   // process the form
			       $.ajax({
	                    url     : root+'admin_tools/process_update.php', //the url you are sending datas to which will again send the result
	                    async: false,
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { table_name: table_name, inputs: input_values, pk: pk, original_value: original_value}, //Data you are sending
	                    success : function(data){alert(data)}, // On success, it will populate the 2nd select
	                    error   : function(){alert('A Submission Error Has Occurred')} //error message,
	                }) 
			  }else{
			  	    alert("ERROR: Please Fill In All Fields");
			   	    event.preventDefault();
			  }
		    });
		});
	</script>
	<?php
	
	echo '<form  class="registration" action="update.php" method="GET">';
	echo '<fieldset>';
	echo '<LEGEND><b>Choose Table And Entry To Update: </b></LEGEND>';
	echo '<div class="container-fluid">';
  	echo '<div class="row">';
  	echo '<div class="col-xs-6">';
	$tables = get_all_tables($dbc);
	echo "<label class='textbox-label'>Table Name:</label>";
						
	echo "<select id='table' name='table'>";
	echo "<option value='0'>-Select-</option>";
	foreach($tables as $table => $pk){
			echo '<option value="'.$table.'">'.$table.'</option>';
	}
	echo "</select>";
	
	echo "<div id='col' name='col'>";
	echo "<label class='textbox-label'>Entry To Update:</label>";
	echo "<select id='entry' name='entry'>";
	echo "<option value='0'>-Select-</option>";
	echo "</select>";
	echo "</div>";
	echo "<div id='col2' name='col2'></div>";
	echo "</div></div></div>";
	echo "<button class='button' type='submit' name='submit' value='update'>Update</button>";
	echo '</fieldset>';
	echo "</form>";
	
	
	function get_all_tables($dbc){
		//get all of the tables that have a visible flag and grab all of the primary keys for each table
  		$tableList = array();
  		$table_res = mysqli_query($dbc,"SHOW TABLES");
  		while($table = mysqli_fetch_array($table_res)){
			$columns_query = "SHOW COLUMNS FROM ".$table[0];
			$col_res = mysqli_query($dbc,$columns_query);
  			while($column = mysqli_fetch_array($col_res)){
					$exclude_table_name = check_exclude_list($table[0],'update');
					if($exclude_table_name == false){
						$pk_query = "SHOW KEYS FROM ".$table[0]." WHERE Key_name = 'PRIMARY'";
						$pk_res = mysqli_query($dbc,$pk_query);
	  					while($pk = mysqli_fetch_array($pk_res)){
							$tableList[$table[0]] = $pk[4];
						}
					}
			}
	
	       
		}
  		return $tableList;
	}
	?>

	</body>
	
	
</html>
