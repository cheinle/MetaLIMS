<?php	

//display table
function build_query_results_table($stmt,$table_type,$dbc){ //table types are 'dislapy' and 'xls'
	include($_SESSION['include_path'].'functions/convert_time.php');
	include($_SESSION['include_path'].'functions/find_samplers.php');

	

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
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
				,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
				,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
				,$analysis_name
				,$notes
				);
				
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
				echo "<th>Start Time</th>";
				//echo "<th>End Time</th>";
				echo "<th>Sampling Duration</th>";
				echo "<th>Samplers</th>";
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
				echo "<th>Start Time</th>";
				//echo "<th>End Time</th>";
				echo "<th>Sampling Duration</th>";
				echo "<th>Samplers</th>";
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
					// $end_time = htmlspecialchars($end_time);
					 $total_time = htmlspecialchars($total_time);
					 
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
					 echo "<td>$start_time</td>";
					// echo "<td>$end_time</td>";
					 echo "<td>$converted_total_time</td>"; 
					  echo "<td>$samplers</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			
		echo "</div>"; //end div fragment
		echo "<div id=\"fragment-2\">";
		
		$stmt->execute();
				/* bind variables to prepared statement */
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
				,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
				,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
				,$analysis_name
				,$notes
				);
				
		 		echo "<table id=\"datatable2\" class=\"display\" cellspacing=\"0\" width=\"100%\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Extraction Date (YYYY-MM-DD)</th>";
				echo "<th>Extraction Kit</th>";
				echo "<th>Concentration (ng/uL)</th>";
				echo "<th>Volume of Elution (uL)</th>";
				echo "<th>Quantification Instrument</th>";
				echo "<th>Vol. Used for Quantification (uL)</th>";
				echo "<th>Storage Location</th>";
				echo "<th>Performed By</th>";
				echo "<th>DNA Extraction Exists</th>";
				echo "<th>Original Sample Exists</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tfoot>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Extraction Date (YYYY-MM-DD)</th>";
				echo "<th>Extraction Kit</th>";
				echo "<th>Concentration (ng/uL)</th>";
				echo "<th>Volume of Elution (uL)</th>";
				echo "<th>Quantification Instrument</th>";
				echo "<th>Vol. Used for Quantification (uL)</th>";
				echo "<th>Storage Location</th>";
				echo "<th>Performed By</th>";
				echo "<th>DNA Extraction Exists</th>";
				echo "<th>Original Sample Exists</th>";
				echo "</tr>";
				echo "</tfoot>";
				
				echo "<tbody>";
				
				/* fetch values */
				while ($stmt->fetch()) {
					 $sample_name = htmlspecialchars($sample_name);
					 $sample_sort = htmlspecialchars($sample_sort);
					 $dna_extraction_date = htmlspecialchars($dna_extraction_date);
					 $dna_extraction_kit= htmlspecialchars($dna_extraction_kit);
				     $dna_concentration = htmlspecialchars($dna_concentration);
				     $dna_volume_of_elution = htmlspecialchars($dna_volume_of_elution);
				     $dna_instrument = htmlspecialchars($dna_instrument);
					 $dna_vol_for_instrument = htmlspecialchars($dna_vol_for_instrument);
					 $dna_storage = htmlspecialchars($dna_storage);
					 $dna_extractor = htmlspecialchars($dna_extractor);
					 $dna_exists = htmlspecialchars($dna_exists);
					 $orig_exists = htmlspecialchars($orig_exists);
					 
					 if($orig_exists == 'true'){
					 	$orig_exists = 'Y';
					 }else{
					 	$orig_exists = 'N';
					 }
					 
					 if($dna_exists == 'one'){
					 	$dna_exists = 'Yes';
					 }elseif($dna_exists == 'two'){
					 	$dna_exists = "No,Not Extracted";
					 }else{
					 	$dna_exists = "No,Extract Used";
					 }
					 
					 
					 echo "<tr>";
					 echo "<td>$sample_name</td>";
					 echo "<td>$sample_sort</td>";
					 echo "<td>$dna_extraction_date</td>";
					 echo "<td>$dna_extraction_kit</td>";
					 echo "<td>$dna_concentration</td>";
					 echo "<td>$dna_volume_of_elution</td>";
					 echo "<td>$dna_instrument</td>";
				     echo "<td>$dna_vol_for_instrument</td>";
				     echo "<td>$dna_storage</td>";;
					 echo "<td>$dna_extractor</td>"; 
					 echo "<td>$dna_exists</td>";
					 echo "<td> $orig_exists</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		 
		 
		 echo "</div>";//end of fragment 2
		echo "<div id=\"fragment-3\">";
		
			$stmt->execute();
				/* bind variables to prepared statement */
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
				,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
				,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
				,$analysis_name
				,$notes
				);
		 	echo "<table id=\"datatable3\" class=\"display\" cellspacing=\"0\" width=\"100%\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Extraction Date (YYYY-MM-DD)</th>";
				echo "<th>Extraction Kit</th>";
				echo "<th>Concentration (ng/uL)</th>";
				echo "<th>Volume of Elution (uL)</th>";
				echo "<th>Quantification Instrument</th>";
				echo "<th>Vol. Used for Quantification (uL)</th>";
				echo "<th>Storage Location</th>";
				echo "<th>Performed By</th>";
				echo "<th>RNA Extraction Exists</th>";
				echo "<th>Original Sample Exists</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tfoot>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Extraction Date (YYYY-MM-DD)</th>";
				echo "<th>Extraction Kit</th>";
				echo "<th>Concentration (ng/uL)</th>";
				echo "<th>Volume of Elution (uL)</th>";
				echo "<th>Quantification Instrument</th>";
				echo "<th>Vol. Used for Quantification (uL)</th>";
				echo "<th>Storage Location</th>";
				echo "<th>Performed By</th>";
				echo "<th>RNA Extraction Exists</th>";
				echo "<th>Original Sample Exists</th>";
				echo "</tr>";
				echo "</tfoot>";
				
				echo "<tbody>";
				
				/* fetch values */
				while ($stmt->fetch()) {
					 $sample_name = htmlspecialchars($sample_name);
					 $sample_sort = htmlspecialchars($sample_sort);
					 $rna_extraction_date = htmlspecialchars($rna_extraction_date);
					 $rna_extraction_kit= htmlspecialchars($rna_extraction_kit);
				     $rna_concentration = htmlspecialchars($rna_concentration);
				     $rna_volume_of_elution = htmlspecialchars($rna_volume_of_elution);
				     $rna_instrument = htmlspecialchars($rna_instrument);
					 $rna_vol_for_instrument = htmlspecialchars($rna_vol_for_instrument);
					 $rna_storage = htmlspecialchars($rna_storage);
					 $rna_extractor = htmlspecialchars($rna_extractor);
					 $rna_exists = htmlspecialchars($rna_exists);
					 $orig_exists = htmlspecialchars($orig_exists);
					 
					 if($orig_exists == 'true'){
					 	$orig_exists = 'Y';
					 }else{
					 	$orig_exists = 'N';
					 }
					 
					 if($rna_exists == 'one'){
					 	$rna_exists = 'Yes';
					 }elseif($rna_exists == 'two'){
					 	$rna_exists = "No,Not Extracted";
					 }else{
					 	$rna_exists = "No,Extract Used";
					 }
					 
					 
					 echo "<tr>";
					 echo "<td>$sample_name</td>";
					 echo "<td>$sample_sort</td>";
					 echo "<td>$rna_extraction_date</td>";
					 echo "<td>$rna_extraction_kit</td>";
					 echo "<td>$rna_concentration</td>";
					 echo "<td>$rna_volume_of_elution</td>";
					 echo "<td>$rna_instrument</td>";
				     echo "<td>$rna_vol_for_instrument</td>";
				     echo "<td>$rna_storage</td>";;
					 echo "<td>$rna_extractor</td>"; 
					 echo "<td>$rna_exists</td>";
					 echo "<td> $orig_exists</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
		 echo "</div>"; //end of fragment 3
		 echo "<div id=\"fragment-4\">";
		
			$stmt->execute();
				/* bind variables to prepared statement */
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
				,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
				,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
				,$analysis_name
				,$notes
				);
				
				
				echo "<table id=\"datatable4\" class=\"display\" cellspacing=\"0\" width=\"100%\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Analysis Name</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tfoot>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Analysis Name</th>";
				echo "</tr>";
				echo "</tfoot>";
				
				echo "<tbody>";
				
				/* fetch values */
				while ($stmt->fetch()) {
					 $sample_name = htmlspecialchars($sample_name);
					 $sample_sort = htmlspecialchars($sample_sort);
					 $analysis_name = htmlspecialchars($analysis_name);
			
					 
					 echo "<tr>";
					 echo "<td>$sample_name</td>";
					 echo "<td>$sample_sort</td>";
					 echo "<td>$analysis_name</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
				
				
		echo "</div>"; //end of fragment 4
		 echo "<div id=\"fragment-6\">";
		
			$stmt->execute();
				/* bind variables to prepared statement */
				$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
				,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
				,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
				,$analysis_name
				,$notes
				);
				
				echo "<table id=\"datatable6\" class=\"display\" cellspacing=\"0\" width=\"100%\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Notes</th>";
				echo "<th>Entered By</th>";
				echo "<th>Updated By</th>";
				echo "<th>Time Stamp</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tfoot>";
				echo "<tr>";
				echo "<th>Sample Name</th>";
				echo "<th>Sample Sort</th>";
				echo "<th>Notes</th>";
				echo "<th>Entered By</th>";
				echo "<th>Updated By</th>";
				echo "<th>Time Stamp</th>";
				echo "</tr>";
				echo "</tfoot>";
				
				echo "<tbody>";
				
				/* fetch values */
				while ($stmt->fetch()) {
					 $sample_name = htmlspecialchars($sample_name);
					 $sample_sort = htmlspecialchars($sample_sort);
					 $notes = htmlspecialchars($notes);
					 $entered_by = htmlspecialchars($entered_by);
					 $updated_by = htmlspecialchars($updated_by);
					 $time_stamp = htmlspecialchars($time_stamp);
			
					 
					 echo "<tr>";
					 echo "<td>$sample_name</td>";
					 echo "<td>$sample_sort</td>";
					 echo "<td>$notes</td>";
					 echo "<td>$entered_by</td>";
					 echo "<td>$updated_by</td>";
					 echo "<td>$time_stamp</td>";
					 echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
				
				
		echo "</div>"; //end of fragment 6
	echo "</div>"; //end of tabs
	echo "<script>$( \"#tabs\" ).tabs();</script>";
	
	
	
	
		/* close statement */
				$stmt->close();
				/* close connection */
				$dbc->close();
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