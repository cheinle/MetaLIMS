

<?php	//drowpdown to select field_name from table_name
function dropDown($select_name,$table_name,$field_name,$select_id,$submitted){
	
			#sanatize
			$p_select_name = htmlspecialchars($select_name);
			$p_table_name = htmlspecialchars($table_name);
			$p_field_name = htmlspecialchars($field_name);
			$p_select_id = htmlspecialchars($select_id);
			
			//include('config/path.php');
			//$path = $_SERVER['DOCUMENT_ROOT'].$root;
			$path = $_SERVER['DOCUMENT_ROOT'].'/series/dynamic/am_production/';
			include($path.'database_connection.php');
		

			//check that $table_name exists in white list
			include_once($path.'functions/white_list.php');
			
			
			$check = whiteList($p_table_name,'table'); 
			$check2 = whiteList($p_field_name,'column');
			if($check == 'true' && $check2 == 'true'){
				
				$query = "SELECT * FROM $p_table_name";
				$result = mysqli_query($dbc, $query);
				if(!$result){
					echo 'An error has occured';
					mysqli_error($dbc);
					echo '<p>'.$query.'</p>';
				}
				
				//echo "<select id='$p_select_name' name='$p_select_name' class='fields';'>";
				echo "<select id='$p_select_name' name='$p_select_name'>";
				echo "<option value='0'>-Select-</option>";
		
				$attr = 'selected="selected"';
				while($row = mysqli_fetch_assoc($result)) {
					$name = htmlspecialchars($row["$p_field_name"]);
					$id = htmlspecialchars($row["$p_select_id"]);

					if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {
						if(isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false'){
?>
								<option value="<?php echo $id; ?>" <?php if((isset($_SESSION["$p_select_name"]) && $_SESSION["$p_select_name"] == "$id" )){ echo "selected";} ?>><?php echo "$name" ?></option>
<?php
						}
						else{
?>		
				 		<!--<option value="<?php echo $id; ?>" <?php if((isset($_GET["$p_select_name"]) && $_GET["$p_select_name"] == "$name" )|| isset($_GET['copy'])){ echo "selected";} ?>><?php echo "$name" ?></option>-->
						<option value="<?php echo $id; ?>" <?php if((isset($_GET["$p_select_name"]) && $_GET["$p_select_name"] == "$id" )){ echo "selected";} ?>><?php echo "$name" ?></option>
<?php
						}
					}
					else{
						?>
						<option value="<?php echo $id; ?>"><?php echo "$name" ?></option>;
						<?php
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