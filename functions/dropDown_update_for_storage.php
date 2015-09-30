<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
<body>
<?php

function dropDown_update_for_storage($select_name,$table_name,$field_name,$select_id,$s_field_name,$sample_name,$stored_name){ #send also the query name?, always based on sample name

			include('config/path.php');
			$path = $_SERVER['DOCUMENT_ROOT'].$root;
			include($path.'database_connection.php');
			
			$p_select_name = htmlspecialchars($select_name);
			$p_table_name = htmlspecialchars($table_name);
			$p_field_name = htmlspecialchars($field_name);
			$p_select_id = htmlspecialchars($select_id);
			$p_s_field_name = htmlspecialchars($s_field_name);
			$p_sample_name = htmlspecialchars($sample_name);

			//check that $table_name exists in white list
			include_once($path.'functions/white_list.php');
			
			$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_s_field_name,'column'); 
			if($check == 'true' && $check2 == 'true'){
				#echo "sfname:".$s_field_name.'<br>';
				#echo "samp Name:".$sample_name.'<br>';
				$query1 = "SELECT $p_s_field_name FROM storage_info WHERE sample_name = '$p_sample_name'";
				$result1 = mysqli_query($dbc, $query1);
				$name1;
				if($result1){
					while($row1 = mysqli_fetch_assoc($result1)) {
						$name1 = $row1["$p_s_field_name"];
						#echo "name:".$name1.'<br>';
						if($name1){
							$pieces = explode(",",$name1);
							$name1 = $pieces[$stored_name];
						}
						else{
							$name1 = ""; //see if exisits?
						}
						#echo "name:".$name1.'<br>';
					}
				}else{
					echo 'An error has occured';
					mysqli_error($dbc);
					#echo '<p>'.$query.'</p>';
				}
				
				
				#now get your dropdown slect menu and cycle through it till you match above. When you match above make it the selected option
				$query = "SELECT * FROM $p_table_name";
				$result = mysqli_query($dbc, $query);
				if(!$result){
					echo 'An error has occured';
					mysqli_error($dbc);
					#echo '<p>'.$query.'</p>';
				}
				
				echo "<select id='$p_select_name' name='$p_select_name' class='fields';'>";
				echo "<option value='0'>-Select-</option>";
		
				$attr = 'selected="selected"';
				while($row = mysqli_fetch_assoc($result)) {
					$name = htmlspecialchars($row["$p_field_name"]);
					$id = htmlspecialchars($row["$p_select_id"]);
					$id = trim($id);
					$name = trim($name);
					$name1 = trim($name1);
					
					$visible_check = htmlspecialchars($row["visible"]);
					if($visible_check == 1){
						
						if($id == $name1){
							echo '<option value="'.$id.'" selected>'.$name.'</option>';
						}
						else{
							echo '<option value="'.$id.'">'.$name.'</option>';
						}
					}

				}
	   	 		echo "</select><br>";
					
     	}
     	else{
			echo "ERROR: Please check table name";
     	}
     }	






/*
function dropDown_update_for_storage($s_field_name,$sample_name){ #send also the query name?, always based on sample name

			$path = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/airmicrobiomes/';
			include($path.'connect_air.php');
			
			$p_s_field_name = htmlspecialchars($s_field_name);
			$p_sample_name = htmlspecialchars($sample_name);

			//check that $table_name exists in white list
			include_once('white_list.php');
			
			//$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_s_field_name,'column'); 
			//if($check == 'true' && $check2 == 'true'){
			if($check2 == 'true'){
				#echo "sfname:".$s_field_name.'<br>';
				#echo "samp Name:".$sample_name.'<br>';
				$query1 = "SELECT $p_s_field_name FROM storage_info WHERE sample_name = '$p_sample_name'";
				$result1 = mysqli_query($dbc, $query1);
				$name1 = "";
				if($result1){
					while($row1 = mysqli_fetch_assoc($result1)) {
						$name1 = $row1["$p_s_field_name"];
						#echo "name:".$name1.'<br>';
					}
				}else{
					echo 'An error has occured';
					mysqli_error($dbc);
					#echo '<p>'.$query.'</p>';
				}

				if($name1){
					$pieces = explode(",",$name1);
					return $pieces;
		
				}
				else{return "";}
			}	
				
			
     }	


	function recall ($temp, $check_temp,$get_array){
			if($temp == $check_temp){
				return "true";	
			}
			else{
				return "falseeee";
			}
	}
 * 
 */
?>

</body>
</html>