<?php

//build xls spreadsheet for output
function build_xls_output_table($stmt){
		//include($_SESSION['include_path'].'/index.php');
		include($_SESSION['include_path'].'/functions/build_table_tab.php');
		build_table_tab($stmt,'xls');
		
		echo "<div class=\"page-header\"><h3>Download Query Results</h3></div>";
	
		echo '<div class="border">';
		echo "<p>";
		echo "*Click link to start download";
		echo '<div style="text-align:left">';    
		echo '<a href="download.php?download_file=nanolims_export.txt" style = "font-size: 40px;">Download Main File</a>';
		echo '</div>';
		
		echo '<div style="text-align:left">';    
		echo '<a href="download.php?download_file=nanolims_user_created_export.txt" style = "font-size: 40px;">Download User Created Things File</a>';
		echo '</div>';
		echo "</p>";
		
		echo "</div>";


	
}
?>