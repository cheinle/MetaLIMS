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
<title>FAQ</title>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">-->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</head>

<body>
	<?php include ('index.php');?>
	<div class="page-header">
	<h3>FAQ</h3>
	</div>
	
	<pre class="border">
		 <b>Q: The web page looks lovely, but the layout is really crazy. What is wrong?</b> 
		      A: The database was designed primarily with Google Chrome and Firefox. Please use one of these
		      preferred browsers. Thanks!
		      
		  <b>Q: What is the 'Add and Copy' button on the Home-Insert Sample page?</b> 
		      A: It is a button that will allow you to save the input in the fields you just added. 
			  This is adventageous if you want to add multiple entries in a row which may have similar information to be added
		
		  <b>Q: How do I pool samples?</b> 
		 	  A: Pooling raw samples before processing simply add multiple samplers on the Insert Sample page and proceed as normal. 
		 	  If pooling already proccessed samples, please use 'Create Sample Pools' in the dropdown named 'Sample Management' on the main 
		 	  toolbar at the top of the screen. Pooling samples assumes that you have used up 
		 	  all of the original sample collected for each of the samples to be pooled. 
		 
		 
		  <b>Q: I need to add a new [project name/sampler/media type etc]. I don't see my selection in the drop down. What do I do?</b> 
		      A: Choose the appropriate selection in the 'Update Dropdowns in Insert Sample' dropdown tab and add your new [project name/sampler/media type etc]
		  
	
		  <b>Q: How will naming conventions change for each project? What is the naming convention for my current project?</b> 
		      A: Sample naming convention is as follows:  
		        
		      <i>Sample name convention
		      [date][project_name][sample type][sample number]


		      date - yyyy/mm/dd 
		      project name - name of your project 
		      sample type - A (air), W (water), S(surface), B(sterilization blank),BR (reagent blank), BFR (reagent + unused filter blank),BMF (blank microscopy or flow cytometry), F (fungal isolate), BC (bacterial isolate) , UI (unsure isolate)      
		      sample number - 3-digit sequential #  
 
		      ex:   2014/10/27test_projectA001 </i> 

		      Name will be automatically created for you based on sampling information provided.
		  
		
		  ***<b>Q: Do Blanks have a sampling time?</b>
		      A: Blanks for the Coil project have a sampling date, but no sampling time length.
		      Blanks for projects using the Coriolis can be set to the start time they were taken. If times are set for blanks
		      the end time will be set to equal the beginning time so that the total sampling time will equal 0-zero. This includes Reagent blanks
		  
		 **** <b>Q: Why am I not able to enter my sterilization blank at the same time as my air sample?</b>
		      A: If you are taking a blank for the a Coriolis sample, it is often taken about 10 minutes prior to the actual
		      sampling event. Please adjust your Coriolis sterilization blank time accordingly
		
		  <b>Q: Why is the search feature not working on my queried table?</b>
		      A: If you are searching for a word that contains spaces or is a substring of another word, 
		      try putting double quotes "" around your search. Ex: "Sam" would find the word 'Sam' but Sam 
		      without double quotes would also find 'Sample'
		  
		  <b>Q: How do I know if my project exisits or not?</b> 
		      A: You can check if your project exisits by either 1) going to the 'Insert Sample' tab 
		      the dropdown menu for 'Project Name' or 2) Going to the 'Query Sample Info' tab and searching for your 
		      project name using the 'Select Field to Query' function or 3) Going to 'Query Sample Info', clicking
		      on the 'More Query Options', and selecting to 'Display all DB Project Info'
		  
		  <b>Q: How do I know which sample number is next in a project?</b> 
		      A: Under 'Query Sample Info', search all samples in your project name by using 'Select Field to Query', selecting
		      'Project Name' and then typing in your project name. Press submit to view table. Click on 'Sample Sort' header to
		      sort samples in project by sample number
		      
		    <b>Q: Can I bulk update database entires?</b> 
		      A: Yes, for DNA extraction info you can bulk update entires under 'Query Sample Info'. Under 'Display Sample Info
		      Select search criteria and then select 'Bulk Update For DNA Extration Info' and press enter. Note that column selection
		      will not matter here. 
		      
		      You can also bulk update storage information the same way
		  
		
	</pre>
	
</body>
