<?php //Note: Testing for use for label printing. works from main directory?>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('database_connection.php');
?>
<html>
<head>
<title>Query Results For Labels</title>
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
	<?php include('functions/build_table.php'); ?>
</head>

<body class="dt-example">

<?php
if(isset($_GET['submit_labels']) && $_GET['db_content']=='xls'){	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=sample_label_description.xls");
	#header("Content-Disposition: attachment;Filename=sample_label_description.txt");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
	echo "<body>";

	$sql = "SELECT sample_name,start_samp_date_time,project_name,sample_type,sample_num FROM sample";
	$stmt = $dbc->prepare("$sql");
	$_SESSION['label_prep'] = 'TRUE';
	build_table($stmt);
	
	unset($_SESSION['label_prep']);
	
	 //////////////////////////////////////////////////////////////////////////////////////////////////////;
	 //add this as tab delimited regular text file >.<
	 //and write up how to download and create labels
	 //also add on project name page that you want a certain project name length
	 //check numbers and time (why is time still there and why can't you import the text...only view from html)

echo "</body>";
echo "</html>";
}
	
else{
	include('index.php');

	//check sample name exists
	#$stmt = $dbc->prepare("SELECT sample_name,location_name FROM sample");
	$stmt = $dbc->prepare("SELECT sample_name,start_samp_date_time,project_name,sample_type,sample_num FROM sample");
	#$stmt -> bind_param('s', $p_query_basis);
				
	 if ($stmt->execute()){
	    			
	    if($stmt->fetch()){
	    	$meta = $stmt->result_metadata(); 
   			while ($field = $meta->fetch_field()){ 
        		$params[] = &$row[$field->name]; 
    		} 

    		call_user_func_array(array($stmt, 'bind_result'), $params); 
			build_table($stmt);	
		}
	}
}
							
?>


</body>
</html>
