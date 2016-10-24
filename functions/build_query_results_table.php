<?php	

//display table
function build_query_results_table($stmt,$table_type,$dbc){ //table types are 'dislapy' and 'xls'
	include($_SESSION['include_path'].'functions/convert_time.php');
	include($_SESSION['include_path'].'functions/convert_header_names.php');
	include($_SESSION['include_path'].'functions/find_samplers.php');
	include($_SESSION['include_path'].'functions/find_sensors.php');

	echo "<div id=\"tabs\">";
		 echo "<ul >";
		     echo "<li><a href=\"#fragment-1\"><span>General</span></a></li>";
			 echo "<li><a href=\"#fragment-2\"><span>DNA Extraction Info</span></a></li>";
			 echo "<li><a href=\"#fragment-3\"><span>RNA Extraction Info</span></a></li>";
			 echo "<li><a href=\"#fragment-4\"><span>Analysis</span></a></li>";
			 echo "<li><a href=\"#fragment-5\"><span>User Created Fields</span></a></li>";
			 echo "<li><a href=\"#fragment-6\"><span>Notes</span></a></li>";
		 echo "</ul>";
	  
		 echo "<div id=\"fragment-1\">";
			//General
				//sample_number, barcode,project name,location,relative location,mediat type,collector names,sample type,storage location,samplers
		//if ($stmt2 = $dbc->prepare("SELECT sample_name,sample_sort,barcode,project_name,location_name,relt_loc_name,media_type,collector_name,sample_type FROM sample;")) {
				$stmt->execute();
				/* bind variables to prepared statement */
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$updated_by,$time_stamp);
				echo "<table id=\"datatable\" class=\"display\" cellspacing=\"0\" width=\"100%\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Barcode</th>";
				echo "<th>Project Name</th>";
				echo "<th>Location</th>";
				echo "<th>Relative Location</th>";
				echo "<th>Media Type</th>";
				echo "<th>Collector Name</th>";
				echo "<th>Sample Type</th>";
				echo "<th>Samplers</th>";
				echo "<th>Start Time</th>";
				echo "<th>End Time</th>";
				echo "<th>Sampling Duration</th>";
				echo "<th>Updated By</th>";
				echo "<th>Time Stamp</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tfoot>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Barcode</th>";
				echo "<th>Project Name</th>";
				echo "<th>Location</th>";
				echo "<th>Relative Location</th>";
				echo "<th>Media Type</th>";
				echo "<th>Collector Name</th>";
				echo "<th>Sample Type</th>";
				echo "<th>Samplers</th>";
				echo "<th>Start Time</th>";
				echo "<th>End Time</th>";
				echo "<th>Sampling Duration</th>";
				echo "<th>Updated By</th>";
				echo "<th>Time Stamp</th>";
				echo "</tr>";
				echo "</tfoot>";
				
				echo "<tbody>";
				
				/* fetch values */
				while ($stmt->fetch()) {
					 $sample_name = htmlspecialchars($sample_name);
					 $sample_sort = htmlspecialchars($sample_sort);
					 $barcode = htmlspecialchars($barcode);
					 $project_name = htmlspecialchars($project_name);
					 $location = htmlspecialchars($location);
				     $relative_location = htmlspecialchars($relative_location);
				     $media_type = htmlspecialchars($media_type);
				     $collector_name = htmlspecialchars($collector_name);
					 $sample_type = htmlspecialchars($sample_type);
					 $start_time = htmlspecialchars($start_time);
					 $end_time = htmlspecialchars($end_time);
					 $total_time = htmlspecialchars($total_time);
					 $updated_by = htmlspecialchars($updated_by);
					 $time_stamp = htmlspecialchars($time_stamp);
					 
					 $key = 'total_time';
					 $converted_total_time = convert_time($key, $total_time);
					 //$sample_name = htmlspecialchars($sample_name);
					 
					 
					 $samplers = find_samplers($sample_name,$table_type);
					 
					 echo "<tr>";
					 echo "<td>$sample_name</td>";
					 echo "<td>$sample_sort</td>";
					 echo "<td>$barcode</td>";
					 echo "<td>$project_name</td>";
					 echo "<td>$location</td>";
					 echo "<td>$relative_location</td>";
					 echo "<td>$media_type</td>";
				     echo "<td>$collector_name</td>";
				     echo "<td>$sample_type</td>";
				     echo "<td class=\"reg\">$samplers</td>";
					 echo "<td>$start_time</td>";
					 echo "<td>$end_time</td>";
					 echo "<td>$converted_total_time</td>"; 
					 echo "<td>$updated_by</td>";
					 echo "<td>$time_stamp</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
				/* close statement */
				$stmt->close();
			//}
			/* close connection */
			$dbc->close();
		echo "</div>";
	echo "</div>"; //end of tabs
	echo "<script>$( \"#tabs\" ).tabs();</script>";
}
	//DNA
		//dna extraction date
		//dna extraction kit
		//dna concentration
		//volumne of dna elution
		//instrument used to measure dna concentration
		//volume of dna used to measure dna conecntration
		//location of dna extract in freezer
		//person who did extract
		//if exists
		//if original sample exists
		
	//RNA
		//dna extraction date
		//dna extraction kit
		//dna concentration
		//volumne of dna elution
		//instrument used to measure dna concentration
		//volume of dna used to measure dna conecntration
		//location of dna extract in freezer
		//person who did extract
		//if exists
		//if original sample exists
	//Analysis
		//name of analysis pipeline
	//User Things
	//Notes
		//notes
				
			
		
	    
?>