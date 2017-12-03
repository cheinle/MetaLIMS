<?php

function bulk_sample_insert_parse($ext,$file,$randomString,$path,$dbc){


	//$myErrorFile = fopen("example/sensor_data_upload_error.txt", "w") or die("Unable to open error file!");
	//$myOutFile = fopen("uploads/process_bulk_sample_insert_output_".$randomString.".txt", "w") or die("Unable to open output file!");
	//$myErrorFile = fopen("uploads/process_bulk_sample_insert_error_".$randomString.".txt", "w") or die("Unable to open error file!");
		//**UNLINK FILES on logout?**//
	
	$myOutFile = fopen("uploads/process_bulk_sample_insert_output.txt", "w") or die("Unable to open output file!");
	$myErrorFile = fopen("uploads/process_bulk_sample_insert_error.txt", "w") or die("Unable to open error file!");
	
	//  Include PHPExcel_IOFactory
	require_once ($path.'acquired/xls_classes/PHPExcel/IOFactory.php');

	$inputFileName = $file;
	//$insert_check = 'false';
	$insert_check = 'true';

	//  Read your Excel workbook
	try {
		//start transaction
		$dbc->autocommit(FALSE);
			
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($inputFileName);
	
	
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		
		//echo "Highest Row: $highestRow <br>";
		//echo "Highest Column: $highestColumn <br>";
		
		//  Loop through each row of the worksheet in turn
		$sample_array = array();
		for ($row = 2; $row <= $highestRow; $row++){ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    TRUE);
			// Process and check data
			$orig_time_stamp = '';
			$entered_by = '';
			$total_sampling_time = '';
			$sample_sort = '';
			$seq_id = '';
			$sample_name = '';
			
			// Check basic sample table data
				// Check data exists
				// Build appropriate extra data above
				//print_r($rowData);
			$sample_number = trim($rowData[0][0]);
			$unformatted_sample_number = $sample_number;
			if(strlen ($sample_number) == 1){
				$sample_number = '00'.$sample_number;
			}elseif(strlen ($sample_number) == 2){
				$sample_number = '0'.$sample_number;
			}elseif(strlen ($sample_number) == 3){
				$sample_number = $sample_number;
			}else{
				fwrite($myErrorFile,"Insert Failure: sample number ".$sample_number." is a required field and cannot be greater than 3-digits".PHP_EOL);
				$insert_check = 'false';
			}
			
			$barcode = trim($rowData[0][1]);
			$project_name = trim($rowData[0][2]);
			$location = trim($rowData[0][3]);
			$relative_location = trim($rowData[0][4]);
			$media_type = trim($rowData[0][5]);
			$collector_names = trim($rowData[0][6]);
			$sample_type = trim($rowData[0][7]);
			$notes = trim($rowData[0][35]);
			
			
			if($project_name == ''){
				fwrite($myErrorFile,"Insert Failure: project name is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($location == ''){
				fwrite($myErrorFile,"Insert Failure: location is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($relative_location == ''){
				fwrite($myErrorFile,"Insert Failure: relative location is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($media_type == ''){
				fwrite($myErrorFile,"Insert Failure: media type is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($collector_names == ''){
				fwrite($myErrorFile,"Insert Failure: collector names is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($sample_type == ''){
				fwrite($myErrorFile,"Insert Failure: sample type is a required field. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			if($rowData[0][11] == '' || $rowData[0][12] == '' || $rowData[0][13] == '' ||$rowData[0][14] == '' ){
				fwrite($myErrorFile,"Insert Failure: sampler name1 and date/times1 required fields. Please enter and retry".PHP_EOL);
				$insert_check = 'false';
			}
			
			
			
			// Check project name exists
			$stmt = $dbc->prepare("SELECT project_name FROM project_name WHERE project_name = ? AND visible = 1");
			$stmt->bind_param("s", $project_name);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows <= 0){
		    		fwrite($myErrorFile,"Insert Failure: Project ".$project_name." does not exist. Please manually enter into database and retry".PHP_EOL);
					$insert_check = 'false';
		    		//throw new Exception("Insert Failure: Project ".$project_name." does not exist. Please manually enter into database");	
		    	}
				
			} 
			$stmt -> close();

			
			// Check location exists
			$stmt = $dbc->prepare("SELECT loc_name FROM location WHERE loc_name = ? AND visible = 1");
			$stmt->bind_param("s", $location);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows <= 0){
		    		fwrite($myErrorFile,"Insert Failure: Location ".$location." does not exist. Please manually enter into database and retry".PHP_EOL);
					$insert_check = 'false';
		    		//throw new Exception("Insert Failure: Location ".$location." does not exist. Please manually enter into database");	
		    	}
				
			} 
			$stmt -> close();
			
			// Check relative location exists
			$stmt = $dbc->prepare("SELECT loc_name FROM relt_location WHERE loc_name = ? AND visible = 1");
			$stmt->bind_param("s", $relative_location);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows <= 0){
		    		fwrite($myErrorFile,"Insert Failure: Relative Location ".$relative_location." does not exist. Please manually enter into database and retry".PHP_EOL);
		    		$insert_check = 'false';
		    		//throw new Exception("Insert Failure: Relative Location ".$relative_location." does not exist. Please manually enter into database");	
		    	}
				
			} 
			$stmt -> close();
			
			// Check media type exists
			$stmt = $dbc->prepare("SELECT media_type FROM media_type WHERE media_type = ? AND visible = 1");
			$stmt->bind_param("s", $media_type);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows <= 0){
		    		fwrite($myErrorFile,"Insert Failure: Media Type ".$media_type." does not exist. Please manually enter into database and retry".PHP_EOL);
					$insert_check = 'false';
		    		//throw new Exception("Insert Failure: Media Type ".$media_type." does not exist. Please manually enter into database");	
		    	}
				
			} 
			$stmt -> close();
			
			// Check sample type exists
			$sample_type_id = '';
			$stmt = $dbc->prepare("SELECT sample_type_id FROM sample_type WHERE sample_type_name = ? AND visible = 1");
			$stmt->bind_param("s", $sample_type);		
			if ($stmt->execute()){
				$stmt->bind_result($stype_id);
				if($stmt->fetch()){
		    		$sample_type_id = $stype_id;
		    	}
				
			} 
			$stmt -> close();
			
			if($sample_type_id == ''){
				fwrite($myErrorFile,"Insert Failure: Sample Type '".$sample_type."' does not exist. Please manually enter into database and retry".PHP_EOL);
				$insert_check = 'false';
			}
			

			// Check sample numeber doesn't already exist for project
			$stmt = $dbc->prepare("SELECT sample_name FROM sample WHERE project_name = ? AND sample_num = ?");
			$stmt->bind_param("ss", $project_name,$sample_number);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows > 0){
		    		fwrite($myErrorFile,"Insert Failure: Sample Number ".$sample_number." exists for project ".$project_name." .Please check sample number ".PHP_EOL);
		    		$insert_check = 'false';
		    		//throw new Exception("Insert Failure: Sample Number ".$sample_number." exists for project ".$project_name);
		    	}
				
			} 
			$stmt -> close();
			
			
			//echo "<br>INSERT CHECK $insert_check<br><br>";
			// Format sample name and sample sort name
			$date = trim($rowData[0][12]); //just using start date for sampling date of the first sampler
			$date_object = DateTime::createFromFormat('d-M-y', $date);
			$reformatted_date = $date_object->format('Y/m/d'); //must change to this date format first for date_create to read.

			
			//$regrex_check = '/^(20[0-9][0-9])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes
			//preg_match($regrex_check,$date,$matches);
			//$date = $matches[1].'/'.$matches[2].'/'.$matches[3];
			$date = $reformatted_date;
			$sample_name = $date.$project_name.$sample_type_id.$sample_number;
			$sample_sort = $project_name.$unformatted_sample_number;
			
			$sample_array[$sample_sort] = $sample_name; //for dna processing
			
			
			//Grab abbreviated project name to create new ID for sequencing submission
			$stmt_sid= $dbc->prepare("SELECT seq_id_start FROM project_name WHERE project_name = ?");
			$stmt_sid -> bind_param('s', $project_name);
				
  			if ($stmt_sid->execute()){
    			$stmt_sid->bind_result($name);
    			if ($stmt_sid->fetch()){
        			$seq_id = $name.$sample_number;
				}
				else {
					$insert_check = 'false';
					fwrite($myErrorFile,"ERROR: Unable to get sequencing ID of project name. Please see admin ".PHP_EOL);
		    		
				}
			} 
			else {
				$insert_check = 'false';
				fwrite($myErrorFile,"ERROR: Unable to get sequencing ID of project name. Please see admin ".PHP_EOL);
			}
			$stmt_sid -> close();
			
			// Get current time stamp. This will be original timestamp
			$t = time();
			$orig_time_stamp = date("Y-m-d H:i:s",$t);
		
			// Get username and update entered by with
			$entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
			
			if($insert_check = 'true'){
				//Insert data into db. Use prepared statement 
				$stmt2 = $dbc -> prepare("INSERT INTO sample (
														sample_name,
														sample_num,
														barcode,
														project_name,
														location_name,
														relt_loc_name,
														media_type,
														collector_name, 
														sample_type,
														notes,
														orig_time_stamp,
														entered_by,
														sample_sort,
														seq_id
														) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
							
				if(!$stmt2){
					$insert_check = 'false';
					throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
				}else{
					$stmt2 -> bind_param('sissssssssssss',$sample_name,$sample_number,$barcode,$project_name,$location,$relative_location,$media_type,$collector_names,$sample_type_id,$notes,$orig_time_stamp,$entered_by,$sample_sort,$seq_id);
					if(!$stmt2 -> execute()){
						$insert_check = 'false';
						fwrite($myErrorFile,"Execution Failure: Unable To Insert Into Main Sample Table ".PHP_EOL);
						throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
					}
				}	
			}
			
			
			// If sample name was able to be built, check freezer and drawer exist and put insert to freezer_drawer table
			$freezer = trim($rowData[0][8]);
			//$freezer = ltrim($freezer,"'");
			$drawer = trim($rowData[0][9]);
			//$drawer = ltrim($drawer,"'");
			$sample_exists = trim($rowData[0][10]);
			
			// Check if freezer and drawer exist
			$stmt = $dbc->prepare("SELECT drawer_id, freezer_id FROM freezer_drawer WHERE drawer_id = ? AND freezer_id = ? AND visible_flag = 1");
			$stmt->bind_param("ss", $drawer,$freezer);		
			if ($stmt->execute()){
				$stmt->store_result();
		    	if($stmt->num_rows <= 0){
		    		fwrite($myErrorFile,"Insert Failure: Freezer ".$freezer." & Drawer Name ".$drawer." do not exist. Please enter manually or check spelling".PHP_EOL);
		    		$insert_check = 'false';
		    	}
			} 
			$stmt -> close();
			
			
			// Insert sample_name into storage_info table  with 'original' storage info
			$storage = $freezer.','.$drawer;
			if($sample_exists == 'Y' || $sample_exists == 'y'){
				$sample_exists = 'true';
			}elseif($sample_exists == 'N' || $sample_exists == 'n'){
				$sample_exists = 'false';
			}else{
				$insert_check = 'false';
				fwrite($myErrorFile,"Insert Failure: Sample Exists flag must be Y/y or N/n".PHP_EOL);
			}
			
			if($insert_check == 'true'){
				$stmt3 = $dbc -> prepare("INSERT INTO storage_info (sample_name,original,orig_sample_exists) VALUES (?,?,?)");
				$stmt3 -> bind_param('sss', $sample_name,$storage,$sample_exists);		
				$stmt3 -> execute();
				$rows_affected3 = $stmt3 ->affected_rows;
				if($rows_affected3 <= 0){
					$insert_check = 'false';
					fwrite($myErrorFile,"Insert Failure: unable to insert storage info".PHP_EOL);
				}		
				$stmt3 -> close();
			}
			
			
			// Check sampler names exist 
			// Insert samplers and times
			// Calculate total time
			$my_samplers = array();
			$start_times = array();
			$end_times = array();
			$start_dates = array();
			$end_dates = array();
			
			$num_of_my_samplers = 6;
			
			$sampler1 = trim($rowData[0][11]);
			$sampler_sdate1 = trim($rowData[0][12]);
			$sampler_edate1 = trim($rowData[0][13]);
			$sampler_time1 = trim($rowData[0][14]);
			$times = explode("-",$sampler_time1);
			$sampler_stime1 = trim($times[0]);
			$sampler_etime1 = trim($times[1]);
			
			if($sampler1 != '' && $sampler_sdate1 != ''  && $sampler_edate1 != ''  && $sampler_time1 != '' && $sampler_etime1 != ''){
				$my_samplers[1] = $sampler1;
				$start_dates[1] = $sampler_sdate1;
				$end_dates[1] = $sampler_edate1;
				$start_times[1] = $sampler_stime1;
				$end_times[1] = $sampler_etime1;

			}else{
				echo "$sampler1 strt $sampler_sdate1 end $sampler_edate1 $sampler_time1<br>";
			}
			
			// Sampler2
			$sampler2 = trim($rowData[0][15]);
			$sampler_sdate2 = trim($rowData[0][16]);
			$sampler_edate2 = trim($rowData[0][17]);
			$sampler_time2 = trim($rowData[0][18]);
			
			if($sampler2 != '' && $sampler_sdate2 != ''  && $sampler_edate2 != ''  && $sampler_time2 != ''){
				$times2 = explode("-",$sampler_time2);
				$sampler_stime2 = trim($times2[0]);
				$sampler_etime2 = trim($times2[1]);
			
				$my_samplers[2] = $sampler2;
				$start_dates[2] = $sampler_sdate2;
				$end_dates[2] = $sampler_edate2;
				$start_times[2] = $sampler_stime2;
				$end_times[2] = $sampler_etime2;
			}
			
			// Sampler3
			$sampler3 = trim($rowData[0][19]);
			$sampler_sdate3 = trim($rowData[0][20]);
			$sampler_edate3 = trim($rowData[0][21]);
			$sampler_time3 = trim($rowData[0][22]);
			
			if($sampler3 != '' && $sampler_sdate3 != ''  && $sampler_edate3 != ''  && $sampler_time3 != ''){
			
				$times3 = explode("-",$sampler_time3);
				$sampler_stime3 = trim($times3[0]);
				$sampler_etime3 = trim($times3[1]);

				$my_samplers[3] = $sampler3;
				$start_dates[3] = $sampler_sdate3;
				$end_dates[3] = $sampler_edate3;
				$start_times[3] = $sampler_stime3;
				$end_times[3] = $sampler_etime3;
			}

			// Sampler4
			$sampler4 = trim($rowData[0][23]);
			$sampler_sdate4 = trim($rowData[0][24]);
			$sampler_edate4 = trim($rowData[0][25]);
			$sampler_time4 = trim($rowData[0][26]);
			
			if($sampler4 != '' && $sampler_sdate4 != ''  && $sampler_edate4 != ''  && $sampler_time4 != ''){
				$times4 = explode("-",$sampler_time4);
				$sampler_stime4 = trim($times4[0]);
				$sampler_etime4 = trim($times4[1]);
			
				$my_samplers[4] = $sampler4;
				$start_dates[4] = $sampler_sdate4;
				$end_dates[4] = $sampler_edate4;
				$start_times[4] = $sampler_stime4;
				$end_times[4] = $sampler_etime4;
			}
			
			// Sampler5
			$sampler5 = trim($rowData[0][27]);
			$sampler_sdate5 = trim($rowData[0][28]);
			$sampler_edate5 = trim($rowData[0][29]);
			$sampler_time5 = trim($rowData[0][30]);
			
			if($sampler5 != '' && $sampler_sdate5 != ''  && $sampler_edate5 != ''  && $sampler_time5 != '' ){
				$times5 = explode("-",$sampler_time5);
				$sampler_stime5 = trim($times5[0]);
				$sampler_etime5 = trim($times5[1]);
			
				$my_samplers[5] = $sampler5;
				$start_dates[5] = $sampler_sdate5;
				$end_dates[5] = $sampler_edate5;
				$start_times[5] = $sampler_stime5;
				$end_times[5] = $sampler_etime5;
			}
			
			// Sampler6
			$sampler6 = trim($rowData[0][31]);
			$sampler_sdate6 = trim($rowData[0][32]);
			$sampler_edate6 = trim($rowData[0][33]);
			$sampler_time6 = trim($rowData[0][34]);
			
			if($sampler6 != '' && $sampler_sdate6 != ''  && $sampler_edate6 != ''  && $sampler_time6 != ''){
			
				$times6 = explode("-",$sampler_time6);
				$sampler_stime6 = trim($times6[0]);
				$sampler_etime6 = trim($times6[1]);

				$my_samplers[6] = $sampler6;
				$start_dates[6] = $sampler_sdate6;
				$end_dates[6] = $sampler_edate6;
				$start_times[6] = $sampler_stime6;
				$end_times[6] = $sampler_etime6;
			}
			
						
			$earliest_start = '';
			$latest_end = '';
			$counter = 0;
		
			$sampler_check = array();
			for ($x = 1; $x <= $num_of_my_samplers; $x++) {
				if(!isset($my_samplers[$x])){
					continue;
				}
				$counter++;
				
				if (in_array($my_samplers[$x], $sampler_check)){
				  throw new Exception("Insert Failure: Sampler names must be unique: ".$my_samplers[$x]." appears more than once for sample $sample_name");
				}else{
					$sampler_check[] = $my_samplers[$x];
				}
				
				//Convert dates
				$date_format = 'd-M-y'; 
				$date_object_start = DateTime::createFromFormat($date_format, $start_dates[$x]);
				$start_dates[$x] = $date_object_start->format('Y-m-d'); 
				
				$date_object_end = DateTime::createFromFormat($date_format, $end_dates[$x]);
				$end_dates[$x] = $date_object_end->format('Y-m-d'); 
				
				
				if($start_dates[$x] == '' || $start_times[$x] == '' || $end_dates[$x] == '' || $end_times[$x] == '' ){
					fwrite($myErrorFile,"Insert Failure: Please check date and times are correct format DD-Month-YY HH:mm Ex:  27-Nov-17 13:30".PHP_EOL);
					$insert_check = 'false';
				}

				$p_my_samp_name = htmlspecialchars($my_samplers[$x]);
				$start = $start_dates[$x].' '.$start_times[$x];
				$end = $end_dates[$x].' '.$end_times[$x];
				
				// Check sampler exists			
				$stmt = $dbc->prepare("SELECT sampler_name FROM sampler WHERE sampler_name = ? AND visible = 1");
				$stmt->bind_param("s", $p_my_samp_name);		
				if ($stmt->execute()){
					$stmt->store_result();
			    	if($stmt->num_rows <= 0){
			    		fwrite($myErrorFile,"Insert Failure: Sampler $x: ".$p_my_samp_name." does not exist. Please manually enter into database and retry".PHP_EOL);
						$insert_check = 'false';	
			    	}
				} 
				$stmt -> close();
			
				
				//check if you are a blank. If you are then make sampling time zero
				if($sample_type_id == 'B'){
					$end = $start;
				}
				
				if($counter == 1){
					$earliest_start = $start;
					$latest_end = $end;
				}
				else{
					//check starts
					if($start < $earliest_start){
						$earliest_start = $start;
					}
					
					//check ends
					if($end > $latest_end){
						$latest_end = $end;
					}
				}
				
				//format date/time
				$p_time;
				if(($start) && ($end)){
					$ts1 = strtotime($start);
					$ts2 = strtotime($end);

					$seconds_diff = $ts2 - $ts1;
					
					$time = ($seconds_diff/3600);
					$p_time = round($time,4);
				}
				
				if($insert_check == 'true'){
					$p_my_samp_name = trim($p_my_samp_name);
					$query_my_samp = "INSERT INTO sample_sampler (sample_name, sampler_name, start_date_time,end_date_time,total_date_time) VALUES (?,?,?,?,?)";
					$stmt_my_samp = $dbc -> prepare($query_my_samp);
					if(!$stmt_my_samp){
						throw new Exception("Prepare Failure: Unable To Insert Sample Sampler");	
					}
					else{
						$stmt_my_samp -> bind_param('ssssd', $sample_name,$p_my_samp_name,$start,$end,$p_time);
						if($stmt_my_samp -> execute()){
							$rows_affected_my_samp = $stmt_my_samp ->affected_rows;
							$stmt_my_samp -> close();
							//check if add was successful or not. Tell the user
					   		if($rows_affected_my_samp <= 0){
								$insert_check = 'false';
								fwrite($myErrorFile, "An Error Occurred: No sampler info added. Please check date/time format: $sample_name $p_my_samp_name $start $end".PHP_EOL);
								$insert_check = 'false';
							}
						}
						else{
							echo mysqli_error($dbc);
							fwrite($myErrorFile, "An Error Occurred: No sampler info added. Please check date/time format: $sample_name $p_my_samp_name $start $end".PHP_EOL);
							$insert_check = 'false';
						}
					}
				}
			}

			//echo 'earliest and latest'.$earliest_start.' '.$latest_end.'<br>';
			//format largest sampling period for samplers run at the same time period
			//update sample table with this new time
			$p_biggest_time;
			if(($start) && ($end)){
					$bts1 = strtotime($earliest_start);
					$bts2 = strtotime($latest_end);

					$big_seconds_diff = $bts2 - $bts1;
					
					$big_time = ($big_seconds_diff/3600);
					$p_biggest_time = round($big_time,2);
			}
			
			if($earliest_start == '' || $latest_end == ''){
				$insert_check = 'false';
				fwrite($myErrorFile,"Insert Failure: problem obtaining start".PHP_EOL);
			}
			$time_query = "UPDATE sample SET start_samp_date_time = ?, end_samp_date_time = ?, total_samp_time = ? WHERE sample_name = ?";
			if($time_stmt = $dbc ->prepare($time_query)) {                 
            	$time_stmt->bind_param('ssds',$earliest_start,$latest_end,$p_biggest_time,$sample_name);
		
                if($time_stmt -> execute()){
					$time_rows_affected = $time_stmt ->affected_rows;
				
					$time_stmt -> close();
					if($time_rows_affected < 1){	
						$insert_check = 'false';
						//throw new Exception("Insert Failure: Unable to insert sampler");
						fwrite($myErrorFile,"Insert Failure: Unable To insert sampler".PHP_EOL);
					}
				}
				else{
					$insert_check = 'false';
					//throw new Exception("Execution Failure: Unable To Insert Sampler");
					fwrite($myErrorFile,"Execution Failure: Unable to insert sampler".PHP_EOL);
				}
			}
			else{
				$insert_check = 'false';
				fwrite($myErrorFile,"Prepare Failure: Unable to insert sampler".PHP_EOL);
				//throw new Exception("Prepare Failure: Unable to insert sampler");
			}


			// Insert sample name for remaining tables...?
			$stmt_seq_num = $dbc -> prepare("INSERT INTO number_of_seq_submissions (sample_name) VALUES (?)");
			if(!$stmt_seq_num){
					$insert_check = 'false';
					//throw new Exception("Prepare Failure: Unable to insert sample into Sequence Number Submission table");	
					fwrite($myErrorFile,"Prepare Failure: Unable to enter sample into Sequence Number Submission table1.".PHP_EOL);
			}
			else{
				$stmt_seq_num -> bind_param('s', $sample_name);
				if(!$stmt_seq_num-> execute()){
					$insert_check = 'false';
					fwrite($myErrorFile,"Execution Failure: Unable to enter sample into Sequence Number Submission table2.".PHP_EOL);
					//throw new Exception("Execution Failure: Unable to enter sample into Sequence Number Submission table.");	
				}
				else{
					$rows_affected_seq_num = $stmt_seq_num ->affected_rows;
					$stmt_seq_num -> close();
					if($rows_affected_seq_num < 0){
						$insert_check = 'false';
						fwrite($myErrorFile, "Unable to insert sample into Sequence Number Submission table3".PHP_EOL);
						//throw new Exception("Unable to insert sample into Sequence Number Submission table");	
					}
				}
			}
			
			fwrite($myOutFile,"Input sample with sample name: $sample_name ".PHP_EOL);
		}
		/*****************************************************************************
		 * Check for DNA/RNA info sample_array 
		 * ***************************************************************************/
		
		//  Get worksheet dimensions
		$sheet2 = $objPHPExcel->getSheet(1); 
		$highestRow2 = $sheet2->getHighestRow(); 
		$highestColumn2 = $sheet2->getHighestColumn();
		
		//echo "Highest Row2: $highestRow2 <br>";
		//echo "Highest Column2: $highestColumn2 <br>";
		
		//  Loop through each row of the worksheet in turn
		for ($row2 = 2; $row2 <= $highestRow2; $row2++){ 
			//  Read a row of data into an array
			$rowData2 = $sheet2->rangeToArray('A' . $row2 . ':' . $highestColumn2 . $row2,
											NULL,
											TRUE,
											TRUE);
											
			$sample_number = $rowData2[0][0];
			$project_name = $rowData2[0][1];
			
			$dna_counter = 0;
			$rna_counter = 0;
			if($sample_array[$project_name.$sample_number]){
				$sample_name = $sample_array[$project_name.$sample_number];
				
				$dna_extraction_date = $rowData2[0][2];
				if($dna_extraction_date != ''){$dna_counter++;}
				
				$dna_extraction_kit = $rowData2[0][3];
				if($dna_extraction_kit != ''){$dna_counter++;}
				
				$dna_conc = $rowData2[0][4];
				if($dna_conc != ''){$dna_counter++;}
				
				$dna_volume = $rowData2[0][5];
				if($dna_volume != ''){$dna_counter++;}
				
				$dna_conc_instrument = $rowData2[0][6];
				if($dna_conc_instrument != ''){$dna_counter++;}
				
				$dna_volume_quant = $rowData2[0][7];
				if($dna_volume_quant != ''){$dna_counter++;}
				
				$dna_extractor_name = $rowData2[0][8];
				if($dna_extractor_name != ''){$dna_counter++;}
				
				$dna_storage_freezer = $rowData2[0][9];
				if($dna_storage_freezer != ''){$dna_counter++;}
				
				$dna_storage_drawer = $rowData2[0][10];
				if($dna_storage_drawer != ''){$dna_counter++;}
				
				$dna_storage_exists = $rowData2[0][11];
				if($dna_storage_exists != ''){$dna_counter++;}
				
				if($dna_counter > 0 && $dna_counter < 10){
					$insert_check = 'false';
					fwrite($myErrorFile, "All fields for DNA extraction must be filled in. Please check form for sample : $sample_name".PHP_EOL);
				}
				
				if($dna_counter == 10){
					
					$date_format = 'd-M-y'; 
					$date_object_start = DateTime::createFromFormat($date_format, $dna_extraction_date);
					$dna_extraction_date = $date_object_start->format('Y-m-d'); 	
					
					// Check dna_extraction_kit exists
					$stmt = $dbc->prepare("SELECT d_kit_name FROM dna_extraction WHERE d_kit_name = ? AND visible = 1");
					$stmt->bind_param("s", $dna_extraction_kit);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: DNA extraction kit ".$dna_extraction_kit." does not exist. Please manually enter into database and retry".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					// Check dna_conc_instrument exists
					$stmt = $dbc->prepare("SELECT kit_name FROM quant_instruments WHERE kit_name = ? AND visible = 1");
					$stmt->bind_param("s", $dna_conc_instrument);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: DNA Quantitation Instrument ".$dna_conc_instrument." does not exist. Please manually enter into database and retry".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					// Check if numeric
					if(!is_numeric($dna_conc)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: DNA concentration must be a number for sample $sample_name".PHP_EOL);
					}
					if(!is_numeric($dna_volume)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: DNA volume must be a number for sample $sample_name".PHP_EOL);
					}
					if(!is_numeric($dna_volume_quant)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: DNA volume for quantitation must be a number for sample $sample_name".PHP_EOL);
					}
				
					
					// Update sample table for dna info
					$query = "UPDATE sample SET d_extraction_date = ?, dna_extract_kit_name = ?, d_conc = ?, d_volume = ?, d_conc_instrument =?, d_volume_quant = ?, dExtrName = ? WHERE sample_name = ?";
					if($stmt = $dbc ->prepare($query)) {                 
						$stmt->bind_param('ssddsdss',$dna_extraction_date, $dna_extraction_kit, $dna_conc ,$dna_volume,$dna_conc_instrument,$dna_volume_quant ,$dna_extractor_name ,$sample_name);
						if($stmt -> execute()){
							$rows_affected = $stmt ->affected_rows;
							$stmt -> close();
							if($rows_affected < 0){	
									$insert_check = 'false';
									fwrite($myErrorFile,"Insert Failure: Unable to insert DNA information for sample1 $sample_name".PHP_EOL);
								}
							}
						else{
							$insert_check = 'false';
							fwrite($myErrorFile,"Insert Failure: Unable to insert DNA information for sample2 $sample_name".PHP_EOL);
						}
						//$stmt -> close();
					}
					else{
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: Unable to insert DNA information for sample3 $sample_name".PHP_EOL);;
					}
					
					
					// Check if freezer and drawer exist
					$stmt = $dbc->prepare("SELECT drawer_id, freezer_id FROM freezer_drawer WHERE drawer_id = ? AND freezer_id = ? AND visible_flag = 1");
					$stmt->bind_param("ss", $dna_storage_drawer,$dna_storage_freezer);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: Freezer ".$dna_storage_freezer." & Drawer Name ".$dna_storage_drawer." do not exist. Please enter manually or check spelling".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					
					// Insert sample_name into storage_info table  with 'dna' storage info
					$dna_storage = $dna_storage_freezer.','.$dna_storage_drawer;
					if($dna_storage_exists == 'Y' || $dna_storage_exists == 'y'){
						$dna_sample_exists = 'one';
					}elseif($dna_storage_exists == 'N' || $dna_storage_exists == 'n'){
						$dna_sample_exists = 'three';
					}else{
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: Sample Exists flag must be Y/y or N/n".PHP_EOL);
					}
					
					if($insert_check == 'true'){
						$query = "UPDATE storage_info SET dna_extr = ?, DNA_sample_exists = ? WHERE sample_name = ?";
						if($stmt = $dbc ->prepare($query)) {                 
							$stmt->bind_param('sss',$dna_storage,$dna_sample_exists,$sample_name);
							if($stmt -> execute()){
								$rows_affected = $stmt ->affected_rows;
								$stmt -> close();
								if($rows_affected < 0){	
										$insert_check = 'false';
										fwrite($myErrorFile,"Insert Failure: unable to insert DNA information for sample $sample_name".PHP_EOL);
									}
								}
							else{
								$insert_check = 'false';
								fwrite($myErrorFile,"Insert Failure: unable to insert DNA information for sample $sample_name".PHP_EOL);
							}
						}
						else{
							$insert_check = 'false';
							fwrite($myErrorFile,"Insert Failure: unable to insert DNA information for sample $sample_name".PHP_EOL);;
						}
						//$stmt->close();
					}
							
				}
				
				//RNA
				$rna_extraction_date = $rowData2[0][12];
				if($rna_extraction_date != ''){$rna_counter++;}
				
				$rna_extraction_kit = $rowData2[0][13];
				if($rna_extraction_kit != ''){$rna_counter++;}
				
				$rna_conc = $rowData2[0][14];
				if($rna_conc != ''){$rna_counter++;}
				
				$rna_volume = $rowData2[0][15];
				if($rna_volume != ''){$rna_counter++;}
				
				$rna_conc_instrument = $rowData2[0][16];
				if($rna_conc_instrument != ''){$rna_counter++;}
				
				$rna_volume_quant = $rowData2[0][17];
				if($rna_volume_quant != ''){$rna_counter++;}
				
				$rna_extractor_name = $rowData2[0][18];
				if($rna_extractor_name != ''){$rna_counter++;}
				
				$rna_storage_freezer = $rowData2[0][19];
				if($rna_storage_freezer != ''){$rna_counter++;}
				
				$rna_storage_drawer = $rowData2[0][20];
				if($rna_storage_drawer != ''){$rna_counter++;}
				
				$rna_storage_exists = $rowData2[0][21];
				if($rna_storage_exists != ''){$rna_counter++;}
				
				if($rna_counter > 0 && $rna_counter < 10){
					$insert_check = 'false';
					fwrite($myErrorFile, "All fields for RNA extraction must be filled in. Please check form for sample : $sample_name".PHP_EOL);
				}
				
				if($rna_counter == 10){
					
					$date_format = 'd-M-y'; 
					$date_object_start = DateTime::createFromFormat($date_format, $rna_extraction_date);
					$rna_extraction_date = $date_object_start->format('Y-m-d'); 	
					
					// Check rna_extraction_kit exists
					$stmt = $dbc->prepare("SELECT r_kit_name FROM rna_extraction WHERE r_kit_name = ? AND visible = 1");
					$stmt->bind_param("s", $rna_extraction_kit);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: RNA extraction kit ".$rna_extraction_kit." does not exist. Please manually enter into database and retry".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					// Check rna_conc_instrument exists
					$stmt = $dbc->prepare("SELECT kit_name FROM quant_instruments WHERE kit_name = ? AND visible = 1");
					$stmt->bind_param("s", $rna_conc_instrument);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: RNA Quantitation Instrument ".$rna_conc_instrument." does not exist. Please manually enter into database and retry".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					//Check if numeric
					if(!is_numeric($rna_conc)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: RNA concentration must be a number for sample $sample_name".PHP_EOL);
					}
					if(!is_numeric($rna_volume)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: RNA volume must be a number for sample $sample_name".PHP_EOL);
					}
					if(!is_numeric($rna_volume_quant)){
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: RNA volume for quantitation must be a number for sample $sample_name".PHP_EOL);
					}
				
					
					//Update sample table for rna info
					$query = "UPDATE sample SET r_extraction_date = ?, rna_extract_kit_name = ?, r_conc = ?, r_volume = ?, r_conc_instrument =?, r_volume_quant = ?, rExtrName = ? WHERE sample_name = ?";
					if($stmt = $dbc ->prepare($query)) {                 
						$stmt->bind_param('ssddsdss',$rna_extraction_date, $rna_extraction_kit, $rna_conc ,$rna_volume,$rna_conc_instrument,$rna_volume_quant ,$rna_extractor_name ,$sample_name);
						if($stmt -> execute()){
							$rows_affected = $stmt ->affected_rows;
							$stmt -> close();
							if($rows_affected < 0){	
									$insert_check = 'false';
									fwrite($myErrorFile,"Insert Failure: Unable to insert RNA information for sample $sample_name".PHP_EOL);
								}
							}
						else{
							$insert_check = 'false';
							fwrite($myErrorFile,"Insert Failure: Unable to insert RNA information for sample $sample_name".PHP_EOL);
						}
					}
					else{
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: Unable to insert RNA information for sample $sample_name".PHP_EOL);;
					}
					
					// Check if freezer and drawer exist
					$stmt = $dbc->prepare("SELECT drawer_id, freezer_id FROM freezer_drawer WHERE drawer_id = ? AND freezer_id = ? AND visible_flag = 1");
					$stmt->bind_param("ss", $rna_storage_drawer,$rna_storage_freezer);		
					if ($stmt->execute()){
						$stmt->store_result();
				    	if($stmt->num_rows <= 0){
				    		fwrite($myErrorFile,"Insert Failure: Freezer ".$rna_storage_freezer." & Drawer Name ".$rna_storage_drawer." do not exist. Please enter manually or check spelling".PHP_EOL);
				    		$insert_check = 'false';
				    	}
					} 
					$stmt -> close();
					
					
					// Insert sample_name into storage_info table  with 'rna' storage info
					$rna_storage = $rna_storage_freezer.','.$rna_storage_drawer;
					if($rna_storage_exists == 'Y' || $rna_storage_exists == 'y'){
						$rna_sample_exists = 'one';
					}elseif($rna_storage_exists == 'N' || $rna_storage_exists == 'n'){
						$rna_sample_exists = 'three';
					}else{
						$insert_check = 'false';
						fwrite($myErrorFile,"Insert Failure: Sample Exists flag must be Y/y or N/n".PHP_EOL);
					}
					
					if($insert_check == 'true'){
						$query = "UPDATE storage_info SET rna_extr = ?, RNA_sample_exists = ? WHERE sample_name = ?";
						if($stmt = $dbc ->prepare($query)) {                 
							$stmt->bind_param('sss',$rna_storage,$rna_sample_exists,$sample_name);
							if($stmt -> execute()){
								$rows_affected = $stmt ->affected_rows;
								$stmt -> close();
								if($rows_affected < 0){	
										$insert_check = 'false';
										fwrite($myErrorFile,"Insert Failure: unable to insert RNA information for sample $sample_name".PHP_EOL);
									}
								}
							else{
								$insert_check = 'false';
								fwrite($myErrorFile,"Insert Failure: unable to insert RNA information for sample $sample_name".PHP_EOL);
							}
						}
						else{
							$insert_check = 'false';
							fwrite($myErrorFile,"Insert Failure: unable to insert RNA information for sample $sample_name".PHP_EOL);;
						}
						//$stmt->close();
					}
							
				}

			}else{
				$insert_check = 'false';
				fwrite($myErrorFile,"ERROR: No sample was created for project $project_name and sample number $sample_number. Unable to insert DNA/RNA info. Please check file".PHP_EOL);
			}
		}

		//$insert_check = 'false'; //for testing
		
		
		/*****************************************************************************
		 * Do One Last Check And Commit If You Had No Errors
		 * ***************************************************************************/
		if($insert_check == 'true'){
			$dbc->commit();
			return 'Fini!';
		}
		else{
			throw new Exception("Final Error: Unable To Insert Info To DB. No Changes Made");		
		}
	}
	catch (Exception $e) {
		if (isset ($dbc)){
			echo '<script>Alert.render("ERROR: Bulk sample insert failed. No changes made. Please see error messages");</script>';
			fwrite($myErrorFile, "$e ".PHP_EOL);

			//rollback if see an error
			$dbc->rollback ();
			return 'ERROR';
		}
	}
	fclose($myErrorFile);
	fclose($myLogFile);
}
?>
