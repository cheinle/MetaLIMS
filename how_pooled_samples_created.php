<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');
?>
<!--testting wwwhhat?-->
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pool Creation</title>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>

<body>
	<?php include ('index.php');?>
	<div class="page-header">
	<h3>How Pooled Samples Are Created</h3>
	</div>
	
	<pre class="border">		      
		<b>Q: How are pooled samples created in the db?</b> 
	
			A: Currently multiple samples from the same project can be pooled (up to five samples). A new sample
			is created with the same project name and a new number is assigned. Because previously we did not know how 
			samples would be pooled, no information from the samples used to create the pool is included in the newly created
			entry. Most fields will be changed to '(Pooled)' and time will be set to zero.
		      
			You will no longer be able to view or make changes to the original samples. To obtain information on the old samples,
			please use the 'Query Info' function. 
		      
			The new pooled sample entry will contain information in the notes as to which samples are included in the pool.
			You can also go to 'Query Info' -> 'Query Pooled Info' and look up all the samples which are included
			in each pool.
	</pre>
	
</body>
