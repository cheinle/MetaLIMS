<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Query Selection</title>
	
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  	
</head>

 
<body>
<?php 
include('index.php');
include('functions/check_box_tables.php');

 ?>
<div class="page-header">
<h3>Query Selection</h3>	
</div>
	
<!--query by date range-->	
<form class="registration" action="query_results_mod.php" method="GET">
<fieldset>
<LEGEND><b>Display Sample Info:</b></LEGEND>
	<div class="col-xs-6">
	<i>(Select Dates and/or Fields to Query By)</i><br>
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
		    include_once('functions/convert_header_names.php');
			//drowpdown to select query from videos table
			$query = "SELECT * FROM sample";
			#$query = "SELECT * FROM sample LEFT JOIN isolates ON isolates.sample_name = sample.sample_name";
			
			$result = mysqli_query($dbc, $query);
			if($result){
					#echo 'You queried your sample table';
					#echo '<p>'.$query.'</p>';
			}else{
					echo 'An error has occured';
					mysqli_error($dbc);
					echo '<p>'.$query.'</p>';
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
					#echo "<option value='$id'>$name</option>";
    		}
			sort($array);
			#print_r($array);
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
					source:'suggest_name.php', 
					minLength:2
    			});
			});
		</script>
	</p>
	</div>
				
	<p>
	<h3 class="checkbox-header">Display?:</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="screen") echo "checked";?> value="screen">Display To Screen</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="xls") echo "checked";?> value="xls">Download MS Excel Worksheet</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="sensor") echo "checked";?> value="sensor">Include Sensor Data</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="view_read_sub") echo "checked";?> value="view_read_sub">View Read Submission IDs</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="isolates") echo "checked";?> value="isolates">View Isolate Info Only</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="xls_isolates") echo "checked";?> value="xls_isolates">Download MS Excel-Isolate Info Only</label>
	</div>
	</p>
	
	<p>
	<h3 class="checkbox-header">Bulk Action:</h3>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="bulk_dna") echo "checked";?> value="bulk_dna">Bulk Update For DNA Extraction Info</label>
	<!--<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="seq_sub") echo "checked";?> value="seq_sub">Bulk Update For Sequencing Submission</label>-->
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="read_sub") echo "checked";?> value="read_sub">Bulk Insert For Read Submission IDs</label>
	<label class="checkbox-label"><input type="radio" name="db_content" <?php if (isset($db_content) && $db_content =="update_read_sub") echo "checked";?> value="update_read_sub">Bulk Update For Read Submission IDs</label>
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

<p>
<!--<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#demo" aria-expanded="true" aria-controls="demo" class='buttonLength'>More Query Options</button>
<div id="demo" class="collapse">-->
</p>

<a name="projects"></a>
<a name="samplers"></a>
<form class="registration" action="query_results_mod.php" method="GET">
	
<fieldset>
	<LEGEND><b>Data Dump (Choose One)</b></LEGEND>
	<LEGEND  style="margin-left:2%;"><b>Display All Project Info:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="project_all">Display all DB Project Info</label>
	</div>
	
	
	<LEGEND  style="margin-left:2%;"><b>  Display All Weather:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="weather_all">Display all DB weather content</label>
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content =="xls") echo "checked";?> value="weather_xls">Download to MS Excel worksheet</label>
	</div>
	
	<!--DEPRICATED
		<LEGEND><b>  Display All Daily Data:</b></LEGEND>
	<label class="textbox-label">Select Date Range:</label>
	<input type="text" id="datepicker3"  name="sdate"><input type="text" id="datepicker4"  name="edate">
	<script>
		$('#datepicker3').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#datepicker4').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="daily_data_all">Display all DB daily data content</label>
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content =="xls") echo "checked";?> value="daily_data_xls">Download to MS Excel worksheet</label>
	</div>-->
	
	<LEGEND  style="margin-left:2%;"><b>  Display All Air Samplers:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="airSampler_all">Display all DB air sampler content</label>
	</div>
	
	<LEGEND  style="margin-left:2%;"><b>Display All Particle Counters:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="partCt_all">Display all DB particle counter content</label>
	</div>

	<!--<LEGEND><b>  Display All Sequencing Submission Info:</b></LEGEND>
	<div class="vert-checkboxes">
	<label class="checkbox-label"><input type="radio" name="db_content"<?php if (isset($db_content) && $db_content=="all") echo "checked";?>value="all">Display all DB sequencing submission info</label>
	</div>-->
	

	<!--submit button-->
	<button type="submit" name="submit" class="button" value="other"> Submit </button>
</fieldset>
</form>
</body>
</html>
