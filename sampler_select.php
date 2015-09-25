<?php
	 include('database_connection.php');

	$num_samplers = $_GET['num_samplers'];

	for ($x = 1; $x <= $num_samplers; $x++) {
    	#echo "The number is: $x <br>";
    	echo "<p>";
		echo "<label class='textbox-label-sampler'>Sampler #".$x.":*</label>";
		echo "<select id='sampler".$x."' name='sampler".$x."'>";
		echo "<option value='0'>-Select-</option>";
		$stmt[$x] = $dbc->prepare("SELECT sampler_name FROM sampler");
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
			echo "</select>";
			echo "</p>";
?>

				<label class="textbox-label-sampler">Start Date/Time:*</label>
				<input type="text" id="sdate<?php echo $x ?>"  class = "shrtfields" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['sdate']);} ?>"/>
				<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields"  placeholder="Time"  value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['stime']);} ?>"/>
				
				<label class="textbox-label-sampler">End Date/Time:*</label>
				<input type="text" id="edate<?php echo $x ?>" class = "shrtfields" placeholder = "Date" name="edate<?php echo $x ?>" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['edate']);} ?>"/>
				<input type="text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields"  placeholder="Time" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['etime']);} ?>"/>
				
				<script type="text/javascript">
				$('#sdate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				$('#edate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();

				var sampler_num = <?php echo(json_encode($x)); ?>;
    			$(document).ready(function(){
        			$('input[name="stime'+sampler_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
   	 			
   	 			$(document).ready(function(){
   	 				$('input[name="etime'+sampler_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
				</script>
				
<?php
			
			
			} 
		}
?>
		<!--<p>
		<h3 class="checkbox-header">Check To Mark All Times Are The Same:</h3>
 		<div class="vert-checkboxes">
 		<label class="checkbox-label"><input type="checkbox" name="same_time" id="same_time" value="same_time"/>Yes</label>
		</div>
		</p>-->
		<!--</fieldset>-->

