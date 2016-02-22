<script type="text/javascript">
				//if from looks ok, try ands submit
				$(document).ready(function() {
					$("#submit").click(function() {
						
						//check form
						var do_check = validate(form);
						if(do_check == true){
							alert('Form ok');

							//define variables..
							var sample_number = $("#sample_number").val();
							var projName = $("#projName").val();
							var loc = $("#loc").val();
							var rloc = $("#rloc").val();
							var dExtKit = $("#dExtKit").val();
							var rExtKit = $("#rExtKit").val();
							var anPipe = $("#anPipe").val();
							var barcode = $("#barcode").val();
							var sType = $("#sType").val();
							var notes = $("#notes").val();
							var fRate = $("#fRate").val();
							var fRate_eod = $("#fRate_eod").val();
							var media = $("#media").val();
							var sampling_height = $("#sampling_height").val();
							var collector_names = $("#collector").val();
							var collector = [collector_names];
							var orig_sample_exist = $("#orig_sample_exist").val();
							var oStore_temp = $("#oStore_temp").val();
							var oStore_name = $("#oStore_name").val();
							var enteredBy = $("#enteredBy").val();
							
							//for dna
							var dConc = $("#dConc").val();
							var dInstru = $("#dInstru").val();
			   				var dVol = $("#dVol").val();
			   				var dVol_quant = $("#dVol_quant").val();
							var d_extr_date = $("#d_extr_date").val();
							var dExtrName_names= $("#dExtrName").val();
							var dExtrName = [dExtrName_names];
							var DNA_sample_exist = $("#DNA_sample_exist").val();
							var dStore_temp = $("#dStore_temp").val();
							var dStore_name = $("#dStore_name").val();
			
							//for rna
							var rConc = $("#rConc").val();
							var rInstru = $("#rInstru").val();				
							var rVol = $("#rVol").val();	
							var rVol_quant = $("#rVol_quant").val();
							var r_extr_date = $("#r_extr_date").val();
							var rExtrName_names = $("#rExtrName").val();
							var rExtrName = [rExtrName_names];
							var RNA_sample_exist = $("#RNA_sample_exist").val();
							var rStore_temp = $("#rStore_temp").val();
							var rStore_name = $("#rStore_name").val();

							//air samplers
							var sampler_num = $("#sampler_num").val();
							var air_samplers = [];
							var start_dates = [];
							var end_dates = [];
							var start_times = [];
							var end_times = [];
							
							//user things
							var user_things_s = [];
							var counter = 0;
							$(".things").each(function(){
								user_things_s[counter] = $(this).val();
								counter++;
							});
							
					
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
			                    url     : 'form_insert_submit.php', //the url you are sending datas to which will again send the result
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { sample_number:sample_number,
								projName: projName,
								loc:loc,
								rloc:rloc,
								dExtKit:dExtKit, 
								rExtKit:rExtKit,
								anPipe: anPipe,
								barcode: barcode,
								sType: sType, 
								notes: notes, 
								fRate: fRate,
								fRate_eod: fRate_eod,
								media: media,
								sampling_height: sampling_height, 
								collector: collector,
								orig_sample_exist: orig_sample_exist,
								oStore_temp: oStore_temp,
								oStore_name: oStore_name,
								enteredBy:enteredBy,
								
								//for dna
								dConc: dConc,
								dInstru: dInstru,
				   				dVol: dVol ,
				   				dVol_quant: dVol_quant,
								d_extr_date: d_extr_date,
								dExtrName: dExtrName,
								DNA_sample_exist: DNA_sample_exist,
								dStore_temp: dStore_temp,
								dStore_name: dStore_name,
				
								//for rna
								rConc: rConc,
								rInstru: rInstru, 				
								rVol: rVol,	
								rVol_quant: rVol_quant,
								r_extr_date: r_extr_date,
								rExtrName: rExtrName,
								RNA_sample_exist: RNA_sample_exist,
								rStore_temp: rStore_temp,
								rStore_name: rStore_name,
								
								//air samplers and date/time arrays
								sampler_num:sampler_num, 
								air_samplers: air_samplers,
								start_dates : start_dates,
								end_dates : end_dates,
								start_times: start_times,
								end_times: end_times,
								
								//user things
								user_things_s: user_things_s

			                    }, //Data you are sending
			                    //success : function(data){alert("\t"+'Success!'+"\n"+'Inserted Sample Number : '+sample_number+"\n"+'For Project: '+projName)}, 
			                    success : function(data){alert(data)}, 
			                    error   : function(){alert('An Error Has Occurred')} //error message
			                })
							}
						});
				});
				
				
				//actual form checking
				var name_check = 'true';
			    function validate(form) {
			       var valid = 'true';
				   // if(check_form() == 'false'){
				   if(check_form() == 'false'){
				    	valid = 'false';
				   }
				   if(check_form_required() == 'false'){
				    	valid = 'false';
				   }	
				   check_sample_name_insert();
				   if(name_check == 'false'){
				   		alert('Sample Name Not Valid. Please Check Project Name And Sample Number');
				    	valid = 'false';
				    }
				   if(valid == 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				   }
				   else{
				   		return confirm('Sure You Want To Submit?');
				   }
				}
				
				function check_form(){
					var index;
					var valid = 'true';
					var x = document.getElementById('sampler_num').value;
					if(x == 0){
						valid = 'false';
						document.getElementById('sampler_num').style.backgroundColor = 'blue';
					}
					else{
						//create a contains method to check if airSamp is entered twice
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						var seen = [];
						//validate airSamp data
						for (index = 1; index <= x; ++index) {
	   	 					var airSamp_name = 'airSamp'+index;
	   	 					//check that airSamp is picked 
	   	 					var airSamp_name_value = document.getElementById(airSamp_name).value;
	   	 					if(airSamp_name_value == '0' || airSamp_name_value == 'Needs to be added'){
	   	 						alert("Whoops! Sampler Name Is Not Valid");
	   	 						document.getElementById(airSamp_name).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						//check to see if airSamp name is already input
	   	 						if(seen.contains(airSamp_name_value)){
	   	 							document.getElementById(airSamp_name).style.backgroundColor = 'blue';
	   	 							alert("You Have Chosen More Than One Air Sampler With The Same Name. Please Check Names");
	   	 							valid = 'false';
	   	 						}
	   	 					    else{
	   	 							seen.push(airSamp_name_value);
	   	 							document.getElementById(airSamp_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}
	   	 				
	   	 					//check start and end date/times are entered and make sense
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						alert("Whoops! Please Enter A Start Time");
	   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;
	   	 					if(end_time_value == ''){
	   	 						alert("Whoops! Please Enter An End Time");
	   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					
	   	 					var start_date = 'sdate'+index;
	   	 					var start_date_value = document.getElementById(start_date).value;
	   	 					if(start_date_value == ''){
	   	 						alert("Whoops! Please Enter An Starting Date");
	   	 						document.getElementById(start_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					var end_date = 'edate'+index;
	   	 					
	   	 					var end_date_value = document.getElementById(end_date).value;
	   	 					if(end_date_value.length == '0'){
	   	 						alert("Whoops! Please Enter An End Date");
	   	 						document.getElementById(end_date).style.backgroundColor = 'blue';
	   	 						valid = 'false';
	   	 					}
	   	 				
							
							if(start_time_value != '' && start_date_value != '' && end_time_value != '' && end_date_value != ''){
								
								//first check if date time values make sense
								var p_start = start_date_value+' '+start_time_value;
								var p_end = end_date_value+' '+end_time_value;
								if((p_start) && (p_end)){
									
									var ts1 = Date.parse(p_start);
									var ts2 = Date.parse(p_end);
									var seconds_diff = ts2 - ts1;
									var time = (seconds_diff/3600);
									time = (time/1000); 
									var p_time = time.toFixed(2);
									var airSamp_check = airSamp_name.match(/^Coriolis.*/);
								
									if(p_time < 0){
										valid = 'false';
										alert("Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else if(p_time > 6.5 && airSamp_check  != null){//check if coriolis sampling is greater than 6 hours
										valid = 'false';
										alert("Sampling Is Greater Than 6 Hours For Coriolis Sampling. Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = 'blue';
										document.getElementById(end_time).style.backgroundColor = 'blue';
										document.getElementById(start_date).style.backgroundColor = 'blue';
										document.getElementById(end_date).style.backgroundColor = 'blue';
									}
									else{
										document.getElementById(start_time).style.backgroundColor = 'white';
										document.getElementById(end_time).style.backgroundColor = 'white';
										document.getElementById(start_date).style.backgroundColor = 'white';
										document.getElementById(end_date).style.backgroundColor = 'white';
									}
								}
							}
						}
					}	
					return valid;
					
				}
</script>