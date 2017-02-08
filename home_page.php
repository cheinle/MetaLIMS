<?php 
if(!isset($_SESSION)) { session_start(); }
include('index.php');
include('database_connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
$root = $_SESSION['link_root'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home Page</title>	
		<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
</head>
<body class="homepage">				

<h1 style="color:#fff;font-family: Georgia;text-shadow: 2px 4px 3px rgba(0,0,0,0.3);">Welcome <?php echo htmlspecialchars($_SESSION['first_name'].' '.$_SESSION['last_name']);?>!</h1>



<p>
<button type="button"  data-toggle="collapse" data-target="#demo" aria-expanded="true" aria-controls="demo" class='med'>Setting Up a New Project?</button>
<div id="demo" class="collapse">
<div style = "clear:both;float: left;width:35%;border-radius:5px;margin-top:10px;margin-left:2%;background-color:white;padding:5px">
<p>
New to MetaLIMS? Use the following options to get started!<br>
<br>
<strong>Instructions:</strong> <br>
Before starting to add your samples, please check on the sample insert page<br>
that the following dropdowns contain the information for your samples (<a href="<?php echo $root; ?>sample_insert/form_insert_jquery.php">Check Here</a>). <br>
If your information does not exist already, add the following:<br>
<br>
1) Add a new project name - submit request for project name to be added by admin<br>
<br>
2) Add a new sampler - if your the sampler you used to do your sample collection <br>
does not already exist, add one using the button below<br>
<br>
3) Add a new location and relative location - if your location or relative <br>
location do not exist, add a them now using the button below<br>
<br>	
	Ex: Location - General Hospital , Relative Location - Pediatrics <br>
<br>
4) Add a new media type - if you do not see the media type you used, <br>
add now using the button below (can be N/A)<br>
<br>
5) Note: If a sample type or storage location is needed, please approach <br>
admin to add<br>
	
</p>
</div>
</p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_proj_name_for_approval.php'" value='1'>Add a New Project Name (Request)</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_sampler.php'" value='1'>Add a New Sampler</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_samp_loc.php'" value='1'>Add a New Location</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_samp_rel_loc.php'" value ='1'>Add a New Relative Location</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>update_tables/update_media_type.php'" value ='1'>Add a New Media Type</button></p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>sample_insert/form_insert_jquery.php'" value ='1'>Start Adding Samples!!</button></p>
</div>




<p>
<button type="button"  data-toggle="collapse" data-target="#demo2" aria-expanded="true" aria-controls="demo" class='med'>Adding Samples to Exisiting Project?</button>
<div id="demo2" class="collapse">
</p>
<button class="small-button" type=button onClick="parent.location='<?php echo $root; ?>sample_insert/form_insert_jquery.php'" value='2'>Insert Sample(s)</button></p>
</div>


<!--</div>--><!--end homepage div-->

</body>
</html>