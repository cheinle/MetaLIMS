<?php
	include('../database_connection.php');


	$num_air_samplers = $_GET['num_air_samplers'];
	$old_num_air_samplers = $_GET['old_num_air_samplers'];
	$air_samplers = array();
	if(isset($_GET['air_samplers'])){
		$air_samplers = $_GET['air_samplers'];
	}
	$start_dates = array();
	if(isset($_GET['start_dates'])){
		$start_dates = $_GET['start_dates'];
	}
	$start_times = array();
	if(isset($_GET['start_times'])){
		$start_times = $_GET['start_times'];
	}
	$end_dates = array();
	if(isset($_GET['end_dates'])){
		$end_dates = $_GET['end_dates'];
	}
	$end_times = array();
	if(isset($_GET['end_times'])){
		$end_times = $_GET['end_times'];
	}

	for ($x = 1; $x <= $num_air_samplers; $x++) {

    	echo "<p>";
		echo "<label class='textbox-label-sampler'>Sampler #".$x.":*</label>";
		echo "<select id='airSamp".$x."' name='airSamp".$x."'>";
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
						$selected_option = $air_samplers[$x];
						if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
							echo '<option value="'.$p_vale.'"', ($selected_option == $p_value) ? 'selected':'' ,'>'.$p_value.'</option>';
						}
						else{
							if(isset($_GET['air_samplers'])){
								if (array_key_exists($x, $air_samplers)) {
									echo '<option value="'.$p_value.'"', ($selected_option == $p_value) ? 'selected':'' ,'>'.$p_value.'</option>';
								}
							}
							else{
								echo '<option value="'.$p_value.'">'.$p_value.'</option>';
							}
						}
					}		
				}	
				$stmt[$x]->free_result();	
		    	$stmt[$x]->close();
			
			} 
			echo "</select>";
			echo "</p>";
?>

			<label class="textbox-label-sampler">Start Date/Time:*</label>
			<input type="text" id="sdate<?php echo $x ?>"  class = "shrtfields" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php 
				if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
					echo htmlspecialchars($_GET['sdate']);
				}
				else{
					if(isset($_GET['air_samplers'])){
						if (array_key_exists($x,$start_dates)) {
							echo $start_dates[$x];
						}
					}
			}?>"/>
			<input type="text" name="stime<?php echo $x ?>" id ="stime<?php echo $x ?>" class="shrtfields"  placeholder="Time"  value="<?php 
				if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
				echo htmlspecialchars($_GET['stime']);
				}
				else{
					if(isset($_GET['air_samplers'])){
						if (array_key_exists($x, $start_times)) {
							echo $start_times[$x];
						}
					}
				}?>"/>
				
			<label class="textbox-label-sampler">End Date/Time:*</label>
			<input type="text" id="edate<?php echo $x ?>" class = "shrtfields" placeholder = "Date" name="edate<?php echo $x ?>" value="<?php 
				
				if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
					echo htmlspecialchars($_GET['edate']);
				}
				else{
					if(isset($_GET['air_samplers'])){
						if (array_key_exists($x, $end_dates)) {
							echo $end_dates[$x];
						}
					}
				}?>"/>
			<input type="text" name="etime<?php echo $x ?>" id="etime<?php echo $x ?>" class="shrtfields"  placeholder="Time" value="<?php 
				
				if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
					echo htmlspecialchars($_GET['etime']);
				}
				else{
					if(isset($_GET['air_samplers'])){
						if (array_key_exists($x, $end_times)) {
						    echo $end_times[$x];
						}
					}
				}?>"/>
				
				<script type="text/javascript">
				$('#sdate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				$('#edate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();

				var air_samp_num = <?php echo(json_encode($x)); ?>;
    			$(document).ready(function(){
        			$('input[name="stime'+air_samp_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
   	 			
   	 			$(document).ready(function(){
   	 				$('input[name="etime'+air_samp_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
				</script>
				
<?php	
			} 
		}
?>
		
