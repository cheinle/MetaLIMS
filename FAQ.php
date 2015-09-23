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
		
		
		  <b>Q: What is a pool name? (Depricated as of: 2015/05/29. Please use 'Pool Samples' tab)</b> 
		      A: In the case of 'pool extracts name', this is an id given to a group of samples that have been pooled for use for downstream processing. 
		      This specific case refers to pooled samples for DNA extraction. Pool extract names are user created.
		  
		  <b>Q: How do I pool samples?</b> 
		 	  A: Please use 'Pool samples' tab at the top of the screen. Pooling samples assumes that you have used up 
		 	  all of the original sample collected for each of the samples to be pooled
		 
		 
		  <b>Q: I need to add a new [project name/air sampler/media type etc]. I don't see my selection in the drop down. What do I do?</b> 
		      A: Choose the appropriate selection in the 'Update Dropdowns in Insert Sample' dropdown tab and add your new [project name/air sampler/media type etc]
		  
	
		  <b>Q: How will naming conventions change for each project? What is the naming convention for my current project?</b> 
		      A: Sample naming conventions may fluxuate as the project changes. Relative location and pump number may vary or 
		      become obsolete depending on the project. For the current project: 'Coil', the naming convention is as follows:  
		        
		      <i>Sample name convention (Project: Coil) 
		      [date][project_name][relative location][sample type][pump #][sample number]

		      date - yyyy/mm/dd 
		      project name - name of your project 
		      relative location - i.e. upstream, downstream,ambient **
		      sample type - A (air), W (water), S   (surface),B(sterilization blank),BR (reagent blank), BFR (reagent + unused filter blank))     
		      pump # - number assigned to each pump,   'np' used for water and passive filters **      
		      sample number - 3-digit sequential #  
		      (no  mandatory time of day or pump order to dictate)     
		      ex:   2014/10/27Coil_Airdown_A_pump5_sample001 </i> 
              	
		      ** = only use for Coil projects. 
              	
		      Update: All new projects will follow the below format:
              	
		      date - yyyy/mm/dd 
		      project name - name of your project 
		      sample type - A (air), W (water), S(surface), B(sterilization blank),BR (reagent blank), BFR (reagent + unused filter blank),BMF (blank microscopy or flow cytometry), F (fungal isolate), BC (bacterial isolate) , UI (unsure isolate)      
		      sample number - 3-digit sequential #  
		      (no  mandatory time of day or pump order to dictate)    
		      ex:   2014/10/27NewProjA001 </i> 

		      Name will be automatically created for you based on sampling information provided.
		  
		
		  <b>Q: Do Blanks have a sampling time?</b>
		      A: Blanks for the Coil project have a sampling date, but no sampling time length.
		      Blanks for projects using the Coriolis can be set to the start time they were taken. If times are set for blanks
		      the end time will be set to equal the beginning time so that the total sampling time will equal 0-zero. This includes Reagent blanks
		  
		  <b>Q: Why am I not able to enter my sterilization blank at the same time as my air sample?</b>
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
		  
		
		  <b>Q: Why does my rain data show all 9's?</b> 
		      A: A series of 9's representing your rain data means that there was defective rain data reported for that day
			
		  <b>Q: How do I know which sample number is next in a project?</b> 
		      A: Under 'Query Sample Info', search all samples in your project name by using 'Select Field to Query', selecting
		      'project_name' and then typing in your project name. Press submit to view table. Click on 'sample_sort' header to
		      sort samples in project by sample number
		      
		  <b>Q: How are Sequencing Submission ID's created?</b> 
		      A: Prior to April 20,2015 sequence ID's were created by the submitter. The previous projects include the sequence
		         submission ID used by the submitter (with the exception of 'Coil', which was submitted with various ID's C, CW, and M).
		         New Sequence Submission ID's are created using the first two letters of the project name + the capital letters of the 
		         camel cased name + any number included (project numbers assumed to be single digit)
		      
		  <b>Q: Why are some project names camel cased and some are not?</b> 
		      A: Project names began to be camel cased as a way of uniforming the project names. Previous project names were not updated in 
		      order to prevent possible conflicts in records
		      
		  <b>Q: Can I bulk update database entires?</b> 
		      A: Yes, for DNA extraction info you can bulk update entires under 'Query Sample Info'. Under 'Display Sample Info
		      Select search criteria and then select 'Bulk Update For DNA Extration Info' and press enter. Note that column selection
		      will not matter here
		      
		  <b>Q: Why do I have to sign in to browse/upload files? What is my username/password?</b> 
		      A: Sign in is an extra layer of protection for our filesystem. Your username should be your NTU email address. Please
		      see admin for password info
		
		
	</pre>
	
</body>
