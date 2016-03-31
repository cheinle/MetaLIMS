<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
include ('../index.php');
include('../functions/dropDown.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Form Builder</title>
</head>
 
<body>
<div class="page-header">
<h3>Sequence Submission Form Builder</h3>
</div>
<form class="registration" onsubmit="return validate(this)" action="choose_form_for_seq.php" method="POST">
	<div class="container-fluid">
  	<div class="row">
	<div class="col-xs-6">
	<fieldset>
	<LEGEND>Form Options:</LEGEND>
  	<?php $submitted = false; ?>
	<p>
	<label class="textbox-label">Sample Type:</label>
	<?php  dropDown('sample_type','type_seq_sample', 'sample_type', 'sample_type',$submitted,$root);?>
	</p>
	<div id="amplicon_info"></div>
	
	<p>
	<label class="textbox-label">Container Type:</label><br/>
	<?php  dropDown('container_type','container_type', 'container_type', 'container_type',$submitted,$root);?>
	</p>
	
	<p>
	<label class="textbox-label">Sequencing Method:</label><br/>
	<?php  dropDown('method','sequencing_method', 'method', 'method',$submitted,$root);?>
	</p>
	
	<p>
	<label class="textbox-label">Read Length:</label><br/>
	<?php  dropDown('read_length','read_length', 'read_length', 'read_length',$submitted,$root);?>
	</p>
	
	<!--<p>
	<label class="textbox-label">Quantitation Method:</label><br/>
	<?php  dropDown('quant_method','quantitation_method', 'quant_method', 'quant_method',$submitted,$root);?>
	</p>-->
	
	<p>
	<label class="textbox-label">Application:</label><br/>
	<?php  dropDown('application','application', 'application', 'application',$submitted,$root);?>
	</p>
	
	<p>
	<label class="textbox-label">Library Prep Kit:</label><br>
	<?php dropDown('libPK', 'library_prep_kit', 'lib_prep_kit','lib_prep_kit',$submitted,$root);?>
	</p>
	
	<p>
	<label class="textbox-label">Pooling (Y/N):</label><br>
	<select id='seq_pool' name='seq_pool';'>
		<option value='0'>-Select-</option>
		<option value="Yes">Yes</option>
		<option value = "No">No</option>
	</select>
	</p>
	</fieldset>
	</div>
	
	<div class="col-xs-6">
    <fieldset>
	<LEGEND>Select Samples:</LEGEND>
	<label class="textbox-label">Select Start Date:</label>
	<input type="text" id="smydate"  name="smydate">
	<label class="textbox-label">Select End Date:</label>
	<input type="text" id="emydate"  name="emydate">
	
	<script>
		$('#smydate').datepicker({ dateFormat: 'yy-mm-dd' }).val();
		$('#emydate').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	
	<p>
	<label class="textbox-label">Pick Project Your Samples Belong To:</label>
	<?php dropDown('projName', 'project_name', 'project_name','project_name',$submitted,$root);?>
	<p class='adjust'>
	<div id="sampleSelect" name="sampleSelect">
	</div>
	</p>
	</p>
	</fieldset>
	</div><!--end col-->
    
	</div> <!--end row-->
	
	<div class="row">
	<div class="col-xs-6">
	<fieldset>
	<legend>Details:</legend>
	<p>
	<label class="textbox-label">Date Submitted:</label><br>
	<input type="text" id="datepicker2"  name="dtSub" class="fields" value="<?php if (isset($_SESSION['submitted']) && $_SESSION['submitted'] == 'false') {echo htmlspecialchars($_SESSION['dtSub']);} ?>"/>
	<script>
	$('#datepicker2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
	</script>
	</p>
	
    <p>
	<label class="textbox-label">Submission Made By:</label><br>
	<?php dropDown('submittedBy', 'users', 'user_id','user_id',$submitted,$root);?>
	</p>
    </fieldset>
    </div>
    
 
    </div>
	</div>
    <button class = "button" type="submit" name="submit" value="1">Submit</button>
    
    			
<script type="text/javascript">
     function validate(from) {
       //if you tried to submit, check the entire page for color?
       //return valid is false if you find it
       var valid = 'true';
       if(check_form() == 'false'){
       		valid = 'false';    
       }
       if(valid == 'false'){
       		alert('ERROR: Some inputs are invalid. Please check fields');
            return false;
       }
       else{
       		return confirm('Are you sure you want to submit?');
       }
    }
       
       function check_form(){
             var index;
             var valid = 'true';
             
             var inputs = document.getElementsByTagName("input");
             var txt = "";
             var i;
             for (i = 0; i < inputs.length; i++) {
                 txt = inputs[i].value;
                 var name = inputs[i].getAttribute("name");
                 if(name == 'seqOther'){
                 	continue;	
                 }
                 else{
	                 //check if your input is empty
	                 var n = txt.length;
	                 if(n == 0){
	                    inputs[i].style.background = "blue";
	                    valid = 'false';
                 	}
                 }
                 
			}
			
			//check selects
			var selects = document.getElementsByTagName("select");
            var i2;
             for (i2 = 0; i2 < selects.length; i2++) {
                 selected = selects[i2].value;
                 if(selected == '0'){
                 	selects[i2].style.background = "blue";
                    valid = 'false';
                 }
			}
			
			//check amplicon info 
			var amplicon_check = document.getElementById('sample_type');
			amplicon_check = amplicon_check.value;
			if(amplicon_check == 'Amplicon'){
				//check that any radio button checked
				//if radio button checked is amplicon-other, check if other amplicon type is filled in
				var amplicon_type = document.getElementsByName('amplicon_type');
				var ischecked_method = false;
				for ( var i = 0; i < amplicon_type.length; i++) {
				    if(amplicon_type[i].checked) {
				        ischecked_method = true;
				        //break;
				    }
				    if(amplicon_type[i].value == 'AmpliconOther'){
				    	var seqOther = document.getElementById('seqOther');
				    	if(seqOther.value.length == 0){
				    		seqOther.style.background = "blue";
				    		valid = 'false';
				    	}
				    }
				}
				if(!ischecked_method)   { 
				    alert("Please choose Amplicon Type");
				    valid = 'false';
				}
			
				//check that left and right primer set names are filled in
					//these should be taken care of in the regular input check
			}
             return valid;
       }

</script>


