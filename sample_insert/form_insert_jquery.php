<?php
header("X-UA-Compatible: IE=Edge");
if(!isset($_SESSION)) { session_start(); }
include ('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
include($path.'config/check_form_insert_js.php');
include($path.'config/check_sample_name.php');
include($path.'config/check_required_user_things_js.php');
include($path.'admin_tools/dynamically_add_user_fields/build_a_user_thing.php');
include('form_insert_jquery_js.php');
include ($path.'functions/dropDown.php');
//$root = $_SESSION['link_root'];
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Form Insert</title>
	
	</head>
 
	<body>
		
		<div class="page-header">
		<h3>Sample Insert Form</h3>
		<?php $submitted = 'false';?>
		</div>
		
		<!--<form class = "registration" id="form" name="form">-->
		<form class="form-horizontal" id="form" name="form" action="" method="post" style="width: 90%; margin-left:5%;"> 
		<div id="tabs">
				  <ul>
				     <li><a href="#fragment-1"><span>Collection Info</span></a></li>
					 <li><a href="#fragment-2"><span>DNA/RNA Extraction</span></a></li>
					 <li><a href="#fragment-3"><span>Analysis</span></a></li>
					 <li><a href="#fragment-4"><span>User Created Fields</span></a></li>
					 <li><a href="#fragment-5"><span>Notes</span></a></li>
				  </ul>
			
			<br>* = required field <br>
			<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>

			<!--table insert form-->

			<div id='samplename_availability_result'></div>
			<div class="container-fluid">
  				<div class="row">  
					
			<!--sample-->
			
  					<div id="fragment-1">
  						<fieldset>
						<LEGEND><b>Sample Collection Info</b></LEGEND>
  						<div class="col-xs-12 col-sm-6 col-md-6">
							<!--Sample number field-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Sample Number:*</label>  
							  <div class="col-md-8">
							 <input type="text" name="sample_number" id = "sample_number" placeholder="[001]" value="" class="form-control input-md" required="">
							  </div>
							</div>
								
							<!--Barcode insert field-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Barcode:(optional)</label>  
							  <div class="col-md-8">
							  <input type="text" name="barcode" id="barcode" placeholder="Enter A Barcode" value="" class="form-control input-md">
							  </div>
							</div>
							
							<!--Project Name Dropdown-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Select Project Name:*</label>
							  <div class="col-md-8">
								  <?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('projName', 'project_name', 'project_name','project_name',$submitted,$root,'yes');
								?>
							  </div>
							</div>
							
							
							
							<!--location dropdown-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Select Location:*</label>
							  <div class="col-md-8">
								  <?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('loc', 'location', 'loc_name','loc_name',$submitted,$root,'yes');
								?>
							  </div>
							</div>
							
							
							<!--rel location dropdown-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Select Relative Location:*</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('rloc', 'relt_location', 'loc_name','loc_name',$submitted,$root,'yes');
								?>
							  </div>
							</div>
							
							<!--media type dropdown-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Media Type:*</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('media', 'media_type', 'media_type','media_type',$submitted,$root,'yes');
								?>
							  </div>
							</div>
							
							<!--Collector Name input-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Enter Collector Name(s):*</label>
							  <div class="col-md-8">
								<input type="text" name="collector[]" id="collector" placeholder="Comma Seperated Names" value="" class="form-control input-md" required=""/>
							  </div>
							</div>
							
						</div><!--col-xs-6-->
				
				  		<div class="col-xs-12 col-sm-6 col-md-6">
	
							<!--Sampling Type insert field-->
							<div class="form-group">
							  <label class="col-md-3 control-label" >Sample Type:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Please See FAQ"></i></label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required, value
								dropDown('sType', 'sample_type', 'sample_type_name','sample_type_id',$submitted,$root,'yes');
								?>
							  </div>
							</div>

			
							<!--Sample Storage-->
							<div class="form-group">
							  <label class="col-md-3 control-label" >Storage Location:* (pick freezer and drawer owner)</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('oStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root,'yes');
								?>
								<select id="oStore_name" name ="oStore_name" class='form-control' required>
			 					<?php echo '<option value="">-Select-</option>';?>
			 					</select>	
							  </div>
							</div>
							
							
							
							<!-- Multiple Radios (inline) -->
							<div class="form-group">
							  <label class="col-md-3 control-label" for="radios">Does Original Sample Still Exist?</label>
							  <div class="col-md-4"> 
							<label class="radio-inline" for="radios-0">
							  <input type="radio" name="orig_sample_exist" id="orig_sample_exist1" value="true" checked="checked">
							  Yes
							</label> 
							<label class="radio-inline" for="radios-1">
							  <input type="radio" name="orig_sample_exist" id="orig_sample_exist2" value="false">
							  No
							</label>
							  </div>
							</div>
				

							
							<!--sampler dropdown-->
							<div class="form-group">
								<label class="col-md-3 control-label">Select Number of Samplers:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="This is the number of samplers used to create the original sample. Field assumes different sampler names. Also, please note that all sample type-blank will have a sampling time duration of zero"></i></label>
								<div class="col-md-8">
								<select id='sampler_num' name='sampler_num' class='form-control' required>
								<option value=''>-Select-</option>
								<option value='1'>-1:One-</option>
								<option value='2'>-2:Two-</option>
								<option value='3'>-3:Three-</option>
								<option value='4'>-4:Four-</option>
								<option value='5'>-5:Five-</option>
								<option value='6'>-6:Six-</option>
								</select>
					
								<div id="div_sampler_num"></div>
								</div>
							</div>
							
					</fieldset>
					</div><!--close fragment-1-->
			
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->

					<div id="fragment-2">
					<fieldset>
					<div class="col-xs-12 col-sm-6 col-md-6">
					<LEGEND><b>DNA Extraction Info</b></LEGEND>
							<div id="dna_extraction">	
								
							<!--DNA Extraction Date-->	
							<div class="form-group">
							  <label class="col-md-3 control-label">DNA Extraction Date:</label>
							  <div class="col-md-8">
								<input type="text" id="d_extr_date"  name="d_extr_date" value="" class="form-control input-md"/>
								<script>
								$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
								</script>
							  </div>
							</div>
							
							<!--DNA Extraction Kit dropdown-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Select DNA Extraction Kit:</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name',$submitted,$root,'no');
								?>
							  </div>
							</div>
							
							
							<!--DNA Concentration-->
							<div class="form-group">
							  <label class="col-md-3 control-label">DNA Concentration (ng/ul):</label>
							  <div class="col-md-8">
								<input type="text" name="dConc" id="dConc" placeholder="Enter A DNA Concentration" value="" class="form-control input-md">
							  </div>
							</div>

	
							<!--Volume of DNA-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Volume of DNA Elution (ul):</label>
							  <div class="col-md-8">
								<input type="text" name="dVol"  id="dVol" placeholder="Enter A Volume" value="" class="form-control input-md">
							  </div>
							</div>
							
								
							<!--Instrument used to measure DNA concentration-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Instrument/Kit Used to Measure DNA Concentration:</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('dInstru', 'quant_instruments', 'kit_name','kit_name',$submitted,$root,'no');
								?>
							  </div>
							</div>
							
							
							<!--Volume of DNA to measure DNA conc-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Volume of DNA for Quantification(ul):</label>
							  <div class="col-md-8">
								<input type="text" name="dVol_quant" id="dVol_quant"  placeholder="Enter A Volume" value="" class="form-control input-md">
							  </div>
							</div>
							
							<!--DNA -->
							<div class="form-group">
							  <label class="col-md-3 control-label">Location of DNA Extract:(pick freezer and drawer owner)</label>
							  <div class="col-md-8">
								<?php
								//$_GET name, table name, field name, id name, form submitted, root path, required
								dropDown('dStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root,'no');
								?>
							
								<select id="dStore_name" name ="dStore_name" class="form-control dna">
				 					<?php echo '<option value="0">-Select-</option>';?>
				 				</select>	
							  </div>
							</div>

							<!--DNA Extractor Name input-->
							<div class="form-group">
							  <label class="col-md-3 control-label">Enter Name(s) of Persons Who Extracted DNA:</label>
							  <div class="col-md-8">
								<input type="text" name="dExtrName[]" id = "dExtrName" placeholder="Comma Seperated Names" value=""  class="form-control input-md"/>
						  	  </div>
							</div>
							
							  
							  
							<!--Original sample exists?-->
							<!--<div class="form-group">
							  <label class="col-md-3 control-label" for="checkboxes">Does Original Sample Still Exist?</label>
							  <div class="col-md-4">
							  <div class="checkbox">
							    <label for="checkboxes-0">
							      <input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" />
							      No
							    </label>
								</div>
							  </div>
							</div>-->
								
							<!--DNA sample exists?-->	
							<div class="form-group">
							  <label class="col-md-3 control-label" for="DNA_sample_exist">Does DNA Extraction Sample Still Exist?</label>
							  <div class="col-md-8"> 
							    <label for="DNA_sample_exist-0">
							      <input type="radio" name="DNA_sample_exist" id="DNA_sample_exist-0" value="one">
							      Yes,DNA sample exists
							    </label> 
							    <label for="DNA_sample_exist-1">
							      <input type="radio" name="DNA_sample_exist" id="DNA_sample_exist-1" value="two" checked>
							      No, DNA Has Not Been Extracted
							    </label> 
							    <label for="DNA_sample_exist-2">
							      <input type="radio" name="DNA_sample_exist" id="DNA_sample_exist-2" value="three">
							      No, DNA Sample Is Used Up
							    </label> 
							  </div>
							</div>
							
							</div><!--close dna extraction-->
						</div><!--close col-xs-6-->
					
						<div class="col-xs-12 col-sm-6 col-md-6">
						<LEGEND><b>RNA Extraction Info</b></LEGEND>	
						
							<div id="rna_extraction">
								
								<div class="form-group">
								  <label class="col-md-3 control-label">RNA Extraction Date:</label>
								  <div class="col-md-8">
									<input type="text" id="r_extr_date"  name="r_extr_date" value="" class="form-control input-md"/>
									<script>
									$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
									</script>
							  	  </div>
								</div>
								
								<!--RNA Extraction dropdown-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Select RNA Extraction Kit:</label>
								  <div class="col-md-8">
									<?php
									//$_GET name, table name, field name, id name, form submitted, root path, required
									dropDown('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name',$submitted,$root,'no');
									?>
							  	  </div>
								</div>

								<!--RNA Concentration-->
								<div class="form-group">
								  <label class="col-md-3 control-label">RNA Concentration (ng/ul):</label>
								  <div class="col-md-8">
									<input type="text" name="rConc" id="rConc" placeholder="Enter an RNA Concentration" value="" class="form-control input-md">
							  	  </div>
								</div>
										
								<!--RNA Volume-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Volume of RNA Elution (ul):</label>
								  <div class="col-md-8">
									<input type="text" name="rVol" id="rVol" placeholder="Enter A Volume" value="" class="form-control input-md">
							  	  </div>
								</div>

								<!--Instrument used to measure RNA concentration-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Instrument/Kit Used to Measure RNA Concentration:</label>
								  <div class="col-md-8">
										<?php
										//$_GET name, table name, field name, id name, form submitted, root path, required
										dropDown('rInstru', 'quant_instruments', 'kit_name','kit_name',$submitted,$root,'no');
										?>
							  	  </div>
								</div>
								
								<!--RNA Volume-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Volume of RNA for Quantification(ul):</label>
								  <div class="col-md-8">
									<input type="text" name="rVol_quant" id="rVol_quant" placeholder="Enter A Volume" value="" class="form-control input-md">
							  	  </div>
								</div>
								
								
							
								<!--RNA storage-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Location of RNA Extract:(pick freezer and drawer owner)</label>
								  <div class="col-md-8">
										<?php
										//$_GET name, table name, field name, id name, form submitted, root path, required
										dropDown('rStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root,'no');
										?>
										
										<select id="rStore_name" name ="rStore_name" class="form-control">
					 					<?php echo '<option value="0">-Select-</option>';?>
					 					</select>
							  	  </div>
								</div>
								
								<!--RNA Extractor Name input-->
								<div class="form-group">
								  <label class="col-md-3 control-label">Enter Name(s) of Persons Who Extracted RNA:</label>
								  <div class="col-md-8">
									<input type="text" name="rExtrName[]" id="rExtrName" placeholder="Comma Seperated Names" value="" class="form-control input-md"/>
							  	  </div>
								</div>
								
							
								<!-- original sample exist?-->
								<!--<div class="form-group">
								  <label class="col-md-3 control-label" for="checkboxes">Does Original Sample Still Exist?</label>
								  <div class="col-md-4">
								  <div class="checkbox">
								    <label for="checkboxes-0">
								      <input type="checkbox" name="orig_sample_exist" id="orig_sample_exist" value="false" />
								      No
								    </label>
									</div>
								  </div>
								</div>-->
								
								<!--RNA sample exist?-->
								<div class="form-group">
								  <label class="col-md-3 control-label" for="RNA_sample_exist">Does RNA Extraction Sample Still Exist?</label>
								  <div class="col-md-8"> 
								    <label for="RNA_sample_exist-0">
								      <input type="radio" name="RNA_sample_exist" id="RNA_sample_exist-0" value="one">
								      Yes,RNA Sample Exists
								    </label> 
								    <label for="RNA_sample_exist-1">
								      <input type="radio" name="RNA_sample_exist" id="RNA_sample_exist-1" value="two" checked>
								      No, RNA Has Not Been Extracted
								    </label> 
								    <label for="RNA_sample_exist-2">
								      <input type="radio" name="RNA_sample_exist" id="RNA_sample_exist-2" value="three">
								      No, RNA Sample Is Used Up
								    </label> 
								  </div>
								</div>
							</div><!--close rna extraction-->
						</div><!--close col-xs-6-->
					</fieldset>
	
					</div><!--close fragment-2-->

					<div id="fragment-3">
						<LEGEND><b>Downstream Analysis Info</b></LEGEND>
							<!--Analysis Pipeline Name Dropdown-->
							<div class="form-group">
							<label class="col-md-3 control-label">Select Analysis Pipeline:</label>
							<div class="col-md-8">
							<?php
							//url or $_GET name, table name, field name
							dropDown('anPipe', 'analysis', 'analysis_name','analysis_name',$submitted,$root);
							?>
							</div>
						</div>
					</div><!--fragment-3-->
					

					<div id="fragment-4">
					<fieldset>
					<LEGEND><b>User Created Fields</b></LEGEND>
						<input type="text" style="visibility:hidden" class="hidden" name="build_type" id="build_type" value="insert"/>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<legend>Required Fields</legend>
							<div id="required_things1" >
								
							</div>
							<legend>Non-Required Fields</legend>
							<div id="user_things1">
								
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<legend>...</legend>
							<div id="required_things2">
								
							</div>
							<legend>...</legend>
							<div id="user_things2">
								
							</div>
						</div>
					</fieldset>
					</div><!--end fragment-4-->
					
					<div id="fragment-5">
					<fieldset>
					<LEGEND><b>Notes</b></LEGEND>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
							  <label class="col-md-2 control-label" for="textarea">Sample Notes:(optional)</label>
							  <div class="col-md-8">                     
							    	<textarea class="form-control" from="sample_form" rows="3" name="notes" id="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)" class="form-control"></textarea>
							  </div>
							</div>
						</div><!--close col-md-12-->
						
						<!--Invisible Project Name Dropdown-->
						<?php  $entered_by = $_SESSION['first_name'].' '.$_SESSION['last_name']; // ?>
						<input type="text" style="visibility:hidden" name="enteredBy" id="enteredBy" value="<?php echo $entered_by;?>"/>
					</fieldset>
					</div> <!--close fragement-5-->
				</div><!--close div row-->
			</div><!-- close fluid container-->
		</div> <!--close tab div-->
	
			
		<!--submit button-->
		<input class="button" id="submit" type="button" value="Submit">
		<button class="button" type="reset" value="Reset">Clear Form</button>
				
		</form>
		<script>
		$( "#tabs" ).tabs();
		</script>
	</body>
</html>
