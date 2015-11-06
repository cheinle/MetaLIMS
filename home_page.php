<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');
include('index.php');
include('config/path.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home Page</title>	
		<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
</head>
<body class="homepage">
<script>Alert.render("WARNING: Error was found. No updates have been made. Please see details of update );</script>
					
<script>
alert("NOTICE: At This Time. Please Use Chrome As Your Browser. Sorry For Any Inconvenience!");
</script>
	<!--<div class = "homepage">-->
<h1 style="color:#fff;font-family: Georgia;text-shadow: 2px 4px 3px rgba(0,0,0,0.3);">Welcome <?php echo htmlspecialchars($_SESSION['first_name'].' '.$_SESSION['last_name']);?>!</h1>
<p>
<button type="button"  data-toggle="collapse" data-target="#demo" aria-expanded="true" aria-controls="demo" class='med'>Setting Up a New Project?</button>
<div id="demo" class="collapse">
</p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_proj_name_for_approval.php'" value='1'>Add a New Project Name</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_sampler.php'" value='1'>Add a New Sampler</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_part_sens.php'" value='1'>Add a New Sensor</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_samp_loc.php'" value='1'>Add a New Location</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_samp_rel_loc.php'" value ='1'>Add a New Relative Location</button></p>
</div>

<p>
<button type="button"  data-toggle="collapse" data-target="#demo2" aria-expanded="true" aria-controls="demo" class='med'>Adding Samples to Exisiting Project?</button>
<div id="demo2" class="collapse">
</p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>form_insert.php'" value='2'>Insert Sample(s)</button></p>
</div>


<!--</div>--><!--end homepage div-->

</body>
</html>