<?php 
// Javascript:

?>

<!--jQuery-->
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>

<!--jQuery UI -->
<script src="//code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<!--Latest complied and minified JavaScript-->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>-->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!--More JavaScript-->
<!--relCopy-->
<!--<script type="text/javascript" src="/series/dynamic/airmicrobiomes/relCopy.js"></script>-->

	

<!--Sample Storage Dropdown-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
                $('#oStore_temp').change(function(){ //on change event
                var Store_temp = $('#oStore_temp').val(); //<----- get the value from the parent select 
                if(Store_temp == 'Used'){
	                $("#oStore_name").html("<option value='Used'>Used</option>"); 
	                $('.orig_sample_exist').prop('checked',true);
                }
                else{
	                var Store_name = "oStore_name";
	                $.ajax({
	                    url     : root+'freezer_drawer_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { Store_temp: Store_temp,Store_name: Store_name}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#oStore_name').html(data)}, // On success, it will populate the 2nd select
	                    error   : function(){alert('an error has occured')} //error message
	                })
	                
	                $('.orig_sample_exist').prop('checked',false);
				}	
			});
			
		});
		
		
</script>

<!--Change dropdown for storage if the sample no longer exists-->
<script type="text/javascript">
	$(document).ready(function() {
	var temp = $("#oStore_temp").val(); 
	var name = $("#oStore_name").val();
	
    $('.orig_sample_exist').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.orig_sample_exist').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1" 
                $("#oStore_temp").val("Used");
        		$("#oStore_name").html("<option value='Used'>Used</option>");               
            });
        }else{
            $('.orig_sample_exist').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1" 
                $("#oStore_temp").val(temp);
                //$("#oStore_name").val(name);
                if(name == '0'){
                	$("#oStore_name").html('<option value='+name+'>-Select-</option>');
                }
                else{
        			$("#oStore_name").html('<option value='+name+'>'+name+'</option>');                      
            	}	
            });         
        }
    });
    
});
</script>

<!--DNA extraction storage-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
                $('#dStore_temp').change(function(){ //on change event
                var Store_temp = $('#dStore_temp').val(); //<----- get the value from the parent select 
                var Store_name = "dStore_name";
                $.ajax({
                    url     : root+'freezer_drawer_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { Store_temp: Store_temp,Store_name: Store_name}, //Data you are sending
                    //success : function(data){$('#div_store').html(data)},
                    success : function(data){$('#dStore_name').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('an error has occured')} //error message
                })
			
			
			});
			
		});
</script>

<!--RNA extraction storage-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
                $('#rStore_temp').change(function(){ //on change event
                var Store_temp = $('#rStore_temp').val(); //<----- get the value from the parent select 
                var Store_name = "rStore_name";
                $.ajax({
                    url     : root+'freezer_drawer_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { Store_temp: Store_temp,Store_name: Store_name}, //Data you are sending
                    //success : function(data){$('#div_store').html(data)},
                    success : function(data){$('#rStore_name').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('an error has occured')} //error message
                })
			
			
			});
			
		});
</script>

<!--ptTimeSelect-->
<?php echo '<script type="text/javascript" src="'.$root.'config/jquery.ptTimeSelect.js"></script>'; ?>

<!--custom alert box-->
<script>
	function CustomAlert(){
		this.render = function(dialog){
			var winW = window.innerWidth;
			var winH = window.innerHeight;
			var dialogoverlay = document.getElementById('dialogoverlay');
			var dialogbox = document.getElementById('dialogbox');
			dialogoverlay.style.display = "block"; /*not none anymore*/
			dialogoverlay.style.height = winH+"px";
			dialogbox.style.left = (winW/2) - (555 * .5) + "px";
			dialogbox.style.top = "100px";
			dialogbox.style.display = "block";
			document.getElementById('dialogboxhead').innerHTML = "Acknowledge This Message";
			document.getElementById('dialogboxbody').innerHTML = dialog;
			document.getElementById('dialogboxfoot').innerHTML = '<button onclick="Alert.ok()">OK</button';	
		}
		this.ok = function(){
			//remove the dialog box and the overlay once you hit ok
			document.getElementById('dialogbox').style.display = "none";
			document.getElementById('dialogoverlay').style.display = "none";
		}
		
		this.render2 = function(dialog){
			var winW = window.innerWidth;
			var winH = window.innerHeight;
			var dialogoverlay = document.getElementById('dialogoverlay');
			var dialogbox = document.getElementById('dialogbox');
			dialogoverlay.style.display = "block"; /*not none anymore*/
			dialogoverlay.style.height = winH+"px";
			dialogbox.style.left = (winW/2) - (555 * .5) + "px";
			dialogbox.style.top = "100px";
			dialogbox.style.display = "block";
			document.getElementById('dialogboxhead').innerHTML = "Acknowledge This Message";
			document.getElementById('dialogboxbody').innerHTML = dialog;
			document.getElementById('dialogboxfoot').innerHTML = '<button onclick="Alert.ok2()">OK</button';	
		}
		this.ok2 = function(){
			goBack();
		}
		function goBack() {
    		window.history.back();
		}
				
	}
	var Alert = new CustomAlert();
</script>
<div id="dialogoverlay"></div>
<div id="dialogbox">
	<div>
		<div id="dialogboxhead"></div>
		<div id="dialogboxbody"></div>
		<div id="dialogboxfoot"></div>
	</div>
</div>



<!--If Sample Type is not an Air Sample, set flow rate to zero-->
<!--Also, do a check to see if media type is not MilliQ-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
        	$('#sType').change(function(){ //on change event
                var sType = $('#sType').val(); //<----- get the value from the parent select 
                var media = $('#media').val();
                //alert(sType);
				var flow_rate = $('#fRate');
				var flow_rate_eod = $('#fRate_eod');
				
				var iso_coll_temp = $('#iso_coll_temp').val();
				var iso_store_date= $('#datepicker3').val();
				var iso_store_method = $('#iso_store_method').val();
				
				//alert(iso_store_method);

        		if (sType == "A" ) {//assume coriolis and commonly used 300
        			$('#fRate').val('300');
        			$('#fRate_eod').val('300');
        			
					if(media == "MilliQ"){
        				Alert.render("Warning: Media is MilliQ. Please check if this is what you want");
        			}
        		}
        		else if(sType == "F" || sType == "BC" || sType == "UI"){ //assume air sampler rate is 28.3
        			$('#fRate').val('28.3');
        			$('#fRate_eod').val('28.3');
        		}
        		else {
        			$('#fRate').val('0');
        			$('#fRate_eod').val('0');
        		}
        		
        		
        		//check if you are a Fungal or Bacterial Isolate
        		//if you are, check to make sure you have the required fields
        		/*if (sType == "F" || sType == "BA") {
        			if(iso_store_method == '0'){$('#iso_store_method').css({"backgroundColor": "blue"});}
        			else{$('#iso_store_method').css({"backgroundColor": "white"});}
        			
        			if(iso_coll_temp == '0'){$('#iso_coll_temp').css({"backgroundColor": "blue"});}
        			else{$('#iso_coll_temp').css({"backgroundColor": "white"});}
        			
        			if(iso_store_date == ''){$('#datepicker3').css({"backgroundColor": "blue"});}
        			else{$('#datepicker3').css({"backgroundColor": "white"});}
        			
        		
        		}
        		else{
        			$('#iso_coll_temp').css({"backgroundColor": "white"});
        			$('#datepicker3').css({"backgroundColor": "white"});
        			$('#iso_store_method').css({"backgroundColor": "white"});
        		}
    			*/
			});
			
		});
		
		
</script>

<!--Pick Sensor Info-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
             $('#sens_num').change(function(){ //on button click event
                var num_sensors = $('#sens_num').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : '/series/dynamic/airmicrobiomes/sensor_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_sensors: num_sensors}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div1').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('an error has occured')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--Pick Air Samplers Info-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
             $('#air_samp_num').change(function(){ //on button click event
                var num_air_samplers = $('#air_samp_num').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : root+'air_sampler_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_air_samplers: num_air_samplers}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div_air_samp_num').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('an error has occured')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--Pick Pick Samples-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
             $('#projName').change(function(){ //on button click event
             	var start_date = $('#smydate').val();
             	var end_date = $('#emydate').val();
                var projName = $('#projName').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : root+'sample_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { projName: projName,
	                    			start_date:start_date,
	                    			end_date:end_date}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#sampleSelect').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('an error has occured')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--Pick Sample Pooling Info-->
<script type="text/javascript">
		$(document).ready(function(){  
			             
             $('#pool_num').change(function(){ //on button click event
                var num_pooled_samples = $('#pool_num').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : root+'pool_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_pooled_samples: num_pooled_samples}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div_pool').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('an error has occured')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--show options for amplicon sequencing-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
             $('#sample_type').change(function(){ //on button click event
                var sample_type = $('#sample_type').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : root+'amplicon_div_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { sample_type: sample_type}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#amplicon_info').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('an error has occured')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--select all checkboxes-->
<script type="text/javascript">
$(document).ready(function() {
    $('#selecctall').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });
   
});
</script>

<!--select all 'yes' checkboxes on sequencing submission form-->
<script type="text/javascript">
$(document).ready(function() {
    $('#selecctallyes').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checkbox2').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox2').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });
   
});
</script>

<!--select all 'no' checkboxes on sequencing submission form-->
<script type="text/javascript">
$(document).ready(function() {
    $('#selecctallno').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checkbox3').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox3').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });
   
});
</script>

<!--if sample is part of a pool, disable the sample input page for editing-->
<script type="text/javascript">
		$(document).ready(function(){  
			if(document.getElementById('part_of_pool')){
			 	var part_of_sample = document.getElementById('part_of_pool').value;
			    if(part_of_sample == 'Y'){
					Alert.render2("Notice: Sample Is Now Part Of A Pool. Original Sample Cannot Be Edited. See Query Info Tab To Explore Sample Info");
				}
			}	
		 });  
</script>

<!--make all air sampler times the same-->
<!--<script type="text/javascript">
$(document).ready(function() {
    $('#same_time').change(function() {
    	
   if($(this).is(":checked")) {
      //'checked' event code
      console.log('what');
      return;
   }
   //'unchecked' event code
});
   
});
</script>-->


<!--custom confirm box-->
<!--note: not actually using this right now...leaving in case try to get it to work later-->
<!--<script type="text/javascript">
var path = window.location.pathname;
var page = path.split("/").pop();
console.log( page );
function deletePost(id){
	var db_id = id.replace("post_", "");
	// Run Ajax request here to delete post from database
	document.body.removeChild(document.getElementById(id));
}
function CustomConfirm(){
	this.render = function(dialog,op,id){
		var winW = window.innerWidth;
	    var winH = window.innerHeight;
		var dialogoverlay = document.getElementById('dialogoverlay');
	    var dialogbox = document.getElementById('dialogbox');
		dialogoverlay.style.display = "block";
	    dialogoverlay.style.height = winH+"px";
		dialogbox.style.left = (winW/2) - (550 * .5)+"px";
	    dialogbox.style.top = "100px";
	    dialogbox.style.display = "block";
		
		document.getElementById('dialogboxhead').innerHTML = "Confirm that action";
	    document.getElementById('dialogboxbody').innerHTML = dialog;
		document.getElementById('dialogboxfoot').innerHTML = '<button onclick="Confirm.yes(\''+op+'\',\''+id+'\')">Yes</button> <button onclick="Confirm.no()">No</button>';
	}
	this.no = function(){
		document.getElementById('dialogbox').style.display = "none";
		document.getElementById('dialogoverlay').style.display = "none";
		return false;
	}
	this.yes = function(op,id){
		if(op == "delete_post"){
			deletePost(id);
		}
		if(op == "update"){
			return true;
		}
		document.getElementById('dialogbox').style.display = "none";
		document.getElementById('dialogoverlay').style.display = "none";
	}
}
var Confirm = new CustomConfirm();
</script>
<script>
function someFunc() {
    if (1==2) {
        return true;
    } else {
        alert("Not submitting");
        return false;
    }
}
function validateMyForm(){
	onload=Confirm.render('Are You Sure You Want To Update?','update','post_2');
 if() == false)
  { 
    alert("validation failed false");
    returnToPreviousPage();
    return false;
  }

  alert("validations passed");
  return true;
  */
}
</script>
</script>-->

