<?php
 	include('../database_connection.php');
	include_once('../functions/white_list.php');
	
	$table_name = $_GET['table_name'];
	$inputs= $_GET['inputs'];
	print_r($inputs);
	
	$fields = '';
	$values = '';
	$question_marks = '';
	foreach($inputs as $key => $value){

		echo $value;
		$res = explode("-", $value);
		$field_name = $res[0];
		$field_value = $res[1];
		
		
		$valid_field_name = whiteList($field_name,'column');
		
		if($valid_field_name == 'true'){
			$fields = $fields.','.$field_name; //white list field names, if does not exist, throw error
			$values = $values.','.$field_value;
			$question_marks = $question_marks.',?';
		}
		else{
			header('HTTP/1.1 500 Internal Server Booboo');
       		header('Content-Type: application/json; charset=UTF-8');
        	die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}	
	
	$fields = trim($fields,",");
	$values = trim($values,",");
	$question_marks = trim ($question_marks,",");	
	
	echo "Fields:".$fields.'<br>';
	echo "Val:".$values.'<br>';
	echo "Questions Marks:".$question_marks.'<br>';
	$query1 = "INSERT INTO ".$table_name." (".$fields.") VALUES (";
	$query2 = $question_marks.")";
	$full_query = $query1.$query2;
	echo $full_query;
	$params = "";
	$stmt2 = $dbc -> prepare($full_query);
	if(!$stmt2){
		$insert_check = 'false';
		throw new Exception("Prepare Failure: Unable To Insert Into Main Sample Table");	
	}
	else{
		 $stmt2 -> bind_param('sssssssssssssdsiisdsiissddsissssdsss', $p_sample_name, $p_loc,$p_rloc, $p_partSamp, $p_collName, $p_dExtKit, $p_rExtKit, $p_seqInfo, $p_anPipe, $p_barcode, $p_sType, $p_path, $p_projName, $p_dConc,$p_dInstru,$p_dVol,$p_dVol_quant,$p_d_extr_date,$p_rConc,$p_rInstru,$p_rVol,$p_rVol_quant,$p_r_extr_date,$p_notes,$p_fRate,$p_fRate_eod,$p_dData,$p_sample_number,$p_entered_by,$sample_sort,$p_orig_time_stamp,$p_media,$p_sampling_height,$p_dExtrName,$p_rExtrName,$seq_id);
		 if(!$stmt2 -> execute()){
			$insert_check = 'false';
			throw new Exception("Execution Failure: Unable To Insert Into Main Sample Table");
		 }
		else{
			$rows_affected2 = $stmt2 ->affected_rows;
			$stmt2 -> close();
			if($rows_affected2 > 0){
	

?>

