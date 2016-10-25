<?php

//build xls spreadsheet for output
function build_xls_output_table($stmt){
		//include($_SESSION['include_path'].'/index.php');
		include($_SESSION['include_path'].'/functions/build_table_tab.php');
		build_table_tab($stmt,'xls');
		echo '<div style="text-align:center">';    
		echo '<a href="download.php?download_file=nanolims_export.txt" style = "font-size: 50px">Download Main File</a>';
		echo '</div>';
		
		echo '<div style="text-align:center">';    
		echo '<a href="download.php?download_file=nanolims_user_created_export.txt" style = "font-size: 50px">Download User Created Things File</a>';
		echo '</div>';


	
}
?>