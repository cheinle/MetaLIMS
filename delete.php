<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('database_connection.php');
include ('index.php');


?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Change Visibility</title>
	</head>
 
	<body>
		<div class="page-header">
	    <h3>Change Visibility</h3>
		</div>
		
		<script type="text/javascript">
			$(document).ready(function(){  
				               
                $('#table').change(function(){ //on change event
                var table_value = $('#table').val(); //<----- get the value from the parent select 
                $.ajax({
                    url     : root+'delete_column_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { table_value: table_value}, //Data you are sending
                    //success : function(data){$('#div_store').html(data)},
                    success : function(data){$('#col').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
                })
			
			
			});
			
		});
		

		function validate(form) {

	       var valid = 'false';
		   var selects = document.getElementsByTagName("select");
           var i2;
           for (i2 = 0; i2 < selects.length; i2++) {
                 selected = selects[i2].value;
                 var name2 = selects[i2].getAttribute("name");
                 if(selected == '0'){
                 	selects[i2].style.background = "blue";
                    valid = 'false';
                 }
                 else{
                 	selects[i2].style.background = "white";
                 }

		   }
		   if(valid == 'false'){
		    	alert('ERROR: Some Inputs Are Invalid. Please Check Fields');
		    	return false;
		   }
		   else{
		   		return confirm('Sure You Want To Submit?');
		   }
		}
	</script>
	<?php
	echo '<form  class="registration" onsubmit="return validate(this)" action="delete.php" method="GET">';
	
	$tables = get_visible_tables($dbc);
	echo "<label class='textbox-label'>Table Name:</label>";
						
	echo "<select id='table' name='table'>";
	echo "<option value='0'>-Select-</option>";
	foreach($tables as $table => $pk){
			echo '<option value="'.$table.'">'.$table.'</option>';
	}
	echo "</select>";
	
	echo "<div id='col' name='col'></div>";
	echo "<button class='button' type='submit' name='submit' value='1'>DELETE</button>";
	echo "</form>";
	
	
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
