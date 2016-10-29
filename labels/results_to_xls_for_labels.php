<?php 
if(!isset($_SESSION)) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php');
?>
<html>
<head>
<title>Query Results For Labels</title>
</head>

<body>
<?php
if(isset($_GET['submit_labels']) && $_GET['db_content']=='xls'){
	
	echo '<p>';    
	echo "<h2>Instructions:</h2> Print QR Codes for Sample Names and Barcode Fields (*if populated) by righ-clicking and printing from browser window. Else <a href=\"download.php?download_file=nanolims_labels.txt\">download alternative file</a>. Alternative file will contain tab-delimited entries for easy import into common label making software"	;
	echo '</p>';
	echo '<br>';

	$sql = "SELECT sample_name,barcode,start_samp_date_time,project_name,sample_type,sample_num FROM sample";
	$stmt = $dbc->prepare("$sql");
	//build_xls_output_table($stmt);
	
	if ($stmt->execute()){
			$myfile = fopen("nanolims_labels.txt", "w") or die("Unable to open file!");
			/* bind variables to prepared statement */
			$stmt->bind_result($sample_name,$barcode_name,$start_date_time,$project_name,$sample_type,$sample_num);

			$counter = 0;
			$sample_names_seen = array();
			echo "<table>";
			echo "<tr><th>Sample Name</th><th>Sample Name QR Code</th><th>Barcode Name</th><th>Barcode Name QR Code</th></tr>";
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
				$img = "<img src=\"https://chart.googleapis.com/chart?chs=50x50&cht=qr&chl=".$sample_name."&choe=UTF-8\" title=\"".$sample_name."\" />";
				$img2 = "<img src=\"https://chart.googleapis.com/chart?chs=50x50&cht=qr&chl=".$barcode_name."&choe=UTF-8\" title=\"".$barcode_name."\" />";
				echo "<tr><td>".$sample_name."</td><td>".$img."</td><td>".$barcode_name."</td><td>".$img2."</td></tr>";
			}
	}
	
}
	
?>




</body>
</html>
