

<?php	//drowpdown to select field_name from table_name
function dropDown($select_name,$table_name,$field_name,$select_id,$submitted,$root){
	
			#sanatize
			$p_select_name = htmlspecialchars($select_name);
			$p_table_name = htmlspecialchars($table_name);
			$p_field_name = htmlspecialchars($field_name);
			$p_select_id = htmlspecialchars($select_id);
			
			$path = $_SERVER['DOCUMENT_ROOT'].$root;
			include($path.'database_connection.php');
		

			//check that $table_name exists in white list
			include_once($path.'functions/white_list.php');
			
			
				$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_field_name,'column');
			$check3 = whiteList($p_select_id,'column');
			
			if($check == 'true' && $check2 == 'true' && $check3 == 'true'){

				echo "<select id='$p_select_name' name='$p_select_name'>";
				echo "<option value='0'>-Select-</option>";
				//$attr = 'selected="selected"'; //page no longer re-loads on submit. No longer needed

				$stmt = $dbc->prepare("SELECT $p_field_name,$p_select_id FROM $p_table_name");
				if(!$stmt){;
					die('prepare() failed: ' . htmlspecialchars($stmt->error));
				}
				if ($stmt->execute()){
					$stmt->bind_result($name,$id);
				
					while ($stmt->fetch()){
						$id = htmlspecialchars($id);
						$id = trim($id);
						$name = htmlspecialchars($name);
						$name = trim($name);
						
						echo '<option value="'.$id.'">'.$name.'</option>';
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

<!--</body>
</html>-->