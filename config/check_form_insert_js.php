<script type="text/javascript">
       
       function check_form_required(){
       		//find out what sample type you are trying to submit
       		var sType = document.getElementById('sType').value; 
       		 
       		//create an array of all the required field names
       		var required = {
			    sample_number : "number",
			    projName : "text",
			    loc: "text",
			    rloc: "text",
			    media: "text",
			    'collector[]': "text",
			    sType: "text",
			    loc: "text",
			    oStore_name: "text",
			    oStore_temp: "text"
       		};
       		
       		//for all others, store what type they are to ensure correct format
       		 var non_required = {
			    barcode : "text",
				d_extr_date: "date",
				dExtKit: "text",
				dConc: "number",
				dVol: "number",
				dInstru: "text",
				dVol_quant: "text",
				dStore_temp: "text",
				dStore_name: "text",
				'dExtrName[]': "text",
				orig_sample_exist: "text",
				DNA_sample_exist: "text",
				r_extr_date: "date",
				rExtKit: "text",
				rConc: "number",
				rVol: "number",
				rInstru: "text",
				rVol_quant: "text",
				rStore_temp: "text",
				rStore_name: "text",
				'rExtrName[]': "text",
				orig_sample_exist: "text",
				RNA_sample_exist: "text",
				seqInfo: "text",
				anPipe: "text",
				notes: "text",
				
				//special
				fRate: "number",
				fRate_eod: "number",
				sampling_height: "number",
       		};

             var index;
             var valid = 'true';
          
             //grab all inputs
             var inputs = document.getElementsByTagName("input");
             var txt = "";
             var i;
             for (i = 0; i < inputs.length; i++) {
             //for (i = 0; i < 10; i++) {
                 txt = inputs[i].value;
                 
                 var name = inputs[i].getAttribute("name");
                 //////////////////////
                 ////***Required**//// dont forget to change the length of iteration
                 ////////////////////
                 
               	//if name is in the required list, check that the length is zero and format is correct
               	if(required[name] && required[name] == 'text'){
                 	 //check if your input is empty
	                 var n = txt.length;
	                 if(n == 0){
	                    inputs[i].style.background = "blue";
	                    valid = 'false';
                 	}else{
                 		//if you are a collector name, check if you are the correct format and if you are duplicates
                 		if(name == 'collector[]'){
                 			var name_check = txt.match(/^[a-zA-Z, ()]+$/); //should match anthing that is not these character
							if (name_check == null){ //null means you did not find the pattern
								alert("Invalid Collector Name. Please Check Names");
								inputs[i].style.background = "blue";
		                    	valid = 'false';
							}else{
								inputs[i].style.background = "white";
							}
							
							
							//create a contains method to check if seen name twice
							Array.prototype.contains = function(needle){
								for (var i in this){
									if(this[i]===needle){
										return true;
									}
								}
								return false;
							}
							var seen = [];
							txt = txt.trim();
							var names = txt.split(',');
							x = names.length;
							
							for (index = 0; index < x; ++index) {
		   	 					var name = names[index];
		   	 			
		   	 					//check to see if extractor name is already input
		   	 					if(seen.contains(name)){
		   	 						inputs[i].style.background = "blue";
		   	 						alert("You Have One Or More With The Same Name. Please Check Names");
		   	 						valid = 'false';
		   	 					}
		   	 					else{
		   	 						seen.push(name);
		   	 						inputs[i].style.background = "white";
		   	 					}	
		   	 				}
								
                 		}
                 		else{
                 			inputs[i].style.background = "white";
                 		}
                 	}
                 }
                 else if (required[name] && required[name] == 'number'){
                 		//***check number format**// should only be sample number, so make sure 3 digits
                 		var sample_number_check = txt.match(/^[0-9]{3}$/);
						if (sample_number_check == null){
							alert("Sample Number Must Be 3-Digit Format");
							inputs[i].style.background = "blue";
	                    	valid = 'false';
						}else{
							inputs[i].style.background = "white";
						}
                 }
                 else{
                 	 //////////////////////
	                 //***Not Required**//
	                 ////////////////////
                 	//check non required fields
                 	if(non_required[name] && non_required[name] == 'text'){// ok for non required text fields to be empty...this is just a placeholder for now
                 	
                 		/*	alert('working');
                 		 //check if your input is empty
	                	var n = txt.length;
	                 	if(n == 0){
	                    	inputs[i].style.background = "blue";
	                   	 	valid = 'false';
                 		}*/
                 		
                 		//////////////////////////////////////////////
                 		 //check if your input is empty
	               
                 		
                 		
                 		
                 		
                 		
                 		
                 		
                 		
                 		
                 		/////////////////////////////////////////////////
                 		
                 		
                 		
                 		
                 	}
                 	else if (non_required[name] && non_required[name] == 'number'){
                 		//check number format
                 		//should be for DNA conc/vol and RNA conc/vol
                 		/*if(name == 'dConc' || name == 'dVol' || name == 'rConc' || name == 'rVol'){
	                 		var nr_number_check = txt.match(/^\s*(?=.*[1-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
							if (nr_number_check  == null){
								alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
								inputs[i].style.background = "blue";
		                    	valid = 'false';
							}else{
								inputs[i].style.background = "white";
							}
						}*/
						
						if(name == 'sampling_height'){
							//if you are an air sample you cannot be empty or zero
							if (sType == "A" ) {
								var color_check = 'true';
								//check you are not empty
								var n = txt.length;
	                 			if(n == 0){
	                 				alert("Sampling Height Cannot Be Zero Or Empty For Air Samples");
	                    			inputs[i].style.background = "blue";
	                   	 			valid = 'false';
	                   	 			color_check = 'false';
                 				}
                 				//check you are not zero
								var regrex_check_sh  = txt.match(/^\s*(?=.*[0-9])[0]{0,5}(?:\.[0]{1,2})?\s*$/);//trying to match any zero here
								if (regrex_check_sh  != null){
									alert("Sampling Height Cannot Be Zero Or Empty For Air Samples");
									inputs[i].style.background = "blue";
			                    	valid = 'false';
			                    	color_check = 'false';
								}
								
								//check you are the correct format
								var regrex_check_sh2  =  txt.match(/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/);//this can be zero
								if (regrex_check_sh2 == null){
									alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
									inputs[i].style.background = "blue";
					                valid = 'false';
					                color_check = 'false';
								}
								
								if(color_check == 'true'){
									inputs[i].style.background = "white";
								}
							}
							else{
								var n = txt.length;
	                 			if(n != 0){
	              
									var regrex_check_sh  =  txt.match(/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/);//this can be zero
									if (regrex_check_sh == null){
										alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
										inputs[i].style.background = "blue";
					                	valid = 'false';
									}else{
										inputs[i].style.background = "white";
									}
								}
							} 
						}
						
						//repeat for flow rate
						/////////////////////////////////////////////
						if(name == 'fRate' || name == 'fRate_eod'){
							//if you are an air sample you cannot be empty or zero
							if (sType == "A" ) {
								var color_check = 'true';
								//check you are not empty
								var n = txt.length;
	                 			if(n == 0){
	                 				alert("Flow Rate Cannot Be Zero Or Empty For Air Samples");
	                    			inputs[i].style.background = "blue";
	                   	 			valid = 'false';
	                   	 			color_check = 'false';
                 				}
                 				//check you are not zero
								var regrex_check_sh  = txt.match(/^\s*(?=.*[0-9])[0]{0,5}(?:\.[0]{1,2})?\s*$/);//trying to match any zero here
								if (regrex_check_sh  != null){
									alert("Flow Rate Cannot Be Zero Or Empty For Air Samples");
									inputs[i].style.background = "blue";
			                    	valid = 'false';
			                    	color_check = 'false';
								}
								
								//check you are the correct format
								var regrex_check_sh2  =  txt.match(/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/);//this can be zero
								if (regrex_check_sh2 == null){
									alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
									inputs[i].style.background = "blue";
					                valid = 'false';
					                color_check = 'false';
								}
								
								if(color_check == 'true'){
									inputs[i].style.background = "white";
								}
							}
							else{
								var n = txt.length;
	                 			if(n != 0){
	              
									var regrex_check_sh  =  txt.match(/^\s*(?=.*[0-9])\d{0,5}(?:\.\d{1,2})?\s*$/);//this can be zero
									if (regrex_check_sh == null){
										alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
										inputs[i].style.background = "blue";
					                	valid = 'false';
									}else{
										inputs[i].style.background = "white";
									}
								}
							} 
						}
						//////////////////////////////////////////////
                 	}
                 } 
			}
			
			//check selects are selected for required data
			//required vs non-required ...really only need for required
			
			var selects = document.getElementsByTagName("select");
            var i2;
             for (i2 = 0; i2 < selects.length; i2++) {
                 selected = selects[i2].value;
                 var name2 = selects[i2].getAttribute("name");
                 if(required[name2]){
	                 if(selected == '0'){
	                 	selects[i2].style.background = "blue";
	                    valid = 'false';
	                 }
	                 else{
	                 	selects[i2].style.background = "white";
	                 }
	              }
			}
			
			
			
			/**Specials**/
			
			//////////////////////////////////////////////////////////////
			//check if some DNA filled out that all of it is filled out
			//////////////////////////////////////////////////////////////
			var empty = [];
			var filled = [];
			var filled_out = 0;
			var counter = 0;
			
			var dna_selects = document.getElementById('dna_extraction').getElementsByTagName('select');
            var i3;
             for (i3 = 0; i3 < dna_selects.length; i3++) {
                 dna_selected = dna_selects[i3].value;
                 var dna_select_name = dna_selects[i3].getAttribute("id");
              
               
          		 if(dna_selected != '0'){
	                 counter++;
	                 filled_out++;
	                 filled.push(dna_select_name);
	             }
	             else{
	                 counter++;
	                 empty.push(dna_select_name);
	             }
			}
			
			var dna_inputs = document.getElementById('dna_extraction').getElementsByTagName('input');
            var i4;
             for (i4 = 0; i4 < dna_inputs.length; i4++) {
                 dna_inputed = dna_inputs[i4].value;
                 var type = dna_inputs[i4].type;
                 var dna_input_name = dna_inputs[i4].getAttribute("id");
               
                 if(type == 'text'){
	          		 if(dna_inputed.length != '0'){
		                 counter++;
		                 filled_out++;
		                 filled.push(dna_input_name);
		             }
		             else{
		                 counter++;
		                 empty.push(dna_input_name);
		             }
	             }
			}

			if( filled_out != 0 && filled_out != counter){
				alert("Please Fill Out All DNA Fields");
				valid = 'false';
				
				var i5;
            	for (i5 = 0; i5 < empty.length; i5++) {
            		document.getElementById(empty[i5]).style.backgroundColor = 'blue';
            	}
            	var i6;
            	for (i6 = 0; i6 < filled.length; i6++) {
            		//check filled name and if it is a collector, check it is formatted correctly
            		///////////////////////////////////////////////////////////////////////////////////////
                 		//if you are an name, check if you are the correct format and if you are duplicates
                 		if(filled[i6] == 'dExtrName'){
                 			var extractor_names = document.getElementById(filled[i6]).value;
                 			var name_check = extractor_names.match(/^[a-zA-Z, ()]+$/); //should match anthing that is not these character
							if (name_check == null){ //null means you did not find the pattern
								alert("Invalid Extractor Name. Please Check Names");
								document.getElementById(filled[i6]).style.backgroundColor = 'blue';
		                    	valid = 'false';
							}else{
								document.getElementById(filled[i6]).style.backgroundColor = 'white';
							}
							
							
							//create a contains method to check if seen name twice
							Array.prototype.contains = function(needle){
								for (var i in this){
									if(this[i]===needle){
										return true;
									}
								}
								return false;
							}
							var seen = [];
							extractor_names = extractor_names.trim();
							var names = extractor_names.split(',');
							x = names.length;
							
							for (var index = 0; index < x; ++index) {
		   	 					var name = names[index];
		   	 					//check to see if airSamp name is already input
		   	 					if(seen.contains(name)){
		   	 						document.getElementById(filled[i6]).style.backgroundColor = 'blue';
		   	 						alert("You Have One Or More Extractor Name With The Same Name. Please Check Names");
		   	 						valid = 'false';
		   	 					}
		   	 					else{
		   	 						seen.push(name);
		   	 						document.getElementById(filled[i6]).style.backgroundColor = 'white';
		   	 					}	
		   	 				}
								
                 		}
                 		else{
                 			document.getElementById(filled[i6]).style.backgroundColor = 'white';
                 		}
  
            		////////////////////////////////////////////////////////////////////////////////////////
            		//document.getElementById(filled[i6]).style.backgroundColor = 'white';
            	}
			}
			else if (filled_out == counter && filled_out != 0){ //assume everythingis filled in 
				var i6;
				for (i6 = 0; i6 < filled.length; i6++) { //assume everything is okie again and check again
					document.getElementById(filled[i6]).style.backgroundColor = 'white';
				}
				var conc = document.getElementById('dConc').value;
	            var conc_check = conc.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (conc_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('dConc').style.background = "blue";
		            valid = 'false';
				}
				else{
					document.getElementById('dConc').style.background = "white";
				}
				
				var vol = document.getElementById('dVol').value;
				var vol_check = vol.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (vol_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('dVol').style.background = "blue";
		            valid = 'false';
				}else{
					document.getElementById('dVol').style.background = "white";
				}
				
				var vol_quant = document.getElementById('dVol_quant').value;
				var vol_check = vol_quant.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (vol_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('dVol_quant').style.background = "blue";
		            valid = 'false';
				}else{
					document.getElementById('dVol_quant').style.background = "white";
				}
				
				
				//check extractor names
                var extractor_names = document.getElementById('dExtrName').value;
                var name_check = extractor_names.match(/^[a-zA-Z, ()]+$/); //should match anthing that is not these character
				if (name_check == null){ //null means you did not find the pattern
						alert("Invalid Extractor Name. Please Check Names");
						document.getElementById('dExtrName').style.backgroundColor = 'blue';
		                   valid = 'false';
				}
				else{
					document.getElementById('dExtrName').style.backgroundColor = 'white';
				}
							
							
				//create a contains method to check if seen name twice
				Array.prototype.contains = function(needle){
					for (var i in this){
						if(this[i]===needle){
							return true;
						}
					}
					return false;
				}
				var seen = [];
				extractor_names = extractor_names.trim();
				var names = extractor_names.split(',');
				x = names.length;
							
				
				for (var index = 0; index < x; ++index) {
					var name = names[index];
		   	 		//check to see if airSamp name is already input
					if(seen.contains(name)){
		 				document.getElementById('dExtrName').style.backgroundColor = 'blue';
						alert("You Have One Or More Extractor Name With The Same Name. Please Check Names");
						valid = 'false';
					}
					else{
						seen.push(name);
						document.getElementById('dExtrName').style.backgroundColor = 'white';
					}	
				}
								
			}
			else{
                 	var i9;
					for (i9 = 0; i9 < empty.length; i9++) {
						document.getElementById(empty[i9]).style.backgroundColor = 'white';
					}
			}
			///////////////////////////////////////////////////////////////
			//check if some RNA filled out that all of it is filled out
			///////////////////////////////////////////////////////////////
			var empty2 = [];
			var filled2 = [];
			var filled2_out = 0;
			var counter2 = 0;
			
			var rna_selects = document.getElementById('rna_extraction').getElementsByTagName('select');
            var i9;
             for (i9 = 0; i9 < rna_selects.length; i9++) {
                 rna_selected = rna_selects[i9].value;
                 var rna_select_name = rna_selects[i9].getAttribute("id");
               
          		 if(rna_selected != '0'){
	                 counter2++;
	                 filled2_out++;
	                 filled2.push(rna_select_name);
	             }
	             else{
	                 counter2++;
	                 empty2.push(rna_select_name);
	             }
			}
			
			var rna_inputs = document.getElementById('rna_extraction').getElementsByTagName('input');
            var i10;
             for (i10 = 0; i10 < rna_inputs.length; i10++) {
                 var rna_inputed = rna_inputs[i10].value;
                 var type2 = rna_inputs[i10].type;
                 var rna_input_name = rna_inputs[i10].getAttribute("id");
               
                 if(type2 == 'text'){
	          		 if(rna_inputed.length != '0'){
		                 counter2++;
		                 filled2_out++;
		                 filled2.push(rna_input_name);
		             }
		             else{
		                 counter2++;
		                 empty2.push(rna_input_name);
		             }
	             }
			}

			if( filled2_out != 0 && filled2_out != counter2){
				alert("Please Fill Out All RNA Fields");
				valid = 'false';
				
				var i7;
            	for (i7 = 0; i7 < empty2.length; i7++) {
            		document.getElementById(empty2[i7]).style.backgroundColor = 'blue';
            	}
            	var i8;
            	for (i8 = 0; i8 < filled2.length; i8++) {
            		if(filled2[i8] == 'rExtrName'){
                 		var extractor_names = document.getElementById(filled2[i8]).value;
                 		var name_check = extractor_names.match(/^[a-zA-Z, ()]+$/); //should match anthing that is not these character
						if (name_check == null){ //null means you did not find the pattern
							alert("Invalid RNA Extractor Name. Please Check Names");
							document.getElementById(filled2[i8]).style.backgroundColor = 'blue';
		                    valid = 'false';
						}else{
							document.getElementById(filled2[i8]).style.backgroundColor = 'white';
						}
							
							
						//create a contains method to check if seen name twice
						Array.prototype.contains = function(needle){
							for (var i in this){
								if(this[i]===needle){
									return true;
								}
							}
							return false;
						}
						var seen = [];
						extractor_names = extractor_names.trim();
						var names = extractor_names.split(',');
						x = names.length;
							
						for (var index = 0; index < x; ++index) {
		   	 				var name = names[index];
		   	 				//check to see if sampler name is already input
		   	 				if(seen.contains(name)){
		   	 					document.getElementById(filled2[i8]).style.backgroundColor = 'blue';
		   	 					alert("You Have One Or More Extractor Name With The Same Name. Please Check Names");
		   	 					valid = 'false';
		   	 				}
		   	 				else{
		   	 					seen.push(name);
		   	 					document.getElementById(filled2[i8]).style.backgroundColor = 'white';
		   	 				}	
		   	 			}
								
                 	}
                 	else{
                 		document.getElementById(filled2[i8]).style.backgroundColor = 'white';
                 	}
  
 
            		//document.getElementById(filled2[i8]).style.backgroundColor = 'white';
            	}
			}
			else if (filled2_out == counter2 && filled2_out != 0){ //assume everythingis filled in 
				var i6;
				for (i6 = 0; i6 < filled.length; i6++) { //assume everything is okie again and check again
					document.getElementById(filled[i6]).style.backgroundColor = 'white';
				}

				var rconc = document.getElementById('rConc').value;
	            var rconc_check = rconc.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (rconc_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('rConc').style.background = "blue";
		            valid = 'false';
				}else{
					document.getElementById('rConc').style.background = "white";
				}
				
				var rvol = document.getElementById('rVol').value;
				var rvol_check = rvol.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (rvol_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('rVol').style.background = "blue";
		            valid = 'false';
				}else{
					document.getElementById('rVol').style.background = "white";
				}
				
				var rvol_quant = document.getElementById('rVol_quant').value;
				var rvol_check = rvol_quant.match(/^\s*(?=.*[0-9])\d{0,3}(?:\.\d{1,2})?\s*$/);
				if (rvol_check  == null){
					alert("Number Must Be 2 Decimal Places Or Less and 3 Digits Or Less");
					document.getElementById('rVol_quant').style.background = "blue";
		            valid = 'false';
				}else{
					document.getElementById('rVol_quant').style.background = "white";
				}
				////////////////////////////////
				var extractor_names = document.getElementById('rExtrName').value;
                var name_check = extractor_names.match(/^[a-zA-Z, ()]+$/); //should match anthing that is not these character
				if (name_check == null){ //null means you did not find the pattern
					alert("Invalid RNA Extractor Name. Please Check Names");
					document.getElementById('rExtrName').style.backgroundColor = 'blue';
		            valid = 'false';
				}else{
					document.getElementById('rExtrName').style.backgroundColor = 'white';
				}
							
				//create a contains method to check if seen name twice
				Array.prototype.contains = function(needle){
					for (var i in this){
						if(this[i]===needle){
							return true;
						}
					}
					return false;
				}
				var seen = [];
				extractor_names = extractor_names.trim();
				var names = extractor_names.split(',');
				x = names.length;
							
	
				for (var index = 0; index < x; ++index) {
		   	 		var name = names[index];
		   	 		//check to see if extractor name is already input
		   	 		if(seen.contains(name)){
		   	 			document.getElementById('rExtrName').style.backgroundColor = 'blue';
		   	 			alert("You Have One Or More Extractor Name With The Same Name. Please Check Names");
		   	 			valid = 'false';
		   	 		}
		   	 		else{
		   	 			seen.push(name);
		   	 			document.getElementById('rExtrName').style.backgroundColor = 'white';
		   	 		}	
		   	 	}
								
			}
			else{
                 	var i10;
					for (i10 = 0; i10 < empty2.length; i10++) {
						document.getElementById(empty2[i10]).style.backgroundColor = 'white';
					}
			}

            return valid;
       }

</script>
