<?php	

//display table
function build_table_tab($stmt,$table_type){ //table types are 'dislapy' and 'xls'
	include($_SESSION['include_path'].'functions/convert_time.php');
	include($_SESSION['include_path'].'functions/convert_header_names.php');
	include($_SESSION['include_path'].'functions/find_samplers.php');
	include($_SESSION['include_path'].'functions/find_sensors.php');
	include($_SESSION['include_path'].'functions/find_thing_labels.php');
	
	$sample_array = array();
	$thing_label_array = find_thing_labels();
	
	if ($stmt->execute()){
			$myfile = fopen("nanolims_export.txt", "w") or die("Unable to open file!");
			/* bind variables to prepared statement */
			$stmt->bind_result($sample_name,$sample_sort,$barcode,$project_name,$location,$relative_location,$media_type,$collector_name,$sample_type,$start_time,$end_time,$total_time,$entered_by,$updated_by,$time_stamp
			,$dna_extraction_date,$dna_extraction_kit,$dna_concentration,$dna_volume_of_elution,$dna_instrument,$dna_vol_for_instrument,$dna_storage,$dna_extractor,$dna_exists,$orig_exists
			,$rna_extraction_date,$rna_extraction_kit,$rna_concentration,$rna_volume_of_elution,$rna_instrument,$rna_vol_for_instrument,$rna_storage,$rna_extractor,$rna_exists,$orig_exists
			,$analysis_name
			,$notes
			);
			
			$counter = 0;
			while ($stmt->fetch()) {
				$counter++;
				
				if($counter == 1){
					//headers
					fwrite($myfile, "Sample Name\t");
					fwrite($myfile, "Sample Sort\t");
					fwrite($myfile, "Barcode\t");
					fwrite($myfile, "Project Name\t");
					fwrite($myfile, "Location\t");
					fwrite($myfile, "Relative Location\t");
					fwrite($myfile, "Media Type\t");
					fwrite($myfile, "Collector Name(s)\t");
					fwrite($myfile, "Sample Type\t");
					fwrite($myfile, "Start Date/Time\t");
					fwrite($myfile, "Sampling Duration\t");
					fwrite($myfile, "Samplers\t");
					
					fwrite($myfile, "DNA Extraction Date (YYYY-MM-DD)\t");
					fwrite($myfile, "DNA Extraction Kit\t");
					fwrite($myfile, "DNA Concentration (ng/uL)\t");
					fwrite($myfile, "DNA Volume of Elution (uL)\t");
					fwrite($myfile, "DNA Quantification Instrument\t");
					fwrite($myfile, "DNA Vol. Used for Quantification (uL)\t");
					fwrite($myfile, "DNA Storage Location\t");
					fwrite($myfile, "DNA Performed By\t");
					fwrite($myfile, "DNA Extraction Exists\t");
					fwrite($myfile, "DNA Extraction Exists\t");
					fwrite($myfile, "Original Sample Exists\t");
					
					fwrite($myfile, "RNA Extraction Date (YYYY-MM-DD)\t");
					fwrite($myfile, "RNA Extraction Kit\t");
					fwrite($myfile, "RNA Concentration (ng/uL)\t");
					fwrite($myfile, "RNA Volume of Elution (uL)\t");
					fwrite($myfile, "RNA Quantification Instrument\t");
					fwrite($myfile, "RNA Vol. Used for Quantification (uL)\t");
					fwrite($myfile, "RNA Storage Location\t");
					fwrite($myfile, "RNA Performed By\t");
					fwrite($myfile, "RNA DNA Extraction Exists\t");
					fwrite($myfile, "RNA Extraction Exists\t");
					fwrite($myfile, "Original Sample Exists\t");
							
					fwrite($myfile, "Analysis Name\t");
					fwrite($myfile, "Notes\t");
					
					fwrite($myfile, "\n");
				}
				
				
				//data
				$key = 'total_time';
				$converted_total_time = convert_time($key, $total_time);
				$samplers = find_samplers($sample_name,'xls');
					
					
				fwrite($myfile, "$sample_name\t");
				fwrite($myfile, "$sample_sort\t");
				fwrite($myfile, "$barcode\t");
				fwrite($myfile, "$project_name\t");
				fwrite($myfile, "$location\t");
				fwrite($myfile, "$relative_location\t");
				fwrite($myfile, "$media_type\t");
				fwrite($myfile, "$collector_name\t");
				fwrite($myfile, "$sample_type\t");
				fwrite($myfile, "$start_time\t");
				fwrite($myfile, "$converted_total_time\t");
				fwrite($myfile, "$samplers\t");
				
				
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
			
				fwrite($myfile, "$dna_extraction_date\t");
				fwrite($myfile, "$dna_extraction_kit\t");
				fwrite($myfile, "$dna_concentration\t");
				fwrite($myfile, "$dna_volume_of_elution\t");
				fwrite($myfile, "$dna_instrument\t");
				fwrite($myfile, "$dna_vol_for_instrument\t");
				fwrite($myfile, "$dna_storage\t");
				fwrite($myfile, "$dna_extractor\t");
				fwrite($myfile, "$dna_exists\t");
				fwrite($myfile, "$orig_exists\t");
				
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
				 
				fwrite($myfile, "$rna_extraction_date\t");
				fwrite($myfile, "$rna_extraction_kit\t");
				fwrite($myfile, "$rna_concentration\t");
				fwrite($myfile, "$rna_volume_of_elution\t");
				fwrite($myfile, "$rna_instrument\t");
				fwrite($myfile, "$rna_vol_for_instrument\t");
				fwrite($myfile, "$rna_storage\t");
				fwrite($myfile, "$rna_extractor\t");
				fwrite($myfile, "$rna_exists\t");
				fwrite($myfile, "$orig_exists\t");
				
				fwrite($myfile, "$analysis_name\t");
				fwrite($myfile, "$notes\t");
	
				fwrite($myfile, "\n");
			}

			
			fclose($myfile);
			
			
			/*Create user thing document*/
			$myThingFile = fopen("nanolims_user_created_export.txt", "w") or die("Unable to open file!");
			fwrite($myThingFile, "Sample Name\t");
			fwrite($myThingFile, "Sample Sort\t");

			foreach($thing_label_array as $key => $value){
				$id_label = explode("|",$value);
				$label =$id_label[1];
				fwrite($myThingFile, "$label\t");
			}
			fwrite($myThingFile, "\n");

			foreach($sample_array as $sample_key => $sample_name){					
				fwrite($myThingFile, "$sample_name\t");
				fwrite($myThingFile, "$sample_key\t");
				foreach($thing_label_array as $key => $value){
					$id_label = explode("|",$value);
					$id =$id_label[0];
					$thing_value = find_thing_values($sample_name, $id);
					fwrite($myThingFile, "$thing_value\t");
				}
				fwrite($myThingFile, "\n");
			}


			fclose($myThingFile);
		}
}	

			
?>