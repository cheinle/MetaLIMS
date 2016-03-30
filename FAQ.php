<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FAQ</title>
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
		      
		  <b>Q: How do I pool samples?</b> 
		 	  A: Pooling raw samples before processing simply add multiple samplers on the Insert Sample page and proceed as normal. 
		 	  If pooling already proccessed samples, please use 'Create Sample Pools' in the dropdown named 'Sample Management' on the main 
		 	  toolbar at the top of the screen. Pooling samples assumes that you have used up 
		 	  all of the original sample collected for each of the samples to be pooled. 
		 
		 
		  <b>Q: I need to add a new [project name/sampler/media type etc]. I don't see my selection in the drop down. What do I do?</b> 
		      A: Choose the appropriate selection in the 'Update Sample Fields' dropdown tab and add your new [project name/sampler/media type etc]
		  
	
		  <b>Q: How will naming conventions change for each project? What is the naming convention for my current project?</b> 
		      A: Sample naming convention is as follows:  
		        
		      <i>Sample name convention
		      [date][project_name][sample type][sample number]


		      date - yyyy/mm/dd 
		      project name - name of your project 
		      sample type - B(sterilization blank),P(pooled) [these two come pre-loaded. Please have your admin add desired types]
		      sample number - 3-digit sequential #  
 
		      ex:   2014/10/27test_projectB001 </i> 

		      Name will be automatically created for users based on sampling information provided.
		  
		
		  <b>Q: Do Blanks have a sampling time?</b>
		      A: Blank- Sterilization do not have a sampling duration regardless of what is entered for sample
		      

		  <b>Q: Why is the search feature not working on my queried table?</b>
		      A: If you are searching for a word that contains spaces or is a substring of another word, 
		      try putting double quotes "" around your search. Ex: "Sam" would find the word 'Sam' but Sam 
		      without double quotes would also find 'Sample'
		  
		  <b>Q: How do I know what project exists or not?</b> 
		      A: You can check if your project exisits by either 1) going to the 'Insert Sample' tab 
		      the dropdown menu for 'Project Name' or 2) Going to the 'Query Sample Info' tab and searching for your 
		      project name using the 'Select Field to Query' function or 3) Going to 'Query Sample Info', clicking
		      on the 'More Query Options', and selecting to 'Display all DB Project Info'
		  
		  <b>Q: How do I know which sample number is next in a project?</b> 
		      A: Under 'Query Sample Info', search all samples in your project name by using 'Select Field to Query', selecting
		      'Project Name' and then typing in your project name. Press submit to view table. Click on 'Sample Sort' header to
		      sort samples in project by sample number
		      
		    <b>Q: Can I bulk update database entires?</b> 
		      A: Yes, for DNA extraction, storage info, read submission info, and user created fields you can bulk update entires under 'Query Sample Info'. Under 'Display Sample Info
		      Select search criteria and then select for example select 'Bulk Update For DNA Extration Info' and press enter. Note that column selection
		      will not matter here. 
		      
		  
		
	</pre>
	
</body>
