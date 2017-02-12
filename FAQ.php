<?php 
include ('index.php');
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
		      sample type - B(Blank-sterilization),P(Pooled) [these two come pre-loaded. Please have your admin add desired types]
		      sample number - 3-digit sequential #  
 
		      ex:   2014/10/27test_projectB001 </i> 

		      Name will be automatically created for users based on sampling information provided.
		  
		
		  <b>Q: Do Blanks have a sampling time?</b>
		      A: Blank- Sterilization do not have a sampling duration regardless of what is entered for sample
		      
		  <b>Q:What are are all of the sample types and their abbreviations?</b> 
			A: 
		      <?php 
		      	echo "<table style=\"margin-left: 10%\";>";
				echo "<tr><td><strong>Sample Type</strong></td><td><strong>Sample Type ID</strong></td></tr>";
		      	$stmt = $dbc->prepare("SELECT sample_type_name,sample_type_id FROM sample_type");
				if(!$stmt){;
					die('prepare() failed: ' . htmlspecialchars($stmt->error));
				}
				if ($stmt->execute()){
					$stmt->bind_result($sample_type,$sample_type_id);
						
					$array = array();
					while ($stmt->fetch()){
							echo "<tr><td>".$sample_type."</td><td>".$sample_type_id."</td></tr>";	
					}
				}
				$stmt->close();
				echo "</table>"; 
				 
		      ?>

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
		      

		  <b>Q: I see the column 'Sequencing Base Submission ID' when I query a sample. What is this? </b> 
			      A: The current base id is the standard id created for sequencing submission. This ID is created by the user when they request a project name
			      and are used as an abbreviatin for the project. 
			      
			      The current sequencing submission names are as follows:
			      [Sequencing Base Submission ID]-[type of sequencing]-[number of times sample has been submitted for this type of sequencing]
			      	
			      Types of sequencing are: 
			      <?php
			      		echo "<table style=\"margin-left: 10%\";>";
						echo "<tr><td><strong>Sequencing Type</strong></td><td><strong>Sequencing Type ID</strong></td></tr>";
				      	$stmt = $dbc->prepare("SELECT application,application_abbrev FROM application");
						if(!$stmt){;
							die('prepare() failed: ' . htmlspecialchars($stmt->error));
						}
						if ($stmt->execute()){
							$stmt->bind_result($application,$application_abbrev);
								
							$array = array();
							while ($stmt->fetch()){
									echo "<tr><td>".$application."</td><td>".$application_abbrev."</td></tr>";	
							}
						}
						$stmt->close();
						echo "</table>"; 
				?>
					
				Ex: FSTP001-A-01 
					
				Would be a sample from the project 'first-project' and submitted one time for amplicon sequencing
	 		
	 			The Sequencing Base Submission ID can be used by bioinformaticists to seach for the actual sample in the db.
	 			To find more information about the actual sequencing, please query in the 'Sequencing Submission Info' tab on the 
	 			main toolbar
	 				
	 				
		
				*Please see MetaLIMS wiki for more details: https://github.com/cheinle/MetaLIMS/wiki
	</pre>
	
</body>
