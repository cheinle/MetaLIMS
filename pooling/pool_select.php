<?php
	 include('../database_connection.php');

	$num_to_pool = $_GET['num_pooled_samples'];

	for ($x = 1; $x <= $num_to_pool; $x++) {
    	#echo "The number is: $x <br>";
    	echo '<p>
		<label class="textbox-label">Sample '.$x.' Name:</label>
		<br>';
		?>
    	<input type="text" placeholder="Sample<?php echo $x; ?> Name" name="sample_name<?php echo $x; ?>" id="customerAutocomplte<?php echo $x; ?>" class="ui-autocomplete-input" autocomplete="off" value ="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>"/>
		</p>
		<script>
			$(document).ready(function($){
				var x = <?php echo json_encode($x); ?>;
    			$('#customerAutocomplte'+x).autocomplete({
					source:'../suggest_name.php', 
					minLength:3
    			});
			});
		</script>
				
		<br>
			
		<?php }

?>
