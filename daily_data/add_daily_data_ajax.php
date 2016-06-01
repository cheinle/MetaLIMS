<script type="text/javascript">
				//if from looks ok, try ands submit
				$(document).ready(function() {
					$("#submit").click(function() {
						
						//check form
						var do_check = validate(form);
						if(do_check == true){
							alert('Form ok');

							 var mydate = document.getElementById('mydate').value;
							 var loc = document.getElementById('loc').value;
							 var notes = document.getElementById('notes').value;
							 var sens_num = document.getElementById('sens_num').value;
							 
							 var my_sensors = [];
							 var start_times = [];
							 var end_times = [];
							 var measurement = [];
							 var record = [];
							
							 for ( var x = 1; x <= sens_num; x++) {
									var mySensors = $("#sensor"+x).val();
									my_sensors[x] = mySensors;
									
									var stime = $("#stime"+x).val();
									start_times[x] = stime;
									
									var etime = $("#etime"+x).val();
									end_times[x] = etime;
									
									var measurement_unit = $("#measurement"+x).val();
									measurement[x] = measurement_unit;
									alert(measurement[x]);
									
									var record_unit = $("#record"+x).val();
									record[x] = record_unit;
							}
							
							alert(measurement);
							 $.ajax({
			                    url     : 'add_daily_data_submit.php', //the url you are sending datas to which will again send the result
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { mydate:mydate,
											loc: loc,
											notes:notes,
											sens_num:sens_num,
											my_sensors: my_sensors,
											start_times: start_times,
											end_times: end_times,
											measurement: measurement,
											record:record
			                    }, //Data you are sending
			                    success : function(data){alert(data)}, 
			                    //success : function(data){alert(data)}, 
			                    error   : function(){alert("An Error Has Occurred");} //error message
							});
						}
					});
				});
				
				//check
				//actual form checking
				 		function validate(form) {
			    	
					    	//if you tried to submit, check the entire page
					    	//return valid is false if you find erro
					    	var valid = 'true';
						    if(check_form() == 'false'){
						    	valid = 'false';	
						    }
						    if(valid == 'false'){
						    	alert('ERROR: Some inputs are invalid. Please check fields');
						    	return false;
						    }
						    else{
						    	var date = document.getElementById('mydate').value;
						   		return confirm('Sure You Want To Add: '+date+'??? Action Cannot Be Easily Undone');
						    }
						}
						
						function check_form(){
							var valid = 'true';
							var x = document.getElementById('sens_num').value;
							
							//check date
		   	 				var date = 'mydate';
		   	 				var date_value = document.getElementById(date).value;
		   	 				if(date_value == ''){
		   	 					alert("Whoops! Please Enter Daily Date");
		   	 					document.getElementById(date).style.backgroundColor = 'blue';
		   	 					valid = 'false'
		   	 				}
		   	 				else{
		   	 					document.getElementById(date).style.backgroundColor = 'white';
		   	 				}
		   	 				
		   	 				//check selects are selected for required data
							var selects = document.getElementsByTagName("select");
				            var i2;
				             for (i2 = 0; i2 < selects.length; i2++) {
				                 selected = selects[i2].value;
				                 var name2 = selects[i2].getAttribute("name");
				                
					                 if(selected == '0'){
					                 	selects[i2].style.backgroundColor = "blue";
					                    valid = 'false';
					                 }
					                 else{
					                 	selects[i2].style.backgroundColor = "white";
					                 }
		
							}
		
							 //grab all inputs
				             var inputs = document.getElementsByTagName("input");
				             var txt = "";
				             var i;
				             for (i = 0; i < inputs.length; i++) {
				                 txt = inputs[i].value;
				                 var name = inputs[i].getAttribute("name");
				                 //check if your input is empty
					             var n = txt.length;
					             if(n == 0){
					             	inputs[i].style.background = "blue";
					                valid = 'false';
				                 }else{
									inputs[i].style.background = "white";
								}
							}
						
							if(valid == 'true'){ //if your form is still valid, go ahead and do some more checks
								//create a contains method to check if sensor is entered more than once
								Array.prototype.contains = function(needle){
									for (i in this){
										if(this[i]===needle){
											return true;
										}
									}
									return false;
								}
								
								var seen = [];
								//validate sensor data
								for (var index = 1; index <= x; index++) {
			   	 					var sensor_name = 'sensor'+index;
			   	 					//check that sensor is picked 
			   	 					var sensor_name_value = document.getElementById(sensor_name).value;
			   	 					
			 						//check to see if sensor name is already input
			 						if(seen.contains(sensor_name_value)){
			 							document.getElementById(sensor_name).style.backgroundColor = 'blue';
			 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
			 							valid = 'false';
			 						}
			 					    else{
			 							seen.push(sensor_name_value);
			 							document.getElementById(sensor_name).style.backgroundColor = 'white';
			 						}
			   	 					
			   	 					//check that start time is earlier than end time
			   	 					var start_time = 'stime'+index;
			   	 					var start_time_value = document.getElementById(start_time).value;
			   	 					
			   	 					
			   	 					var end_time = 'etime'+index;
			   	 					var end_time_value = document.getElementById(end_time).value;
		
			   	 					if(start_time_value > end_time_value){
			   	 						alert("Whoops! Please Check Start And End Times");
			   	 						document.getElementById(start_time).style.backgroundColor = 'blue';
			   	 						document.getElementById(end_time).style.backgroundColor = 'blue';
			   	 						valid = 'false';
			   	 					}
			   	 					else{
			   	 						document.getElementById(start_time).style.backgroundColor = 'white';
			   	 						document.getElementById(end_time).style.backgroundColor = 'white';
			   	 					}
				   	 				
				   	 				//check avg sensor measurement is a 2 digit decimal
				   	 				var measurement = 'measurement'+index;
			   	 					var measurement_value = document.getElementById(measurement).value;
			
		
		 							if(!measurement_value.match(/^\s*(?=.*[0-9])\d{0,4}(?:\.\d{1,2})?\s*$/)){
		 								document.getElementById(measurement).style.backgroundColor = 'blue';
		 								valid = 'false'
		 								alert("Whoops! Measurement Should Be No More Than 2 Decimal Places And 6 Digits");
		 							}
		 							else{
		 								document.getElementById(measurement).style.backgroundColor = 'white';
		 							}
			   	 					
								}
							}
							
							return valid;
						}
						
				
				
</script>