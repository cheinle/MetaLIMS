
<?php include('../database_connection.php'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sequencer Info Update</title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>	
</head>
<body>
<?php
include('config/path.php');
$path = $_SERVER['DOCUMENT_ROOT'].$root;
include($path.'index.php');
include($path.'functions/dropdown.php'); 
?>
<pre>
	
	
<h3>Update Sequencing Info Dropdown </h3>	
	<?php 
		//error && type checking 
		$submitted = 'false';
		if(isset($_GET['submit'])){
			//print_r($_GET);
			$error = 'false';
			
			//sanatize user input to make safe for browser
			$p_projName = htmlspecialchars($_GET['projName']);
			$p_seqName = htmlspecialchars($_GET['seqName']);
			$p_dtSub = htmlspecialchars($_GET['dtSub']);
			$p_libPK = htmlspecialchars($_GET['libPK']);
			$p_vofa = htmlspecialchars($_GET['vofa']);
			$p_dcSub = htmlspecialchars($_GET['dcSub']);
			$p_seqOther = htmlspecialchars($_GET['seqOther']);
			
			
			if($p_seqName == '0'){
					echo '<p>ERROR: You must enter Sequencer Name!<p>';
					$error = 'true';
			}
			if($p_dtSub  == ''){
				echo '<p>ERROR: You must enter a Date Submitted!<p>';
				$error = 'true';
			}
			if($p_libPK == '0'){
				echo '<p>ERROR: You must select a Library Prep Kit!<p>';
				$error = 'true';
			}
			if($p_vofa == ''){
				echo '<p>ERROR: You must enter a Volume of Aliquot!<p>';
				$error = 'true';
			}
			if($p_dcSub == ''){
				echo '<p>ERROR: You must enter a DNA Concentration of Submission!<p>';
				$error = 'true';
			}
			
			$p_seqInfo = $p_dtSub.$p_projName.'seq_submission';
			
			//your 'other' amplicon sequencing region is always stored at the end of the array
			$i=3;
			while($i>=0){
				if((isset($_GET['type'][$i])) && (($_GET['type'][$i]) == 'AmpliconOther')){
					$_GET['type'][] = $p_seqOther;
					
				}$i--;
			}

			//check and process sequencing type info
			if(isset($_GET['type'])){
				include("check_sequencing_type.php");
				$array=$_GET['type'];
				
				$check = check_sequencing_types($array,'true');
				if($check['boolean'] == 'false'){
					echo '<p>ERROR: You must Enter valid Sequencing Type(s). Please check type(s)<p>';
					$error = 'true';
				}
				else{
					$p_typeName = $check['cat_name'];
				}
			}
			else{
				echo '<p>ERROR: You must Enter valid Sequencing Type(s). Please check type(s)<p>';
				$error = 'true';
			}
			
			//check DNA concentration
			$regrex_check_dc  = '/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*/';
			if (!preg_match($regrex_check_dc, $p_dcSub)){
					echo '<p>ERROR: You Must Enter Valid DNA Submission Concentration. Please Check Your Number.<p>';
					$error = 'true';
			}
			
			//check volume aliquot
			$regrex_check_vol  = '/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*/';
			if (!preg_match("$regrex_check_vol", $p_vofa)){
					echo '<p>ERROR: You Must Enter Valid Submission Volume. Please Check Your Number.<p>';
					$error = 'true';
			}
			
			//check sequence info name exists
			$stmt1 = $dbc->prepare("SELECT sequencing_info FROM sequencing2 WHERE sequencing_info = ?");
			$stmt1 -> bind_param('s', $p_seqInfo);
			$stmt1->bind_result($col1);
				
  			if ($stmt1->execute()){
    			$stmt1->bind_result($name);
    			if ($stmt1->fetch()){
        			echo "Name: {$name}<br>";
        			#echo 'Another way:'.print_r($row, true); //won't work with bind_result
        			if($name == $p_seqInfo){
        				echo $p_seqInfo." exits. Please check name.";
						$error = 'true';
					}
				}
    			else {
        			echo "Name exisits: No results <br>";//no result came back so free to enter into db, no error
					
    			}
			} 
			else {
				$error = 'true';
    			die('execute() failed: ' . htmlspecialchars($stmt->error));
				
			}
			#echo 'done';
			$stmt1 -> close();
	
			

			//insert info into db
		    if($error != 'true'){
		    
				$stmt2 = $dbc -> prepare("INSERT INTO sequencing2 (sequencing_info,sequencing_type,sequencer_name,date_submitted,library_prep_kit,volume_of_aliquot,dna_conc_of_subm) VALUES (?,?,?,?,?,?,?)");
				$stmt2 -> bind_param('sssssii', $p_seqInfo,$p_typeName, $p_seqName,$p_dtSub,$p_libPK,$p_vofa,$p_dcSub);
				
				if($stmt2 -> execute()){
					$rows_affected2 = $stmt2 ->affected_rows;
					$stmt2 -> close();
					
					//check if add was successful or not. Tell the user
			   		if($rows_affected2 > 0){
						echo 'You added new Seqencing Submission Info:'.$p_seqInfo.'<br>';
						$submitted = 'true';
					}else{
						
						echo 'An error has occured';
						mysqli_error($dbc);
					}
				}
				else{
					die('execute() failed: ' . htmlspecialchars($stmt2->error));
				}
			}
		}
	?>
</pre>
	
	<form class="navbar-form" action="update_seq_info.php" method="GET">
	* = required field <!--arbitrary requirement at this moment-->
		<fieldset>
		<LEGEND><b>Sequencing Info:</b></LEGEND>
		<!--Sequencing Name-->
		<!--<p>
		<label>Sequencing Submission Info Name:*</label><br>
		<input type="text" name="seqInfo" class="fields" placeholder="[YYYY/MM/DD][project name]seq_submission[001]" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_seqInfo;} ?>"<br>
		</p>-->
		
		<!--Date Submitted-->
		<p>
		<label>Date Submitted:*</label><br>
		<input type="text" id="datepicker2"  name="dtSub" class="fields" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['dtSub']);} ?>"/>
		<script>
		$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		</script>
		
		
		<p>
		<!--Project Name Dropdown-->
		<label for="project_name">Select Project Name:*</label><br/>
		<?php
		//url or $_GET name, table name, field name
		dropDown('projName', 'project_name', 'project_name','project_name',$submitted);
		?>
		</p>
				

		<label>Sequencing Type:* (pick all that apply)</label><br>
		<p>
		<input type="checkbox" name="type[]" value="Amplicon16S" <?php if(isset($_GET['submit']) && $submitted != 'true'){$i=3;while($i>=0){if((isset($_GET['type'][$i])) && (($_GET['type'][$i]) == 'Amplicon16S')){echo "checked";}$i--;}} ?>/>Amplicon-16S<br />
		<input type="checkbox" name="type[]" value="Amplicon18S" <?php if(isset($_GET['submit']) && $submitted != 'true'){$i=3;while($i>=0){if((isset($_GET['type'][$i])) && (($_GET['type'][$i]) == 'Amplicon18S')){echo "checked";}$i--;}} ?>/>Amplicon-18S<br />
		<input type="checkbox" name="type[]" value="AmpliconOther" <?php if(isset($_GET['submit']) && $submitted != 'true'){$i=3;while($i>=0){if((isset($_GET['type'][$i])) && (($_GET['type'][$i]) == 'AmpliconOther')){echo "checked";}$i--;}} ?>/>Amplicon-other
		<div id="appear_div">
		<input type="text" name="seqOther" class="fields" placeholder="Enter Other Sequencing Type" value="<?php if(isset($_GET['submit']) && $submitted != 'true'){ echo $p_seqOther;} ?>"<br>
 		</div>
		<input type="checkbox" name="type[]" value="Metagenome"  <?php if(isset($_GET['submit']) && $submitted != 'true'){$i=3;while($i>=0){if((isset($_GET['type'][$i])) && (($_GET['type'][$i]) == 'Metagenome')){echo "checked";}$i--;}} ?>/>Metagenome<br />
		</p>
		
		<!--Sequencer Name-->
		<p>
		<label>Sequencer Name:*</label><br>
		<?php
		//url or $_GET name, table name, field name
		dropDown('seqName', 'sequencer_names', 'seqName','seqName',$submitted);
		?>
		</p>
		
		
		<p>
		<!--Library Prep Kit-->
		<label>Library Prep Kit:*</label><br>
		<?php
		//url or $_GET name, table name, field name
		dropDown('libPK', 'library_prep_kit', 'lib_prep_kit','lib_prep_kit',$submitted);
		?>
		</p>
		
		
		<!--Volume of Aliquot-->
		<p>
		<label>Volume of Aliquot (uL):*</label><br>
		<input type="text" name="vofa" class="fields" placeholder="Enter Volume of Aliquot  " value="<?php if(isset($_GET['submit']) && $submitted != 'true'){echo $p_vofa;} ?>">
		</p>
		
		<!--DNA Concentration of Submission-->
		<p>
		<label>DNA Concentration of Submission (ng/ul):*</label><br>
		<input type="text" name="dcSub" class="fields" placeholder="Enter A DNA Contration of Submitted Sample" value="<?php if(isset($_GET['submit'])&& $submitted != 'true'){echo $p_dcSub;} ?>">
		</p>
		
		<!--submit button-->
		<p><button class="btn btn-success" type="submit" name="submit" value="1"> Add </button></p>
		<p><input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" /></p>
		</fieldset>
		
	</form>
	
	

	
</body>
	
</html>
