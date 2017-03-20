			<script type="text/javascript">
			   var name_check = 'true';
			   function validate(from) {
			   		var valid = 'true';
				    if(check_samplers() == 'false'){
				    	valid = 'false';
				    }
				    if(check_form_required() == 'false'){
				    	valid = 'false';
				    }
				    if(check_required_user_things() == 'false'){
				    	valid = 'false';
				    }		
				    check_sample_name_update();
				    if(name_check == 'false'){
				    	alert('Sample Name Not Valid. Please Check Project Name And Sample Number');
				    	valid = 'false';
				    }
				   

				    if(valid === 'false'){
				    	alert('ERROR: Some inputs are invalid. Please check fields');
				    	return false;
				    }
				    else{
				   		return confirm('Sure You Want To Submit?');
				    }
				}
				
				function check_samplers(){
					var index;
					var valid = 'true';
					var x = document.getElementById("my_samp_num").value;

					if(x == 0){//this should never happen...
						valid = 'false';
						alert('ERROR!! There Are No Samplers. Please Add Some');
					}
					else{
						//create a contains method to check if mySamp is entered twice
						Array.prototype.contains = function(needle){
							for (i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						var seen = [];
						//validate mySamp data
						for (index = 1; index <= x; ++index) {
	   	 					var mySamp_name = 'mySamp'+index;
	   	 					//check that mySamp is picked 
	   	 					var mySamp_name_value = document.getElementById(mySamp_name).value;
	   	 					if(mySamp_name_value == '0' || mySamp_name_value == 'Needs to be added'){
	   	 						alert("Whoops! Sensor Name Is Not Valid");
	   	 						document.getElementById(mySamp_name).style.backgroundColor = '#f9ae7d';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						//check to see if mySamp name is already input
	   	 						if(seen.contains(mySamp_name_value)){
	   	 							document.getElementById(mySamp_name).style.backgroundColor = '#f9ae7d';
	   	 							alert("You Have Chosen More Than One Sensor With The Same Name. Please Check Names");
	   	 							valid = 'false';
	   	 						}
	   	 					    else{
	   	 							seen.push(mySamp_name_value);
	   	 							document.getElementById(mySamp_name).style.backgroundColor = 'white';
	   	 						}
	   	 					}
	   	 				
	   	 					//check start and end date/times are entered and make sense
	   	 					var start_time = 'stime'+index;
	   	 					var start_time_value = document.getElementById(start_time).value;
	   	 					if(start_time_value == ''){
	   	 						alert("Whoops! Please Enter A Start Time");
	   	 						document.getElementById(start_time).style.backgroundColor = '#f9ae7d';
	   	 						valid = 'false';
	   	 					}
	   	 					else{
	   	 						document.getElementById(start_time).style.backgroundColor = 'white';
	   	 					}
	   	 					
	   	 					var end_time = 'etime'+index;
	   	 					var end_time_value = document.getElementById(end_time).value;
	   	 					if(end_time_value == ''){
	   	 						alert("Whoops! Please Enter An End Time");
	   	 						document.getElementById(end_time).style.backgroundColor = '#f9ae7d';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					
	   	 					var start_date = 'sdate'+index;
	   	 					var start_date_value = document.getElementById(start_date).value;
	   	 					if(start_date_value == ''){
	   	 						alert("Whoops! Please Enter An Starting Date");
	   	 						document.getElementById(start_date).style.backgroundColor = '#f9ae7d';
	   	 						valid = 'false';
	   	 					}
	   	 					
	   	 					var end_date = 'edate'+index;
	   	 					
	   	 					var end_date_value = document.getElementById(end_date).value;
	   	 					if(end_date_value.length == '0'){
	   	 						alert("Whoops! Please Enter An End Date");
	   	 						document.getElementById(end_date).style.backgroundColor = '#f9ae7d';
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
									var mySamp_check = mySamp_name.match(/^Coriolis.*/);

									if(p_time < 0){
										valid = 'false';
										alert("Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = '#f9ae7d';
										document.getElementById(end_time).style.backgroundColor = '#f9ae7d';
										document.getElementById(start_date).style.backgroundColor = '#f9ae7d';
										document.getElementById(end_date).style.backgroundColor = '#f9ae7d';
									}
									else if(p_time > 6.5 && mySamp_check  != null){//check if coriolis sampling is greater than 6 hours
										valid = 'false';
										alert("Sampling Is Greater Than 6 Hours For Coriolis Sampling. Please Check Date/Times");
										document.getElementById(start_time).style.backgroundColor = '#f9ae7d';
										document.getElementById(end_time).style.backgroundColor = '#f9ae7d';
										document.getElementById(start_date).style.backgroundColor = '#f9ae7d';
										document.getElementById(end_date).style.backgroundColor = '#f9ae7d';
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