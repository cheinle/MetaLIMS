<?php
	//build xls spreadsheet for output
#function build_xls_output_table($sql){
function build_xls_output_table($stmt){
	include('database_connection.php');
	include('functions/build_table.php');
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=document_name.xls");
		
	echo "<html>";
	//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
	echo "<body>";

	#$stmt = $dbc->prepare("$sql");
	build_table($stmt,'xls');
	
	echo "</body>";
	echo "</html>";

}
