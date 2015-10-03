<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php'); 
include('functions/build_table.php');

?>

<!doctype html>
<html>
<head>
<title>Query Results</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="freeze/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="freeze/dataTables.bootstrap.css">
	<style type="text/css" class="init">
	body { font-size: 140%; }
	div.dataTables_wrapper {
		width: 99%;
		height: 100%
		margin: 0 auto;
	}
	div.dataTables_scrollBody {
		/*height: 90%*/
	}
	</style>
	<script type="text/javascript" language="javascript" src="freeze/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="freeze/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="freeze/dataTables.fixedColumns.js"></script>
	<script type="text/javascript" language="javascript" src="freeze/dataTables.bootstrap.js"></script>
	<script type="text/javascript" language="javascript" class="init">
		$(document).ready(function() {
			var table = $('#example').DataTable( {
				scrollY:        "90%", 
				scrollX:        true,
				scrollCollapse: true,
				paging:         false
			} );
			new $.fn.dataTable.FixedColumns( table );
		} );
	</script>
	
	 <script type="text/javascript">
        $(document).ready(function() {
            //$('#btnHide').click(function() {
            $('.reg').dblclick(function() {
				var index1 = $(this).index() + 1;
                $("td:nth-child(" +index1+ ")").toggleClass('hidden');
                $("th:nth-child(" +index1+ ")").toggleClass('hidden');
            });
        });
    </script>
</head>

 
<body class="dt-example">
<?php
	
	$p_project_name = 'Coriolis-test';
	$query = "SELECT * FROM sample WHERE project_name = ?";
	$stmt = $dbc->prepare($query);
	$stmt -> bind_param('s', $p_project_name);
	if($stmt){
		build_table($stmt,'display');
	}

	

		
?>
<!--	<table id="example_table" class="table table-striped table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
		<th>A</th>
		<th>B</th>
		<th>C</th>
	</tr>
			
		</thead>
		<tbody>
	
	<tr>
		<td>a</td>
		<td>b</td>
		<td>c</td>
	</tr>
	<tr>
		<td>e</td>
		<td>f</td>
		<td>g</td>
	</tr>
	</table>
</tbody>-->

</body>
</html>
