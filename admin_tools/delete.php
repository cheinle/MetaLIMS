<?php
include ('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Change Visibility</title>
	</head>
 
	<body>
		<div class="page-header">
	    <h3>Change Visibility For Dropdown Options (Delete)</h3>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){  
				               
                $('#table').change(function(){ //on change event
                var table_value = $('#table').val(); //<----- get the value from the parent select 
                var split = table_value.split("-"); 
		       	table_value = split[0];
		       	var visible = 'invisible';
                $.ajax({
                    url     : root+'admin_tools/delete_column_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { table_value: table_value, visible: visible}, //Data you are sending
                    success : function(data){$('#col').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
                })

			});
			
		});
		
		$(document).ready(function(){  
			
                $('#table2').change(function(){ //on change event
                var table_value = $('#table2').val(); //<----- get the value from the parent select 
                var split = table_value.split("-"); 
		       	table_value = split[0];
		       	var visible = 'visible';
                $.ajax({
                    url     : root+'admin_tools/delete_column_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { table_value: table_value, visible: visible}, //Data you are sending
                    success : function(data){$('#col2').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
				});
			});
			
		});
		
		
		// submit form
		$(document).ready(function() {
		
		    // process the form
		    $('form').submit(function(event) {
			   var form_type = $(this).closest("form").attr('id');
			   var visible = '';
			   var table_value = '';
			   var field_value = '';
			   if(form_type == 'invisible'){
			   		visible = 0;
			   		table_value = $('#table').val();
			   		field_value = $('#column').val();
			   		if(table_value == '0' || (field_value == '0' || field_value == undefined)){
			   			$("#table").css({"background-color": "blue"});
			   			$("#column").css({"background-color": "blue"});
			   			alert("ERROR: Please Fill In All Invisible Fields");
			   			event.preventDefault();
			   		}
			   		else{
			   			  var split = table_value.split("-"); 
					      table_value = split[0];
					      var pk = split[1];
			
					        // process the form
					       $.ajax({
			                    url     : root+'admin_tools/process_visibility_change.php', //the url you are sending datas to which will again send the result
			                    async: false,
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { table_value: table_value, field_value: field_value, pk:pk, visible:visible}, //Data you are sending
			                    success : function(data){alert(data)}, // On success, it will populate the 2nd select
			                    error   : function(){alert('A Submission Error Has Occurred')} //error message,
			                }) 
			   		}
			   }
			   if(form_type == 'visible'){
			   		visible = 1;
			   		table_value = $('#table2').val();
			   		field_value = $('#column2').val();
			   		var admin_yn_value = $('#admin_yn').val();
			   		//alert(admin_yn_value);
			   		if(table_value == '0' || (field_value == '0' || field_value == undefined)){
			   			$("#table2").css({"background-color": "blue"});
			   			$("#column2").css({"background-color": "blue"});
			   			alert("ERROR: Please Fill In All Visible Fields");
			   			event.preventDefault();
			   		}
			   		else{
			   			  var split = table_value.split("-"); 
					       table_value = split[0];
					       var pk = split[1];
			
					        // process the form
					       $.ajax({
			                    url     : root+'admin_tools/process_visibility_change.php', //the url you are sending datas to which will again send the result
			                    async: false,
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { table_value: table_value, field_value: field_value, pk:pk, visible:visible, admin_yn_value: admin_yn_value}, //Data you are sending
			                    success : function(data){alert(data)}, // On success, it will populate the 2nd select
			                    error   : function(){alert('A Submission Error Has Occurred')} //error message,
			                }) 
			   		}
			   }
		     
		    });
		});
	</script>
	<?php
	
	
	
	
	
	
	
	
	echo '<form  class="registration" id="invisible" name="invisible" action="delete.php" method="GET">';
	echo '<fieldset>';
	echo '<pre>Change visibility is a non-permanent delete option and will remove an option from a desired dropdown list. 
This can be used to archive old selections no longer used or as a non-permanent \'delete\' function for options not wanted. 
Samples associated with these dropdowns will still contain archived selection unless updated. Selection can be reversed using Option2: Make Visible

Ex: Table Name: Freezer
Field Value Name: -80C

User will no longer be able to select the -80C freezer to store their samples</pre>';
	echo '<LEGEND><b>Option1: Make Invisible</b></LEGEND>';
	echo '<div class="container-fluid">';
  	echo '<div class="row">';
  	echo '<div class="col-xs-6">';
	$tables = get_visible_tables($dbc);
	echo "<label class='textbox-label'>Table Name:</label>";
						
	echo "<select id='table' name='table'>";
	echo "<option value='0'>-Select-</option>";
	foreach($tables as $table => $pk){
		$new_table_name = ucwords(str_replace("_", " ", $table));
		echo '<option value="'.$table.'-'.$pk.'">'.$new_table_name.'</option>';
	}
	echo "</select>";
	
	echo "<div id='col' name='col'></div>";
	echo "</div></div></div>";
	echo "<button class='button' type='submit' name='submit' value='delete'>DELETE</button>";
	echo '</fieldset>';
	echo "</form>";
	
	
	
	
	/********************************************************************/
	echo '<form  class="registration" id="visible" name="visible" action="delete.php" method="GET">';
	echo '<fieldset>';
	echo '<LEGEND><b>Option2: Make Visible</b></LEGEND>';
	echo '<div class="container-fluid">';
  	echo '<div class="row">';
  	echo '<div class="col-xs-6">';
	$tables = get_visible_tables($dbc);
	echo "<label class='textbox-label'>Table Name:</label>";					
	echo "<select id='table2' name='table2'>";
	echo "<option value='0'>-Select-</option>";
	foreach($tables as $table => $pk){
		$new_table_name = ucwords(str_replace("_", " ", $table));
		echo '<option value="'.$table.'-'.$pk.'">'.$new_table_name.'</option>';
	}
	echo "</select>";
	echo "<div id='col2' name='col2'></div>";
	echo "</div></div></div>";
	echo "<button class='button' type='submit' name='submit' value='add'>ADD</button>";
	echo '</fieldset>';
	echo "</form>";
	
	
	
	/********************************************************************/
	
	
	function get_visible_tables($dbc){
		//get all of the tables that have a visible flag and grab all of the primary keys for each table
  		$tableList = array();
  		$table_res = mysqli_query($dbc,"SHOW TABLES");

  		while($table = mysqli_fetch_array($table_res)){
			$columns_query = "SHOW COLUMNS FROM ".$table[0];
		
			$col_res = mysqli_query($dbc,$columns_query);
  			while($column = mysqli_fetch_array($col_res)){
				if($column[0] == 'visible'){
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
