<?php 
include('../index.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../database_connection.php'); 
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Query All Results</title>

	<link rel="stylesheet" type="text/css" href="../aquired/freeze/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../aquired/freeze/dataTables.bootstrap.css">
	<style type="text/css" class="init">

	body { font-size: 140%; }

	/* Ensure that the demo table scrolls */
	th, td { white-space: nowrap; }
	div.dataTables_wrapper {
		width: 99%;
		margin: 0 auto;
	}

	</style>
	<script type="text/javascript" language="javascript" src="../aquired/freeze/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../aquired/freeze/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="../aquired/freeze/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="../aquired/freeze/dataTables.bootstrap.js"></script>
	<script type="text/javascript" language="javascript" class="init">


$(document).ready(function() {
	var table = $('#example').DataTable( {
		scrollY:        "300px",
		scrollX:        true,
		scrollCollapse: true,
		paging:         false
	} );
	new $.fn.dataTable.FixedColumns( table );
} );


	</script>
</head>

 
<body class="dt-example">
<pre>
	<h3>Display DB</h3>
</pre>
<pre>
<?php	

if(isset($_GET['submit_again'])){
	
	######still encode each output for html, because you have some stored html from user input that is rendering in browser
	$sql = "SELECT * FROM sample";
	$result = mysqli_query($dbc,"$sql");

	if ($result->num_rows > 0) {
    	// output data of each row

     	echo '<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">';
        
    	$header_ct = 0;
    	$row = mysqli_fetch_assoc($result);
    	$size =sizeof($row);
		#echo $size;
    	while($row = mysqli_fetch_assoc($result)){//could change to foreach?
    		#print_r($row);

       
        		       
		
	     	//print out headers
	     	if($header_ct == 0){
	     		echo '<thead>';
	        	echo '<tr>';
	
				foreach($row as $key => $value){
						
						$p_key = htmlspecialchars($key);
						echo '<th class="reg">'.$p_key.'</th>';		
	
					
				}
				$header_ct++;
	
				echo '</tr>';
				echo '</thead>';
				
	
			}
			else{
				if($header_ct == 1){
						
					echo '<tbody>';
				
				}
			
				
				//print out fields
				
				echo '<tr>';
	
				#echo "repeat";
				foreach($row as $key => $value){
					#echo $value;
					$p_value = htmlspecialchars($value);
		
			
					echo '<td class="reg">'.$p_value.'</td>';
	
				}
	
				$header_ct++;
	
				echo '</tr>';
				if($header_ct == $size-1){
						
					echo '</tbody>';
				
				}
	
			}
		}
			
        echo '</table>';

		
	}
}
?>
		

</pre>
</body>
</html>