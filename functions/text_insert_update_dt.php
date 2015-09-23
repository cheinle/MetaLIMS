
<body>
<?php	

function text_insert_update_dt($sample_name,$field_name,$field_sub){ #send also the query name?, always based on sample name

	include('database_connection.php');

	$stmt = $dbc->prepare("SELECT * FROM sample WHERE sample_name = ?");
	$stmt -> bind_param('s',$sample_name);
	if ($stmt->execute()){
		
	
	    			
	    if($stmt->fetch()){
	    	$meta = $stmt->result_metadata(); 
   			while ($field = $meta->fetch_field()){ 
        		$params[] = &$row[$field->name]; 
    		} 

    		call_user_func_array(array($stmt, 'bind_result'), $params); 

			$stmt->execute();
			while ($stmt->fetch()) {
				#echo $row[$field_name];
				if(($field_name == 'start_samp_date_time')|| ($field_name == 'end_samp_date_time') || ($field_name == 'd_extraction_date')){		
					$explode = explode(" ",$row[$field_name]);
					if($field_sub == 'date'){
						return $explode[0];
					}
					else{
						return $explode[1];
					}
				}
			}		
			$stmt->close();
		} 
	}
}
?>

</body>
</html>