
<?php

function dropDown_update_for_daily_data($select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date,$location,$part_sens_name){ #send also the query name?, always based on sample name
	
	if(!isset($_SESSION)) { session_start(); }
	$path = $_SESSION['include_path'];
	include($path.'database_connection.php');
	
	$p_select_name = htmlspecialchars($select_name);
	
	$p_table_name = htmlspecialchars($table_name);
	$p_field_name = htmlspecialchars($field_name);
	$p_select_id = htmlspecialchars($select_id);
	$p_s_field_name = htmlspecialchars($s_field_name);
	
	$p_daily_date = htmlspecialchars($daily_date);
	$p_location = htmlspecialchars($location);
	$p_part_sens_name = htmlspecialchars($part_sens_name);

	//check that $table_name exists in white list
	include_once($path.'functions/white_list.php');
	
	//grab what exists in the db for this field for this daily date
	$check = whiteList($p_table_name,'table'); 
	$check2 = whiteList($p_s_field_name,'column'); 
	$check3 = whiteList($p_field_name,'column'); 
	$check4 = whiteList($p_select_id,'column'); 
	
	if($check == 'true' && $check2 == 'true' && $check3 == 'true'  && $check4 == 'true'){
			
		//check if entry is selected
		$name1;
		$stmt = $dbc->prepare("SELECT $p_s_field_name FROM daily_data2_particle_counter WHERE daily_date = ? AND location = ? AND part_sens_name = ?");
		if(!$stmt){;
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		$stmt->bind_param("sss",$p_daily_date,$p_location,$p_part_sens_name);
		if ($stmt->execute()){
			$stmt->bind_result($name1);
			while ($stmt->fetch()) {
				$name1 = htmlspecialchars($name1);
			}
		}
		$stmt->close();	
			
		//now get your dropdown slect menu and cycle through it till you match above. When you match above make it the selected option
		echo "<select id='$p_select_name' name='$p_select_name' class='fields';'>";
		echo "<option value='0'>-Select-</option>";
		$attr = 'selected="selected"';
		
		$stmt = $dbc->prepare("SELECT $p_field_name, $p_select_id FROM $p_table_name");
		if(!$stmt){;
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
		}
		if ($stmt->execute()){
			$stmt->bind_result($name,$id);
			while ($stmt->fetch()) {
				$id = htmlspecialchars($id);
				$id = trim($id);
				$name = htmlspecialchars($name);
				$name = trim($name);
				$name1 = trim($name1);
				
				if($id == $name1){
					echo '<option value="'.$id.'" selected>'.$name.'</option>';
				}
				else{
					echo '<option value="'.$id.'">'.$name.'</option>';
				}

			}
		}
		$stmt->close();
		echo "</select><br>";
			
 	}
 	else{
		echo "ERROR: Please check table name";
 	}
}	

?>