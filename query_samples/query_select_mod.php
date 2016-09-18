<?php include('../index.php');
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
<?php 
include($path.'functions/check_box_tables.php');
 ?>
<div class="page-header">
<h3>Query Selection</h3>	
</div>
	
<!--query by date range-->	
<form class="registration" action="query_results_mod.php" method="GET">
<fieldset>
<LEGEND><b>Display Sample Info:</b></LEGEND>
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
			if ($stmt2 = $dbc->prepare("SELECT thing_id,label_name FROM create_user_things")) {
	    		$stmt2->execute();
				/* bind variables to prepared statement */
				$stmt2->bind_result($thing_id, $label_name);
				
				/* fetch values */
				while ($stmt2->fetch()) {
					 $name = $label_name;
				     $array[$name]['name'] = $name;
					 $array[$name]['id'] = $thing_id;
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
		<label class="textbox-label">Where field matches:</label><br>
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
	<h3 class="checkbox-header">Display?:</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_view" <?php if (isset($db_content) && $db_content =="screen") echo "checked";?> value="screen">Display To Screen</label>
	<label class="checkbox-label"><input type="radio" name="db_view" <?php if (isset($db_content) && $db_content =="xls") echo "checked";?> value="xls">Download MS Excel Worksheet</label>
	</div>
	</p>
	
	<p>
	<h3 class="checkbox-header">Include (Optional):</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="view_read_sub") echo "checked";?> value="view_read_sub">Read Submission IDs</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="view_user_things") echo "checked";?> value="view_user_things">User Created Fields</label>
	</div>
	</p>
	
	<p>
	<h3 class="checkbox-header">Bulk Action:</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="bulk_dna") echo "checked";?> value="bulk_dna">Bulk Update For DNA Extraction Info</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="bulk_storage") echo "checked";?> value="bulk_storage">Bulk Update For Storage Info</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="read_sub") echo "checked";?> value="read_sub">Bulk Insert For Read Submission IDs</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="update_read_sub") echo "checked";?> value="update_read_sub">Bulk Update AND View For Read Submission IDs</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="bulk_things") echo "checked";?> value="bulk_things">Bulk Update User Created Fields</label>
	</div>
	</p>
	
	<p>
	<label class="textbox-label">Columns to Display: (default 'All')</label>
	
	<div class="vert-checkboxes">
	<?php check_box();?>
	</div>
	</p>
	
	<!--submit button-->
	<button type="submit" class="button" name="submit" value="sample"> Submit </button>
</fieldset>
</form>


<a name="projects"></a>
<a name="samplers"></a>
<form class="registration" action="query_results_mod.php" method="GET">
	
<fieldset>
	<LEGEND><b>Display All (Choose One)</b></LEGEND>
	<LEGEND  style="margin-left:1.5%;"><b>Display All Project Info:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="project_all") echo "checked";?>value="project_all">Display All DB Project Info</label>
	</div>
	
	<LEGEND  style="margin-left:1.5%;"><b>  Display All Samplers:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="sampler_all") echo "checked";?>value="sampler_all">Display All Sampler Content</label>
	</div>
	
	<LEGEND  style="margin-left:1.5%;"><b>Display All Sensors:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="partCt_all") echo "checked";?>value="partCt_all">Display All DB Sensor Content</label>
	</div>
	
	<LEGEND  style="margin-left:1.5%;"><b>Display All Location Information:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="location_all") echo "checked";?>value="location_all">Display All DB Locations</label>
	</div>



	<!--submit button-->
	<button type="submit" name="submit" class="button" value="other"> Submit </button>
</fieldset>
</form>
</body>
</html>
