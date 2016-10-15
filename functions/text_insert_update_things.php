
<?php	

function text_insert_update_things($sample_name,$thing_id){

	if(!isset($_SESSION)) { session_start(); }

	include($_SESSION['include_path'].'database_connection.php');

	//$query = "SELECT * FROM store_user_things WHERE sample_name = ?";
	$query = "SELECT thing_value FROM thing_storing WHERE sample_name = ? AND thing_id = ?";
	$stmt = $dbc->prepare($query);
	$stmt -> bind_param('si',$sample_name,$thing_id);
	if ($stmt->execute()){
	    $stmt->bind_result($thing_value);		
	    if ($stmt->fetch()){
	    	return htmlspecialchars($thing_value); 			
		} 
	}
	$stmt->close();
}
?>
