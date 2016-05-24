<?php
include('../index.php'); 
include('../database_connection.php');
error_reporting(E_ALL); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daily Data Update</title>		
</head>
<body>
<?php 
include('../functions/dropDown.php');
include('add_daily_data_ajax.php');
$submitted = 'false';
?>
<div class="page-header">
<h3>Add Daily Sensor Data</h3>
</div>

	<form class = "registration" id="form" name="form">
	<p><i>* = required field </i></p>
		
		
		<fieldset>
		<LEGEND><b>Location/Date:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Daily Data DATE:*</label>
		<input type="text" id="mydate"  name="mydate" value="<?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo htmlspecialchars($_GET['mydate']);} ?>"/>
		<script>
			$('#mydate').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		</p>
		
		
		<!--location dropdown-->
		<p>
		<label class="textbox-label">Select Location:*</label>
		<?php

		//url or $_GET name, table name, field name
		dropDown('loc', 'location', 'loc_name','loc_name',$submitted,$root);
		?>
		</p>
		</div>
		</fieldset>

		
		<fieldset>
		<LEGEND><b>Sensor Data:</b></LEGEND>
		<div class="col-xs-6">
		<p>
		<label class="textbox-label">Pick Number Of Sensors Used:</label><br>
		<select id='sens_num' name='sens_num' class='fields'>
		<option value='0'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "0" )){ echo "selected";}}?>>-Select-</option>
		<option value='1'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "1" )){ echo "selected";}}?>>-1:One-</option>
		<option value='2'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "2" )){ echo "selected";}}?>>-2:Two-</option>
		<option value='3'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "3" )){ echo "selected";}}?>>-3:Three-</option>
		<option value='4'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "4" )){ echo "selected";}}?>>-4:Four-</option>
		<option value='5'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "5" )){ echo "selected";}}?>>-5:Five-</option>
		<option value='6'<?php if ((isset($_GET['submit']))){
			if((isset($_GET['sens_num']) && $_GET['sens_num'] == "6" )){ echo "selected";}}?>>-6:Six-</option>
		</select>
		<div id="div1">
		</div>
		</p>
		</div>
		</fieldset>
		
		<fieldset>
		<LEGEND><b>Daily Notes:(Optional)</b></LEGEND>
		<p>
		<label class="textbox-label">Sample Notes:</label>
		<textarea class="form-control" from="sample_form" rows="3" id="notes" name="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if ((isset($_GET['submit']) && $submitted != 'true')) {echo $p_notes;} ?></textarea>
		</p>
		</fieldset>
		<script type="text/javascript">
		
			 
			
			
			</script>
			<input class="button" id="submit" type="button" value="Submit">
			<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
		
	</form>
	
	

	
</body>
	
</html>
