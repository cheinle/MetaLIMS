

<?php	//drowpdown to select field_name from table_name
function dropDown($select_name,$table_name,$field_name,$select_id,$submitted,$root){
	
			#sanatize
			$p_select_name = htmlspecialchars($select_name);
			$p_table_name = htmlspecialchars($table_name);
			$p_field_name = htmlspecialchars($field_name);
			$p_select_id = htmlspecialchars($select_id);
			
			//include('config/path.php');
			//$path = $_SERVER['DOCUMENT_ROOT'].$root;
			$path = $_SERVER['DOCUMENT_ROOT'].$root;
			include($path.'database_connection.php');
		

			//check that $table_name exists in white list
			include_once($path.'functions/white_list.php');
			
			
			$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_field_name,'column');
			if($check == 'true' && $check2 == 'true'){
				
				$query = "SELECT * FROM $p_table_name";
				$result = mysqli_query($dbc, $query);
				if(!$result){
					echo 'An error has occurred';
					mysqli_error($dbc);
				}
				
				//echo "<select id='$p_select_name' name='$p_select_name' class='fields';'>";
				echo "<select id='$p_select_name' name='$p_select_name'>";
				echo "<option value='0'>-Select-</option>";
		
				$attr = 'selected="selected"';
				while($row = mysqli_fetch_assoc($result)) {
					$name = htmlspecialchars($row["$p_field_name"]);
					$id = htmlspecialchars($row["$p_select_id"]);
					$visible_check = htmlspecialchars($row["visible"]);
					if($visible_check == '1'){
						if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {
							if(isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
									$selected_option = $_SESSION["$p_select_name"];
									echo '<option value="'.$id.'"', ($selected_option == $id) ? 'selected':'' ,'>'.$name.'</option>';
							
							}
							else{
								$selected_option = $_GET["$p_select_name"];
								echo '<option value="'.$id.'"', ($selected_option == $id) ? 'selected':'' ,'>'.$name.'</option>';
							}
						}
						else{
							echo '<option value="'.$id.'">'.$name.'</option>';
						}
					}
				}
	   	 		echo "</select>";
				
		
     		}
			else{
				echo "ERROR: Please check table name";
			}
}	

			

?>

<!--</body>
</html>-->