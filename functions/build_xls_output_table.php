<?php

//build xls spreadsheet for output
function build_xls_output_table($stmt){
	include($_SESSION['include_path'].'/functions/build_table_tab.php');
	build_table_tab($stmt,'xls');
	echo '<div style="text-align:center">';    
	echo '<a href="download.php?download_file=document_name.xls" style = "font-size: 50px">Click To Download File</a>';
	echo '</div>';
}
?>