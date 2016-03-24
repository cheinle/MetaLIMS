<?php 
// Javascript:
?>

<!--jQuery-->
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>

<!--jQuery UI -->
<script src="//code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<!--Latest complied and minified JavaScript-->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<!--ptTimeSelect-->
<?php echo '<script type="text/javascript" src="'.$_SESSION['link_root'].'config/jquery.ptTimeSelect.js"></script>'; ?>

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
	                    error   : function(){alert('An Error Has Occurred')} //error message
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
                    error   : function(){alert('An Error Has Occurred')} //error message
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
                    error   : function(){alert('An Error Has Occurred')} //error message
                })
			
			
			});
			
		});
</script>

<!--general storage-->
<script type="text/javascript">
		$(document).ready(function(){  
				               
                $('#Store_temp').change(function(){ //on change event
                var Store_temp = $('#Store_temp').val(); //<----- get the value from the parent select 
                var Store_name = "Store_name";
                $.ajax({
                    url     : root+'freezer_drawer_select.php', //the url you are sending datas to which will again send the result
                    type    : 'GET', //type of request, GET or POST
                    data    : { Store_temp: Store_temp,Store_name: Store_name}, //Data you are sending
                    //success : function(data){$('#div_store').html(data)},
                    success : function(data){$('#Store_name').html(data)}, // On success, it will populate the 2nd select
                    error   : function(){alert('An Error Has Occurred')} //error message
                })
			
			
			});
			
		});
</script>



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

        		/*if (sType == "A" ) {//assume coriolis and commonly used 300
        			$('#fRate').val('300');
        			$('#fRate_eod').val('300');
        			
					if(media == "MilliQ"){
        				Alert.render("Warning: Media is MilliQ. Please check if this is what you want");
        			}
        		}
        		else {
        			$('#fRate').val('0');
        			$('#fRate_eod').val('0');
        		}*/
      
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
	                    url     : root+'daily_data/sensor_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_sensors: num_sensors}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div1').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('An Error Has Occurred')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--Pick Air Samplers Info-->
<!--Pick Air Samplers Info-->
<script type="text/javascript">
		//var old_sampler_num = $("#sampler_num").val();
		$(document).ready(function(){  
			
			
			var previous;
		    $("select[id=sampler_num]").on("focus",function () {
		        previous = this.value;
		
		    }).change(function() {
			   		var value =  this.value;
	
			      	//alert('Old Value : '+previous+' New Value : '+value);
	
	             	var sampler_num = value;
	             	var old_sampler_num = previous;
             	
             		//air samplers
					var air_samplers = [];
					var start_dates = [];
					var end_dates = [];
					var start_times = [];
					var end_times = [];
							
					for ( var x = 1; x <= sampler_num; x++) {
						var airSampler = $("#airSamp"+x).val();
						air_samplers[x] = airSampler;
											
						var sdate = $("#sdate"+x).val();
						start_dates[x] = sdate;
											
						var edate = $("#edate"+x).val();
						end_dates[x] = edate;
											
						var stime = $("#stime"+x).val();
						start_times[x] = stime;
											
						var etime = $("#etime"+x).val();
						end_times[x] = etime;
					}
	                $.ajax({
	                    url     : root+'sample_insert/sampler_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_air_samplers: sampler_num,
	                    			old_num_air_samplers: old_sampler_num, 
	                    			air_samplers:air_samplers,
	                    			start_dates:start_dates,
	                    			end_dates:end_dates,
	                    			start_times:start_times,
	                    			end_times:end_times}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div_sampler_num').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('An Error Has Occurred')} //error message
	                })
	
			});
			
		});	
		
</script>

<!--pick sample names--> 
<script type="text/javascript">
		$(document).ready(function(){  
				               
             $('#projName').change(function(){ //on button click event
             	var start_date = $('#smydate').val();
             	var end_date = $('#emydate').val();
                var projName = $('#projName').val(); //<----- get the value from the parent select 
                //alert(num_sensors);
	                $.ajax({
	                    url     : root+'sequencing/sample_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { projName: projName,
	                    			start_date:start_date,
	                    			end_date:end_date}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#sampleSelect').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('An Error Has Occurred')} //error message
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
	                    url     : root+'pooling/pool_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { num_pooled_samples: num_pooled_samples}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#div_pool').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('An Error Has Occurred')} //error message
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
	                    url     : root+'sequencing/amplicon_div_select.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : { sample_type: sample_type}, //Data you are sending
	                    //success : function(data){$('#div_store').html(data)},
	                    success : function(data){$('#amplicon_info').html(data)}, // On success, it will populate the div
	                    error   : function(){alert('An Error Has Occurred')} //error message
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

