<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
<body>
<?php

function dropDown_update_for_daily_data($select_name,$table_name,$field_name,$select_id,$s_field_name,$daily_date,$location){ #send also the query name?, always based on sample name

			include('../config/path.php');
			$path = $_SERVER['DOCUMENT_ROOT'].$root;
			include($path.'database_connection.php');
			
			$p_select_name = htmlspecialchars($select_name);
			$p_table_name = htmlspecialchars($table_name);
			$p_field_name = htmlspecialchars($field_name);
			$p_select_id = htmlspecialchars($select_id);
			$p_s_field_name = htmlspecialchars($s_field_name);
			$p_daily_date = htmlspecialchars($daily_date);
			$p_location = htmlspecialchars($location);

			//check that $table_name exists in white list
			include_once($path.'functions/white_list.php');
			
			//grab what exists in the db for this field for this daily date
			$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_s_field_name,'column'); 
			if($check == 'true' && $check2 == 'true'){
				
				$query1 = "SELECT $p_s_field_name FROM daily_data2 JOIN daily_data2_particle_counter ON daily_data2.daily_date = daily_data2_particle_counter.daily_date WHERE daily_data2.daily_date = '$p_daily_date' AND daily_data2.location = '$p_location'";
				$result1 = mysqli_query($dbc, $query1);
				$name1;
				
				if($result1){
					while($row1 = mysqli_fetch_assoc($result1)) {
						$name1 = $row1["$p_s_field_name"];
					}
				}else{
					echo 'An Error Has Occurred In Selection';
					mysqli_error($dbc);
				}
				
				
				#now get your dropdown slect menu and cycle through it till you match above. When you match above make it the selected option
				$query = "SELECT * FROM $p_table_name";
				$result = mysqli_query($dbc, $query);

				if(!$result){
					echo 'An error has occured';
					mysqli_error($dbc);
					echo '<p>'.$query.'</p>';
				}
				
				echo "<select id='$p_select_name' name='$p_select_name' class='fields';'>";
				echo "<option value='0'>-Select-</option>";
		
				$attr = 'selected="selected"';
				while($row = mysqli_fetch_assoc($result)) {
					$name = htmlspecialchars($row["$p_field_name"]);
					$id = htmlspecialchars($row["$p_select_id"]);

						if($id == $name1){
							echo '<option value="'.$id.'" selected>'.$name.'</option>';
						}
						else{
							echo '<option value="'.$id.'">'.$name.'</option>';
						}
				}
	   	 		echo "</select><br>";
					
     	}
     	else{
			echo "ERROR: Please check table name";
     	}
     }	

?>

</body>
</html>