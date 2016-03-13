<?php
	include ('../../index.php');
	include ('../../database_connection.php');
?>	
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Field Selection</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
</head>

 
<body>
<div class="page-header">
<h3>Field Selection For Update</h3>	
</div>
	
<form class="registration" action="things_bulk_update_build_table.php" method="GET">
<fieldset>
<LEGEND><b>Select Field To Update:</b></LEGEND>
	<div class="col-xs-6">
		<select id=thing_select name = thing_select>
		<option value=''>-Select-</option>
		<?php
		$query = "SELECT thing_id, label_name, type, select_values FROM create_user_things";
		$stmt = $dbc->prepare($query);
		if ($stmt->execute()){
	    	$stmt->bind_result($thing_id,$label_name,$type,$select_values);
	    	while ($stmt->fetch()){
	        	echo '<option value="'.$thing_id.':'.$label_name.':'.$type.':'.$select_values.'">'.$label_name.'</option>';			}
		}
		?>
		</select>
	</div>
</fielset>
<button type="submit" name="submit" class="button" value="other"> Submit </button>
</form>
</body>
</html>
