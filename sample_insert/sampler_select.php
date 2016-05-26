<?php
	include('../database_connection.php');


	$num_my_samplers = $_GET['num_my_samplers'];
	$old_num_my_samplers = $_GET['old_num_my_samplers'];
	$my_samplers = array();
	if(isset($_GET['my_samplers'])){
		$my_samplers = $_GET['my_samplers'];
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

	for ($x = 1; $x <= $num_my_samplers; $x++) {
		echo "<p>";
		echo "<label class='textbox-label-sampler'>Sampler #".$x.":*</label>";
		echo "<select id='mySamp".$x."' name='mySamp".$x."'>";
		echo "<option value='0'>-Select-</option>";
		$stmt[$x] = $dbc->prepare("SELECT sampler_name, visible FROM sampler");
  		if ($stmt[$x]->execute()){
  			
			$stmt[$x]->bind_result($sampler_name,$visible);
			while ($stmt[$x]->fetch()) {
  				if($visible == '1'){
					echo '<option value="'.$sampler_name.'">'.$sampler_name.'</option>';
  				}
			}
		}	
		$stmt[$x]->free_result();	
		$stmt[$x]->close();

		echo "</select>";
		echo "</p>";
?>

			<label class="textbox-label-sampler">Start Date/Time:*</label>
			<input type="text" id="sdate<?php echo $x ?>"  class = "shrtfields" placeholder = "Date" name="sdate<?php echo $x ?>" value="<?php 
				if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {
					echo htmlspecialchars($_GET['sdate']);
				}
				else{
					if(isset($_GET['my_samplers'])){
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
					if(isset($_GET['my_samplers'])){
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
					if(isset($_GET['my_samplers'])){
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
					if(isset($_GET['my_samplers'])){
						if (array_key_exists($x, $end_times)) {
						    echo $end_times[$x];
						}
					}
				}?>"/>
				
				<script type="text/javascript">
				$('#sdate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();
				$('#edate<?php echo $x ?>').datepicker({ dateFormat: 'yy-mm-dd' }).val();

				var my_samp_num = <?php echo(json_encode($x)); ?>;
    			$(document).ready(function(){
        			$('input[name="stime'+my_samp_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
   	 			
   	 			$(document).ready(function(){
   	 				$('input[name="etime'+my_samp_num+'"]').ptTimeSelect();
        			timeFormat: "HH:mm"
   	 			});
				</script>
				
<?php } ?>
		
