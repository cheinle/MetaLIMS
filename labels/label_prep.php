<?php 
include('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php'); 
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Query Selection</title>  	
</head>

 
<body>
<?php  ?>
<div class="page-header">
<h3>Label Preperation</h3>
</div>

<form class="registration" action="results_to_xls_for_labels.php" method="GET">
	

	<fieldset>
	<LEGEND><b>Query Labels:</b></LEGEND>
		<div class="col-xs-6">
		<i>(Select Dates and/or Fields to Query By)</i><br><br>
		<label class="textbox-label">Select Start/End Date:</label>
		<input type="text" id="datepicker" class="shrtfields" name="smydate"> 
		<input type="text" id="datepicker2" class="shrtfields" name="emydate">
		
		<script>
			$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
			$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		
		<p>
			<label class="textbox-label">Select Field to Query Samples:</label><br/>
			<?php	
			    include_once($path.'functions/convert_header_names.php');
				
				
				/*Grab Sample Columns*/
				$query = "SELECT * FROM sample";
				$result = mysqli_query($dbc, $query);
				if(!$result){
					echo 'An Error Has Occurred';;
	
	  				printf("error: %s\n", mysqli_error($dbc));
	
				}
				
				echo "<select name='field'>";
				echo "<option value='0'>-Select-</option>";
	
				//push the results into an array so you can sort them and output to dropdown
				$array = array();
				while ($fieldinfo = mysqli_fetch_field($result)){	
						$name = $fieldinfo->name;
						$id = $name;
						$name = convert_header_names($name);
						if($name == 'false'){ //skip certain columns
							continue;
						}
						$array[$name]['name'] = $name;
						$array[$name]['id'] = $id;
	
	    		}
				
				
			
				/*Grab Custom Columns*/;
				if ($stmt2 = $dbc->prepare("SELECT thing_id,label_name FROM create_user_things WHERE visible = 1")) {
		    		$stmt2->execute();
					/* bind variables to prepared statement */
					$stmt2->bind_result($thing_id, $label_name);
					
					/* fetch values */
					while ($stmt2->fetch()) {
						 $name = $label_name;
					     $array[$name]['name'] = $name;
						 $array[$name]['id'] = 'thing'.$thing_id;
					}
					
					/* close statement */
					$stmt2->close();
				}
				/* close connection */
				$dbc->close();
				sort($array);
	
				///////////////
				foreach($array as $key => $value){
					$name2 = $value['name'];
					$id2 = $value['id'];
					echo "<option value='$id2'>$name2</option>";
				}	
				echo "</select>";
			?>
		</p>
		<p>
			<label class="textbox-label">Where field matches (exact):</label><br>
			<p>
	    	<input type="text" size="70" placeholder="Insert Query" name="query" id="customerAutocomplte" class="ui-autocomplete-input" autocomplete="off" value="<?php if(isset($_GET['submit'])&& $submitted != 'true'){ echo htmlspecialchars($_GET['query']);} ?>"/>
			</p>
			<script>
				$(document).ready(function($){
	    			$('#customerAutocomplte').autocomplete({
						source:'../suggest_name.php', 
						minLength:2
	    			});
				});
			</script>
		</p>
		</div>
					
		<p>
		<h3 class="checkbox-header">Generate QR Codes:</h3>
		<div class="vert-checkboxes">
		<label class="checkbox-label"><input type="radio" name="db_view" <?php if (isset($db_content) && $db_content =="sample") echo "checked";?> value="sample">Sample Names</label>
		<label class="checkbox-label"><input type="radio" name="db_view" <?php if (isset($db_content) && $db_content =="barcode") echo "checked";?> value="barcode">Barcode Fields</label>
		</div>
		</p>
		
	<!--submit button-->
	<button type="submit" class="button" name="submit" value="sample"> Submit </button>
</fieldset>
</form>
		
