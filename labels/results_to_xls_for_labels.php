<?php 
if(!isset($_SESSION)) { session_start(); }
include('../database_connection.php');
include('../functions/white_list.php');
include('../index.php');
?>
<html>
<head>
<title>Query Results For Labels</title>
<style>

th {
    border: none;
    word-wrap:break-word;
	/*white-space : nowrap;*/
	background-color: white;
}
td{
	border: none;
	padding-left: 5px;
}
table {
  table-layout:fixed;
  overflow:hidden;
  word-wrap:break-word;
  border: none;
  border-radius: 0 px;
  box-shadow:0 0px 0px rgba(0,0,0,0);
}
		
	
</style>


</head>

<body style="background-color:white">
<?php
if(isset($_GET['submit'])){

		echo '<p>';    
		echo "<h2>Instructions:</h2> Print QR Codes for Sample Names and Barcode Fields (*if populated) by right-clicking anywhere on page and printing from browser window. Else <a href=\"download.php?download_file=nanolims_labels.txt\">download alternative file</a>. Alternative file will contain tab-delimited entries for easy import into common label making software"	;
		echo '</p>';
		echo '<br>';
			
			
		////////////////////////////////////////////////////////////////////////////////////////////////
		//Define what kind of fields you are querying
		////////////////////////////////////////////////////////////////////////////////////////////////		
		
		$check_date = 'false';
		$check_field = 'false';
		$query_date = '';
		$query_field = '';
		$stmt = '';
		$selected_thing_id = '';
		if(isset($_GET['thing_select']) && $_GET['thing_select'] != 0){
			$selected_thing_id = $_GET['thing_select'];
		}
		if(($_GET['smydate'] != NULL) && ($_GET['emydate'] != NULL)){
		
			//sanatize user input to make safe for browser
			$p_smydate = htmlspecialchars($_GET['smydate']);
			$p_emydate = htmlspecialchars($_GET['emydate']);
		
			//make sure you cover the entire day
			$p_smydate = $p_smydate.' 00:00:00';
			$p_emydate = $p_emydate.' 23:59:00';
			$query_date = ' sample.start_samp_date_time BETWEEN (?) AND (?)'; //still going to pull this date time from the regular table
			$check_date = 'true';
		}
			
		if(($_GET['field'] != '0') && isset($_GET['query'])){
			$p_field = htmlspecialchars($_GET['field']);
			$p_query_basis = htmlspecialchars($_GET['query']);
			$thing_id = '';
			//check whitelist for p_field
			$p_field_check = whiteList($p_field, 'column');
			if($p_field_check == 'true'){
				if($p_field == 'sampler_name'){
					$query_field = " sample_sampler.$p_field = (?)";
				}
				elseif (preg_match("/thing(\d+)/i",$p_field,$matches)) {
					$query_field = " thing_storing.thing_id = (?) AND thing_storing.thing_value = (?)";
					$thing_id = $matches[1];
				}
				else{
					$query_field = " sample.$p_field = (?)";
				}
				$check_field = 'true';
			}
		}
		//if(isset($_GET['column_names'])){$field_names = check_box_results($_GET['column_names']);}//removed
		if(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_dna'){
			$field_names = 'sample.sample_name,sample.d_conc,sample.sample_sort';
		}
		elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_storage'){
			$field_names = 'sample.sample_name,sample.sample_sort';
		}
		elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_things'){
			$field_names = 'sample.sample_name,sample.sample_sort';
		}
		else{

			$field_names = 'sample.sample_name,sample.barcode,sample.start_samp_date_time,sample.project_name,sample.sample_type,sample.sample_num';
		}

		////////////////////////////////////////////////////////////////////////////////////////////////
		//Check what type of query you are doing
		////////////////////////////////////////////////////////////////////////////////////////////////		
	

		$query_main = "SELECT $field_names FROM sample JOIN storage_info ON storage_info.sample_name = sample.sample_name JOIN thing_storing ON thing_storing.sample_name = sample.sample_name WHERE ";

		
		////////////////////////////////////////////////////////////////////////////////////////////////
		//Build Query
		////////////////////////////////////////////////////////////////////////////////////////////////
		$query = "";
		$query_add = "";
		
		if($check_field == 'true' && $check_date == 'false'){//only query field populated
			$query = $query_main.$query_field;
			$stmt = $dbc->prepare($query);
			if (preg_match("/thing/i",$p_field)) {
				$stmt -> bind_param('is', $thing_id, $p_query_basis);
			}
			else{
				$stmt -> bind_param('s', $p_query_basis);
			}
		}
		elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
			$query = $query_main.$query_date;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('ss',$p_smydate , $p_emydate);
		}
		elseif ($check_field == 'true' && $check_date == 'true') {//date and query fields are populated
			$query = $query_main.$query_field.' AND '.$query_date;
			$query_add = $query_field.' AND '.$query_date;
			$stmt = $dbc->prepare($query);
			
			if (preg_match("/thing/i",$p_field)) {
				$stmt -> bind_param('isss',$thing_id,$p_query_basis, $p_smydate , $p_emydate);
			}
			else{
				$stmt -> bind_param('sss', $p_query_basis, $p_smydate , $p_emydate);
			}

		}
		else{
			echo '<script>Alert.render("ERROR: No entries found. Please check fields");</script>';
			echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
		}
	
		if ($stmt->execute()){
			$myfile = fopen("nanolims_labels.txt", "w") or die("Unable to open file!");
			/* bind variables to prepared statement */
			$stmt->bind_result($sample_name,$barcode_name,$start_date_time,$project_name,$sample_type,$sample_num);

			$counter = 0;
			$sample_names_seen = array();
			echo "<table class=\"plain\">";
			
			if(($_GET['db_view']) == 'barcode' ){
				echo "<tr><th>Sample Name</th><th>Barcode Name</th><th>Barcode Name QR Code</th></tr>";
			}
			if(($_GET['db_view']) == 'sample' ){
				echo "<tr><th>Sample Name</th><th>QR Code</th></tr>";
			}
			
			while ($stmt->fetch()) {
				$counter++;
				
				if($counter == 1){
					//headers
					fwrite($myfile, "Sample Name\t");
					fwrite($myfile, "Barcode Name\t");
					fwrite($myfile, "Start Date/Time\t");
					fwrite($myfile, "Project Name\t");
					fwrite($myfile, "Sample Type\t");
					fwrite($myfile, "Sample Number\t");
					fwrite($myfile, "\n");
				}
				
				//check if you've seen the sample name already
				if (in_array($sample_name, $sample_names_seen)){
					continue;
				}else{
					array_push($sample_names_seen,$sample_name);
				}
					
				fwrite($myfile, "$sample_name\t");
				fwrite($myfile, "$barcode_name\t");
				fwrite($myfile, "$start_date_time\t");
				fwrite($myfile, "$project_name\t");
				fwrite($myfile, "$sample_type\t");
				fwrite($myfile, "$sample_num\t");
				fwrite($myfile, "\n");
				
				
				$sample_name = htmlspecialchars($sample_name);
				$barcode_name = htmlspecialchars($barcode_name);
				
				if(($_GET['db_view']) == 'barcode' ){
						$img2 = "<img src=\"https://chart.googleapis.com/chart?chs=50x50&cht=qr&chl=".$barcode_name."&choe=UTF-8\" title=\"".$barcode_name."\" />";
						if($barcode_name == ''){
							$img2 = '';
							$barcode_name = '(barcode field empty)';
						}
						echo "<tr><td>".$sample_name."</td><td>&nbsp".$barcode_name."</td><td>".$img2."</td></tr>";
				}
				if(($_GET['db_view']) == 'sample' ){
					$img = "<img src=\"https://chart.googleapis.com/chart?chs=50x50&cht=qr&chl=".$sample_name."&choe=UTF-8\" title=\"".$sample_name."\" />";
					echo "<tr><td>".$sample_name."</td><td>".$img."</td></tr>";
				}
			}
	}
	
}
	
?>




</body>
</html>
