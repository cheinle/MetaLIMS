<?php //Note: Testing for use for label printing. works from main directory?>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php');
if(!isset($_SESSION)) { session_start(); }
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
	build_xls_output_table($stmt);
}					
?>


</body>
</html>
