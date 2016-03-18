<script type="text/javascript">
       
       function check_required_user_things(){
       		
            var valid = 'true';
			var required_selects = document.getElementById('required_things').getElementsByTagName('select');
             for (var i9 = 0; i9 < required_selects.length; i9++) {
                 var selected = required_selects[i9].value;
                 var select_id = required_selects[i9].getAttribute("id");
          		 if(selected == ''){
          		 	 valid = 'false';
	                 document.getElementById(select_id).style.backgroundColor = 'blue';
	             }else{
	             	document.getElementById(select_id).style.backgroundColor = 'white';
	             }
	             
			}
			
			var required_inputs = document.getElementById('required_things').getElementsByTagName('input');
            for (var i10 = 0; i10 < required_inputs.length; i10++) {
                 var inputed = required_inputs[i10].value;
                 var input_id = required_inputs[i10].getAttribute("id");
                 var type = required_inputs[i10].getAttribute("class");
	             if(inputed == ''){
	             	 valid = 'false';
	                 document.getElementById(input_id).style.backgroundColor = 'blue';
	             }else{
	             	//select type from create_user_things where thing_id = input_id. if type equals numeric_input, do a check
	             	
	             	if(type == 'numeric_input'){
	             		var numeric_check = isNaN(inputed); //returns true if is not a number
		             	if(numeric_check == true){
		             		valid = 'false';
		             		document.getElementById(input_id).style.backgroundColor = 'blue';
		             	}else{
		             		document.getElementById(input_id).style.backgroundColor = 'white';
		             	}
	             	}else{
	             		document.getElementById(input_id).style.backgroundColor = 'white';
	             	}
	             }
			}
			
			//check non -required numeric fields also
			var thing_inputs = document.getElementById('user_things').getElementsByTagName('input');
            for (var i11 = 0; i11 < thing_inputs.length; i11++) {
                 var inputed = thing_inputs[i11].value;
                 var input_id = thing_inputs[i11].getAttribute("id");
                 var type = thing_inputs[i11].getAttribute("class");
	        	 if(type == 'numeric_input'){
	             	var numeric_check = isNaN(inputed); //returns true if is not a number
		            if(numeric_check == true){
		            	valid = 'false';
		             	document.getElementById(input_id).style.backgroundColor = 'blue';
		             }
		             else{
		             	document.getElementById(input_id).style.backgroundColor = 'white';
		             }
	             }
	             else{
	             	document.getElementById(input_id).style.backgroundColor = 'white';
	             }
			}
            return valid;
       }

</script>
