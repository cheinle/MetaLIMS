<head>
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	
</head>
<body>
<?php	

function text_insert_isolate_update($sample_name,$field_name,$root){ #send also the query name?, always based on sample name
	//$sample_name = '2015/04/23fungal_bac_isolatesF003';
	//$field_name = 'iso_date';
	//include('config/path.php');
	//$path = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/am_production/';
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'database_connection.php');

	$stmt = $dbc->prepare("SELECT * FROM isolates WHERE sample_name = ?");
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
				if($field_name == 'sample_num'){//check that returned number is output as 3 digits
					$regrex_check_sn1  = '/^[0-9]$/';
					if (preg_match("$regrex_check_sn1", $row[$field_name])){
						$row[$field_name]= '00'.$row[$field_name];
					}
					else{
						$regrex_check_sn2  = '/^[0-9][0-9]$/';
						if (preg_match("$regrex_check_sn2", $row[$field_name])){
							$row[$field_name] = '0'.$row[$field_name];
						}
					}
				}
				return htmlspecialchars($row[$field_name]); 		
			}		
			$stmt->close();
		} 
	}
}
?>

</body>
</html>