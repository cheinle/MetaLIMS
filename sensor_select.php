<?php
	 include('database_connection.php');

	$num_of_sens = $_GET['num_sensors'];

	for ($x = 1; $x <= $num_of_sens; $x++) {
    	#echo "The number is: $x <br>";
		echo "<label class='textbox-label'>Sensor Number".$x.":*</label><br>";
		echo "<select id='sensor".$x."' name='sensor".$x."' class='fields'>";
		echo "<option value='0'>-Select-</option>";
		$stmt[$x] = $dbc->prepare("SELECT part_sens_name FROM particle_counter ");
  		if ($stmt[$x]->execute()){
			if($stmt[$x]->fetch()){
				$meta[$x] = $stmt[$x]->result_metadata(); 
		   		while ($field[$x] = $meta[$x]->fetch_field()){ 
		        	$params[$x][] = &$row[$x][$field[$x]->name]; 
		    	} 
		
		    	call_user_func_array(array($stmt[$x], 'bind_result'), $params[$x]); 
		
				$stmt[$x]->execute();
				$header_ct = 0;
			
		    	while ($stmt[$x]->fetch()) {
					foreach($row[$x] as $key => $value){		
						$p_value = htmlspecialchars($value);
						echo '<option value="'.$p_value.'">'.$p_value.'</option>';
					}		
				}		
				$stmt[$x]->free_result();	
		    	$stmt[$x]->close();
			
			} 
			echo '</select>';
?>
			
				<label class="textbox-label">Start/End Time:*</label>
				<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields" placeholder="Start" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['stime']);} ?>"/>
				<input type= "text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields" placeholder="End" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['etime']);} ?>"/>
				
				<script type="text/javascript">
				var num_sensors = <?php echo(json_encode($x)); ?>;
    			$(document).ready(function(){
        			$('input[name="stime'+num_sensors+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
   	 			
   	 			$(document).ready(function(){
   	 				$('input[name="etime'+num_sensors+'"]').ptTimeSelect();
        			//$('input[name="etime"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
				</script>
				
<?php
			echo '<br>';
			} 
		}

?>
