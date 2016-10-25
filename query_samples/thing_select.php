<?php
	
include ('../database_connection.php');
echo "<p>";
echo "<label class=\"textbox-label\">Select User Created Field to Bulk Update:</label><br/>";
echo "<select id=\"thing_select\" name = \"thing_select\">";
echo "<option value=''>-Select-</option>";

$query = "SELECT thing_id, label_name, type, select_values FROM create_user_things";
$stmt = $dbc->prepare($query);
if ($stmt->execute()){
	$stmt->bind_result($thing_id,$label_name,$type,$select_values);
	while ($stmt->fetch()){
    	echo '<option value="'.$thing_id.':'.$label_name.':'.$type.':'.$select_values.'">'.$label_name.'</option>';			}
}

echo "</select>";
echo "</p>";
?>
	
