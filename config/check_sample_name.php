<!--Pick Sensor Info-->
<script type="text/javascript">
		    
       function check_sample_name_update(){
                var projectName = $('#projName').val(); //<----- get the value from the parent select 
                var sampleNumber = $('#sample_number').val(); 
                var name = $('#sample_name').val(); 
                //alert(num_sensors);
	               $.ajax({
	                    url     : root+'sample_insert/sample_name_validation.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : {proj: projectName,sa: sampleNumber,current_name: name},
	                    success : function(data){  
	                    				var res = data.match(/false/);
						                if(res != null){  
						                    //show that the username is available  
						                    $('#samplename_availability_result').html('Sample Name is Available');
						                    //valid = 'true';
						                    name_check = 'true';
						                }else{  
						                    //show that the username is NOT available  
						                    $('#samplename_availability_result').html('Sample Name is not Available');  
						                    //valid = 'false';
						                     name_check = 'false';
						                }  
						          },
	                    error   : function(){alert('an error has occured')}, //error message
	                    async   : false
	                })
		};	
		
		function check_sample_name_insert(){
                var projectName = $('#projName').val(); //<----- get the value from the parent select 
                var sampleNumber = $('#sample_number').val(); 
                //alert(num_sensors);
	             $.ajax({
	                    url     : root+'sample_insert/sample_name_validation.php', //the url you are sending datas to which will again send the result
	                    type    : 'GET', //type of request, GET or POST
	                    data    : {proj: projectName,sa: sampleNumber},
	                     success : function(data){  
	                    				var res = data.match(/false/);
						                if(res != null){  
						                    //show that the name is available  
						                    $('#samplename_availability_result').html('Sample Name is Available');
						                    //valid = 'true';
						                    name_check = 'true';
						                }else{  
						                    //show that the name is NOT available  
						                    $('#samplename_availability_result').html('Sample Name is not Available');  
						                    //valid = 'false';
						                     name_check = 'false';
						                }  
						          },
	                    error   : function(){alert('an error has occured')}, //error message
	                    async   : false
	                })
		};	
		
</script>


