<!doctype html>
<html>
	<head>
		<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	</head>
	
	<body>
			<script type="text/javascript">
 			//$oStore_temp = $_GET['oStore_temp'];
 			var Store_temp = <?php echo(json_encode($_GET['Store_temp'])); ?>;
 			var Store_name = <?php echo(json_encode($_GET['Store_name'])); ?>;
 			//alert(Store_temp+Store_name);

			four=new Array("Akira","Dana");
			twenty=new Array('Akira','Caroline','Dana','Unassigned');
			eighty=new Array('Akira-F5V3','Caroline-Other');
			
			function populateSelect(Store_temp){
				//$('#oStore_name').append('<option>test</option>');
			    if(Store_temp =='-20C'){
			        twenty.forEach(function(t) { 
			            $('#'+Store_name).append('<option value='+t+'>'+t+'</option>');
			        });
			    }
			
			    if(Store_temp=='4C'){
			        four.forEach(function(t) {
			            $('#'+Store_name).append('<option value='+t+'>'+t+'</option>');
			        });
			    }
			    
			    if(Store_temp=='-80C'){
			        eighty.forEach(function(t) {
			            $('#'+Store_name).append('<option value='+t+'>'+t+'</option>');
			        });
			       
			    }
			    
			    //myFunction('Akira');
			   
			} 
			
			//function myFunction(t) {
   				 //document.getElementById("oStore_name").value = t;
			//}
		</script>
		
		<!--<select id="oStore_name" name = "oStore_name">
 			<option value="">-Select-</option>	
 			</select>-->
		<script>
 			populateSelect(Store_temp);
 		</script>

		</body>
	</html>
	