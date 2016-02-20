<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('../database_connection.php');
include ('../index.php');
include('../config/check_form_insert_js.php');
include('../config/check_sample_name.php');
include('../admin_tools/dynamically_add_user_fields/build_a_user_thing.php');
include('form_insert_jquery_js.php');
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Form Insert</title>
		<!--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>-->
	
	</head>
 
	<body>
		<div class="page-header">
		<h3>Sample Insert Form</h3>
		<?php $submitted = 'false';?>
		</div>
	
		<form class = "registration" id="form" name="form">
		<div id="tabs">
				  <ul>
				     <li><a href="#fragment-1"><span>Collection Info</span></a></li>
					 <li><a href="#fragment-2"><span>DNA/RNA Extraction</span></a></li>
					 <li><a href="#fragment-3"><span>Analysis</span></a></li>
					 <li><a href="#fragment-4"><span>User Created Fields</span></a></li>
					 <li><a href="#fragment-5"><span>Notes</span></a></li>
				  </ul>
			
			<br>* = required field <br>
			+ = required for air samples (incudes fungal/bacterial isolates when applicable)<br>
			
			<i>(Don't see your desired selection in dropdown list? Please add selection in "Update Dropdowns in Insert Sample" tab)</i>

			<!--table insert form-->
			<?php include ("../functions/dropDown.php"); ?>

			<div id='samplename_availability_result'></div>
			<div class="container-fluid">
  				<div class="row">  
					
			<!--sample-->
			
  					<div id="fragment-1">
  						<fieldset>
						<LEGEND><b>Sample Collection Info</b></LEGEND>
  						<div class="col-xs-6">
							<p>
							<label class="textbox-label">Sample Number:*</label>
						
							<input type="text" name="sample_number" id = "sample_number" placeholder="[001]" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_sample_number;} ?>">
							</p>
							
							<!--Barcode insert field-->
							<p>
							<label class="textbox-label">Barcode:(optional)</label>
							<br>
							<input type="text" name="barcode" id="barcode" placeholder="Enter A Barcode" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_barcode;}?>"
							</p>
			
							<p>
							<!--Project Name Dropdown-->
							<label class="textbox-label">Select Project Name:*</label>
							<?php
							//url or $_GET name, table name, field name
							dropDown('projName', 'project_name', 'project_name','project_name',$submitted,$root);
							?>
							</p>
							
							<!--location dropdown-->
							<p>
							<label class="textbox-label">Select Location:*</label>
							<?php
			
							//url or $_GET name, table name, field name
							dropDown('loc', 'location', 'loc_name','loc_name',$submitted,$root);
							?>
							</p>
							
							<!--rel location dropdown-->
							<p>
							<label class="textbox-label">Select Relative Location:*</label>
							<?php
							//url or $_GET name, table name, field name
							dropDown('rloc', 'relt_location', 'loc_name','loc_name',$submitted,$root);
							?>
							</p>
							
							<p>
							<!--media type dropdown-->
							<label class="textbox-label">Media Type:*</label>
							<?php
							//url or $_GET name, table name, field name
							dropDown('media', 'media_type', 'media_type','media_type',$submitted,$root);
							?>
							</p>
			
							<p>
							<!--Collector Name input-->
							<label class="textbox-label">Enter Collector Name(s):*</label>
							<p class="clone"> <input type="text" name="collector[]" id="collector" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_collName;} ?>"/></p>
							</p>
						</div><!--col-xs-6-->
				
				  		<div class="col-xs-6">
							<!--Sampling Type insert field-->
							<p>
							<label class="textbox-label">Sample Type:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Please See FAQ"></i></label>
							<?php
							//url or $_GET name, table name, field name, value
							dropDown('sType', 'sample_type', 'sample_type_name','sample_type_id',$submitted,$root);
							?>
							</p>
							
							<!--Flow Rate-->
							<p>
							<label class="textbox-label">Flow Rate-Start/End of Day:+<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Coriolis or SASS: 300 l/m. Spin Air: 20-100 l/m"></i></label><br>
							<input type="text" class = "shrtfields" name="fRate" id="fRate"  placeholder="Rate(L/min)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_fRate;} ?>">
							<input type="text" class = "shrtfields" name="fRate_eod" id="fRate_eod"  placeholder="Rate(L/min)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_fRate_eod;} ?>">
							</p>
			
							<!--Sample Storage-->
							<label class="textbox-label">Storage Location:* (pick freezer and drawer owner)</label><br>
							<p>
							<?php
							//url or $_GET name, table name, field name
							dropDown('oStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root);
							?>
							
							<select id="oStore_name" name ="oStore_name" >
			 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
			 			
			 						echo '<option value='.$_GET["oStore_name"].'  echo "selected";}} ?>'.$_GET["oStore_name"].' </option>';
			 					}else{
			 						echo '<option value="0">-Select-</option>';
			 					}?>
			 				</select>	
							
							<p>
							<label class="textbox-label">Height Above Floor:+<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Coriolis-113.5cm: SASS-156cm: Spin Air-151cm (all on tripods)" id='example'></i>
							</label><br>
							<input type="text" name="sampling_height" id="sampling_height"  placeholder="Height Above Floor (cm)" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_sampling_height;} ?>">
							</p>
							
							<p>
							<!--sampler dropdown-->
							<label class="textbox-label">Select Number of Samplers:*<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="This is the number of samplers used to create the original sample. Also, please note that all blanks and cfu samples will have a sampling time duration of zero"></i></label>
							<?php
							//url or $_GET name, table name, field name
							//dropDown('airSamp', 'air_sampler', 'air_sampler_name','air_sampler_name',$submitted);
							?>
							<select id='sampler_num' name='sampler_num'>
							<option value='0'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "0" )){ echo "selected";}}?>>-Select-</option>
							<option value='1'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "1" )){ echo "selected";}}?>>-1:One-</option>
							<option value='2'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "2" )){ echo "selected";}}?>>-2:Two-</option>
							<option value='3'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "3" )){ echo "selected";}}?>>-3:Three-</option>
							<option value='4'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "4" )){ echo "selected";}}?>>-4:Four-</option>
							<option value='5'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "5" )){ echo "selected";}}?>>-5:Five-</option>
							<option value='6'<?php if ((isset($_GET['submit']) && $submitted != 'true')){
								if((isset($_GET['sampler_num']) && $_GET['sampler_num'] == "6" )){ echo "selected";}}?>>-6:Six-</option>
							</select>
				
							<div id="div_sampler_num"></div>
							</p>
						</div><!--close col xs 6-->
							
					</fieldset>
					</div><!--close fragment-1-->
			
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->
				<!------------------------------------------------------------------------------------------->

					<div id="fragment-2">
					<fieldset>
					<div class="col-xs-6">
					<LEGEND><b>DNA Extraction Info</b></LEGEND>
							<div id="dna_extraction">	
								<p>
								<label class="textbox-label">DNA Extraction Date:</label><br>
								<input type="text" id="d_extr_date"  name="d_extr_date" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['d_extr_date']);} ?>"/>
								<script>
								$('#d_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
								</script>
				
								<p>
								<!--DNA Extraction Kit dropdown-->
								<label class="textbox-label">Select DNA Extraction Kit:</label>
								<br/>
								<?php
								//url or $_GET name, table name, field name
								dropDown('dExtKit', 'dna_extraction', 'd_kit_name','d_kit_name',$submitted,$root);
								?>
								</p>
								
								<!--DNA Concentration-->
								<p>
								<label class="textbox-label">DNA Concentration (ng/ul):</label><br>
								<input type="text" name="dConc" id="dConc" placeholder="Enter A DNA Concentration" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dConc;} ?>">
								</p>
				
								<!--Volume of DNA-->
								<p>
								<label class="textbox-label">Volume of DNA Elution (ul):</label><br>
								<input type="text" name="dVol"  id="dVol" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dVol;} ?>">
								</p>
								
								<!--Instrument used to measure DNA concentration-->
								<p>
								<label class="textbox-label">Instrument/Kit Used to Measure DNA Concentration:</label><br>
								<?php
								//url or $_GET name, table name, field name
								dropDown('dInstru', 'quant_instruments', 'kit_name','kit_name',$submitted,$root);
								?>
								</p>
								
								<!--Volume of DNA to measure DNA conc-->
								<p>
								<label class="textbox-label">Volume of DNA Used for Measure DNA Concentration(ul):</label><br>
								<input type="text" name="dVol_quant" id="dVol_quant"  placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_dVol_quant;} ?>">
								</p>
								
								<!--DNA -->
								<p>
								<label class="textbox-label">Location of DNA Extract:(pick freezer and drawer owner)</label><br>
								<p>
								<?php
								//url or $_GET name, table name, field name
								dropDown('dStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root);
								?>
								</p>
								<select id="dStore_name" name ="dStore_name">
				 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
				 						echo '<option value='.$_GET["dStore_name"].'  echo "selected";}} ?>'.$_GET["dStore_name"].' </option>';
				 					}else{
				 						echo '<option value="0">-Select-</option>';
				 					}?>
				 				</select>	
				 				
				 				<p>
								<!--DNA Extractor Name input-->
								<label class="textbox-label">Enter Name(s) of Persons Who Extracted DNA:</label>
								<p class="clone2"> <input type="text" name="dExtrName[]" id = "dExtrName" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_dExtrName;} ?>"/></p>
				 				<p>
								<h3 class="checkbox-header">Does Original Sample Still Exist?:</h3>
				 				<div class="vert-checkboxes">
				 				<label class="checkbox-label"><input type="checkbox" name="orig_sample_exist" class = "orig_sample_exist" id="orig_sample_exist" value="false" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_orig_sample_exist == 'false'){echo 'checked';}} ?>/>No
								</div>
								</p>
								
								<p>
								<h3 class="checkbox-header">Does DNA Extraction Sample Still Exist?</h3>
				 				<div class="vert-checkboxes">
				 				<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="one" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'one'){echo 'checked';}} ?>/>Yes,DNA Sample Exisits</label><br />
								<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="two" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'two'){echo 'checked';}} ?>/>No, DNA Has Not Been Extracted</label><br />
								<label class="radio-label"><input type="radio" name="DNA_sample_exist" value="three" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_DNA_sample_exist == 'three'){echo 'checked';}} ?>/>No, DNA Sample Is Used Up</label><br />
								</div>
								</p>
								
								
							</div><!--close dna extraction-->
						</div><!--close col-xs-6-->
						<!--</fieldset>
						<fieldset>-->
						<div class="col-xs-6">
						<LEGEND><b>RNA Extraction Info</b></LEGEND>	
						
							<div id="rna_extraction">
								
								<p>
								<label class="textbox-label">RNA Extraction Date:</label><br>
								<input type="text" id="r_extr_date"  name="r_extr_date" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo htmlspecialchars($_GET['r_extr_date']);} ?>"/>
								<script>
								$('#r_extr_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
								</script>
								
								<p>
								<!--RNA Extraction dropdown-->
								<label class="textbox-label">Select RNA Extraction Kit:</label>
								<br/>
								<?php
								//url or $_GET name, table name, field name
								dropDown('rExtKit', 'rna_extraction', 'r_kit_name','r_kit_name',$submitted,$root);
								?>
								</p>
								
								<!--RNA Concentration-->		
								<p>
								<label class="textbox-label">RNA Concentration (ng/ul):</label><br>
								<input type="text" name="rConc" id="rConc" placeholder="Enter an RNA Concentration" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rConc;} ?>">
								</p>
								
								<!--RNA Volume-->
								<p>
								<label class="textbox-label">Volume of RNA Elution (ul):</label><br>
								<input type="text" name="rVol" id="rVol" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rVol;} ?>">
								</p>
						
								<!--Instrument used to measure RNA concentration-->
								<p>
								<label class="textbox-label">Instrument/Kit Used to Measure RNA Concentration:</label><br>
								<?php
								//url or $_GET name, table name, field name
								dropDown('rInstru', 'quant_instruments', 'kit_name','kit_name',$submitted,$root);
								?>
								</p>
								
								<!--RNA Volume-->
								<p>
								<label class="textbox-label">Volume of RNA for Quantification(ul):</label><br>
								<input type="text" name="rVol_quant" id="rVol_quant" placeholder="Enter A Volume" value="<?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){echo $p_rVol_quant;} ?>">
								</p>
								
								
								<p>
								<label class="textbox-label">Location of RNA Extract:(pick freezer and drawer owner)</label><br>
								<p>
								<?php
								//url or $_GET name, table name, field name
								dropDown('rStore_temp', 'freezer', 'freezer_id','freezer_id',$submitted,$root);
								?>
								</p>
						
								<select id="rStore_name" name ="rStore_name">
				 					<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){
				 						echo '<option value='.$_GET["rStore_name"].'  echo "selected";}} ?>'.$_GET["rStore_name"].' </option>';
				 					}else{
				 						echo '<option value="0">-Select-</option>';
				 					}?>
				 				</select>
				 				
				 				<p>
								<!--RNA Extractor Name input-->
								<label class="textbox-label">Enter Name(s) of Persons Who Extracted RNA:</label>
								<p class="clone3"> <input type="text" name="rExtrName[]" id="rExtrName" class='input' placeholder="Comma Seperated Names" value="<?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))) {echo $p_rExtrName;} ?>"/></p>
							
								
								<p>
								<h3 class="checkbox-header">Does Original RNA Sample Still Exist?:</h3><br>
								<div class="vert-checkboxes">
				 				<label class="checkbox-label"><input type="checkbox" class="orig_sample_exist" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_orig_sample_exist == 'false'){echo 'checked';}} ?>/>No<br />
								</div>
								</p>
								
								<p>
								<h3 class="checkbox-header">Does RNA Extraction Sample Exist?:</h3><br>
				 				<div class="vert-checkboxes">
				 				<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="one" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'one'){echo 'checked';}} ?>/>Yes,RNA Sample Exisits<br />
								<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="two" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'two'){echo 'checked';}} ?>/>No, RNA Has Not Been Extracted<br />
								<label class="radio-label"><input type="radio" name="RNA_sample_exist" value="three" <?php if((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy']))){if($p_RNA_sample_exist == 'three'){echo 'checked';}} ?>/>No, RNA Sample Is Used Up<br />
								</div>
								</p>
							</div><!--close rna extraction-->
						</div><!--close col-xs-6-->
						
						
						
						
							
					
					</fieldset>
					</div><!--close fragment-2-->

					<div id="fragment-3">
						<LEGEND><b>Downstream Analysis Info</b></LEGEND>
							<p>
							<!--Analysis Pipeline Name Dropdown-->
							<label class="textbox-label">Select Analysis Pipeline:</label>
							<br/>
							<?php
							//url or $_GET name, table name, field name
							dropDown('anPipe', 'analysis', 'analysis_name','analysis_name',$submitted,$root);
							?>
							</p>
					</div><!--fragment-3-->

					<div id="fragment-4">
					<fieldset>
					<LEGEND><b>User Created Fields</b></LEGEND>
						<div class="col-xs-6">
							<i>Coming Soon</i>
							<div id="user_things"></div>
						</div>
					</fieldset>
					</div><!--end fragment-5-->
					
					<div id="fragment-5">
					<fieldset>
					<LEGEND><b>Notes</b></LEGEND>
						<div class="col-md-12">
							<p>
							<label class="textbox-label">Sample Notes:(optional)</label>
							<textarea class="form-control" from="sample_form" rows="3" name="notes" id="notes" placeholder = "Enter Date and Initials for Comment (e.g.: YYYY/MM/DD Comment -initials)"><?php if ((isset($_GET['submit']) && $submitted != 'true') || (isset($_GET['copy'])))   {echo $p_notes;} ?></textarea>
							</p>
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