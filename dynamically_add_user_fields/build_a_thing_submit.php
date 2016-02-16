<?php
		include ('../database_connection.php');

		$stmt = $dbc->prepare("SELECT label_name,type,select_values FROM create_user_thing");
		if(!$stmt){
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		if ($stmt->execute()){
			$stmt->bind_result($label_name,$type,$select_values);
			while ($stmt->fetch()) {
				$p_orig_project_name = $p_name;
			}
		}
		else{
			$error_check = 'true';
			die('execute() failed: ' . htmlspecialchars($stmt->error));
		}
				
?>