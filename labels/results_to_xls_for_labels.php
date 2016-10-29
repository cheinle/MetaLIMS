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
	include($_SESSION['include_path'].'functions/build_xls_output_table.php');

	$sql = "SELECT sample_name,start_samp_date_time,project_name,sample_type,sample_num FROM sample";
	$stmt = $dbc->prepare("$sql");
	//build_xls_output_table($stmt);
	
	if ($stmt->execute()){
			$myfile = fopen("nanolims_labels.txt", "w") or die("Unable to open file!");
			/* bind variables to prepared statement */
			$stmt->bind_result($sample_name,$start_date_time,$project_name,$sample_type,$sample_num);

			$counter = 0;
			$sample_names_seen = array();
			while ($stmt->fetch()) {
				$counter++;
				
				if($counter == 1){
					//headers
					fwrite($myfile, "Sample Name\t");
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
				fwrite($myfile, "$start_date_time\t");
				fwrite($myfile, "$project_name\t");
				fwrite($myfile, "$sample_type\t");
				fwrite($myfile, "$sample_num\t");
				fwrite($myfile, "\n");

			}
	}
	
}
	
echo '<div style="text-align:center">';    
echo '<a href="download.php?download_file=nanolims_labels.txt" style = "font-size: 50px">Download Label File</a>';
echo '</div>';	
			
?>
<script type="text/javascript" src="jquery/sample/jquery-1.3.2.min.js"></script>    
<script type="text/javascript" src="jquery/jquery-barcode.js"></script>  

<div id="bcTarget"></div>   
<script type="text/javascript">
	$("#bcTarget").barcode("1234567890128", "datamatrix"); 
	
</script>
<?php
include('php-barcode.php');
$res= 'fpdf';
$color = 'FF0000';
$x = '0';
$y = '0';
$angle ='150';
$type = 'code128';
$datas = '12345678';
$width = '2';
$height = '50';

Barcode::gd($res, $color, $x, $y, $angle, $type, $datas, $width = null, $height = null);  
Barcode::fpdf($res, $color, $x, $y, $angle, $type, $datas, $width = null, $height = null);  
?>


</body>
</html>
