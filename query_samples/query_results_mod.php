<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!isset($_SESSION)) { session_start(); }
include('../database_connection.php'); 
$path = $_SESSION['include_path'];

////////////////////////////////////////////////////////////////////////////////////////////////
//choose which files to include depending on if you are exporting an xls or not.
//xls cannot include the index file because it will send headers too early (and the wrong ones)
////////////////////////////////////////////////////////////////////////////////////////////////
if((isset($_GET['db_view'])) && ($_GET['db_view'] == 'xls')){
	include('../index.php');
	include($path.'functions/build_xls_output_table.php');
}else{
	
	
	
	//check what kind of contents user is wanting to display
	if(isset($_GET['db_content']) && ($_GET['db_content'] == 'bulk_dna')){
		include('../index.php');
		include($path.'functions/build_bulk_dna_table.php');
	}
	elseif(isset($_GET['db_content']) && ($_GET['db_content'] == 'bulk_storage')){
		include('../index.php');
		include($path.'functions/build_bulk_storage_update_table.php');
	}
	elseif(isset($_GET['db_content']) && ($_GET['db_content'] == 'read_sub')){
		include('../index.php');
		include($path.'functions/build_bulk_read_sub_id_table.php');
	}
	elseif(isset($_GET['db_content']) && ($_GET['db_content'] == 'update_read_sub')){
		include('../index.php');
		include($path.'functions/build_bulk_read_sub_id_update_table.php');
	}
	elseif(isset($_GET['db_content']) && ($_GET['db_content'] == 'bulk_things')){
		//header('Location: bulk_insert_and_updates/things_bulk_update_select.php');
		#bulk_things
		include('../index.php');
		include($path.'functions/build_bulk_thing_update_table.php');
		
		
	}
	//elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'view_user_things'){
	//	include('../index.php');
	//	include($path.'functions/build_user_things_view.php');
	//}
	else{
		include('../index.php');
		include($path.'functions/build_query_results_table.php');
		include($path.'functions/basic_build_table.php');
		include($path.'functions/build_table.php');
	}
}
	


?>

<!doctype html>
<html>
<head>
<title>Query Results</title>
	<meta charset="utf-8">
	
	<style>
		div.dataTables_wrapper {
        	width: 100%;
        	margin: 10px 10px 0 0 ;
    	}
    	
    	@media (max-width: 640px) {
			th, td { white-space: wrap; }
		}
		@media (min-width: 641px){
			th, td { white-space: wrap;}
			/*td{overflow:auto;}
			div.block{overflow: auto;}
			div.sample_name{
				overflow:auto;
				
			}*/
			div.block{
				max-width:300px;
				white-space: wrap;
			}
			div.sample_name { 
			  min-width: 300px; 
			  white-space: wrap;
			}
		}

	</style>
	<script type="text/javascript">
	
		//For bulk table updates
		$(document).ready(function () {
		   //$('button').on('click', '#selectAll', function () {
		   	
		   	$('#selectAll').click(function(){
		   		//alert("test");
		      if ($(this).hasClass('allChecked')) {
		         $('input[type="checkbox"]', '#datatable_bulk').prop('checked', false);
		      } else {
		       $('input[type="checkbox"]', '#datatable_bulk').prop('checked', true);
		       }
		       $(this).toggleClass('allChecked');
		     })
		     
		     
		     	//$('#datatable_bulk').DataTable( {
			    //     "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]]
			    //} );
				
				    $('#datatable_bulk')
			    	.addClass('wrap')
				    .dataTable( {
				         responsive: true
				    } );
				
				// DataTable
				//var table_bulk = $('#datatable_bulk').DataTable();
	 
		});
	
	
	
		//For regular query tables	
		$(document).ready(function() 
		{
			//Prevent collapse of headers
			$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
       			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust().fixedColumns().relayout();
    		} );
    		
			// Setup - add a text input to each footer cell
			$('#datatable tfoot th').each( function () 
			{
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
			} );
			
			//Adding x-scroll, fixed first column and shorten show row number
			$('#datatable').DataTable( {
		        "scrollX": true,
		         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
		         "fixedColumns":   {
		            leftColumns: 1,
		        }
		    } );
			
			// DataTable
			var table = $('#datatable').DataTable();
 
			// Apply the search
			table.columns().every( function () 
			{
				var that = this;
 
				$( 'input', this.footer() ).on( 'keyup change', function () 
				{
					if ( that.search() !== this.value ) 
					{
						that
							.search( this.value )
							.draw();
					}
				} );
			} );
		} );
		
		
		//table 2
		$(document).ready(function() 
		{
			
			// Setup - add a text input to each footer cell
			$('#datatable2 tfoot th').each( function () 
			{
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
			} );
			
				//Adding x-scroll, fixed first column and shorten show row number
			$('#datatable2').DataTable( {
		        "scrollX": true,
		         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
		         "fixedColumns":   {
		            leftColumns: 1,
		             
		        }
		    } );
			
 
			// DataTable
			var table2 = $('#datatable2').DataTable();

			// Apply the search
			table2.columns().every( function () 
			{
				var that = this;
 
				$( 'input', this.footer() ).on( 'keyup change', function () 
				{
					if ( that.search() !== this.value ) 
					{
						that
							.search( this.value )
							.draw();
					}
				} );
			} );
		} );
		
		//table 3
		$(document).ready(function() 
		{
			// Setup - add a text input to each footer cell
			$('#datatable3 tfoot th').each( function () 
			{
				var title = $(this).text();
				$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
			} );
 
 
 			//Adding x-scroll, fixed first column and shorten show row number
			$('#datatable3').DataTable( {
		        "scrollX": true,
		         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
		         "fixedColumns":   {
		            leftColumns: 1,
		        }
		    } );
			
 
			// DataTable
			var table3 = $('#datatable3').DataTable();
 
			// Apply the search
			table3.columns().every( function () 
			{
				var that = this;
 
				$( 'input', this.footer() ).on( 'keyup change', function () 
				{
					if ( that.search() !== this.value ) 
					{
						that
							.search( this.value )
							.draw();
					}
				} );
			} );
			
			
			//table 4
			$(document).ready(function() 
			{
				// Setup - add a text input to each footer cell
				$('#datatable4 tfoot th').each( function () 
				{
					var title = $(this).text();
					$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
				} );
	 
	 			//Adding x-scroll, fixed first column and shorten show row number
				$('#datatable4').DataTable( {
			        "scrollX": true,
			         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
			         "fixedColumns":   {
			            leftColumns: 1,
			        }
			    } );
			
 
				// DataTable
				var table4 = $('#datatable4').DataTable();
	 
				// Apply the search
				table4.columns().every( function () 
				{
					var that = this;
	 
					$( 'input', this.footer() ).on( 'keyup change', function () 
					{
						if ( that.search() !== this.value ) 
						{
							that
								.search( this.value )
								.draw();
						}
					} );
				} );
			} )
			
			
			//table 5
			$(document).ready(function() 
			{
				// Setup - add a text input to each footer cell
				$('#datatable5 tfoot th').each( function () 
				{
					var title = $(this).text();
					$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
				} );
	 
	 
	 			//Adding x-scroll, fixed first column and shorten show row number
				$('#datatable5').DataTable( {
			        "scrollX": true,
			         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
			         "fixedColumns":   {
			            leftColumns: 1,
			        }
			    } );
			
 
				// DataTable
				var table5 = $('#datatable5').DataTable();
	 
				// Apply the search
				table5.columns().every( function () 
				{
					var that = this;
	 
					$( 'input', this.footer() ).on( 'keyup change', function () 
					{
						if ( that.search() !== this.value ) 
						{
							that
								.search( this.value )
								.draw();
						}
					} );
				} );
			} )
			
			
			//table 6
			$(document).ready(function() 
			{
				// Setup - add a text input to each footer cell
				$('#datatable6 tfoot th').each( function () 
				{
					var title = $(this).text();
					$(this).html( '<input type="text" style="width:100px" placeholder="Search" />' );
				} );
	 
	 			//Adding x-scroll, fixed first column and shorten show row number
				$('#datatable6').DataTable( {
			        "scrollX": true,
			         "lengthMenu": [[8, 25, 50, -1], [8, 25, 50, "All"]],
			         "fixedColumns":   {
			            leftColumns: 1,
			        }
			    } );
			
	 
	 
				// DataTable
				var table6 = $('#datatable6').DataTable();
	 
				// Apply the search
				table6.columns().every( function () 
				{
					var that = this;
	 
					$( 'input', this.footer() ).on( 'keyup change', function () 
					{
						if ( that.search() !== this.value ) 
						{
							that
								.search( this.value )
								.draw();
						}
					} );
				} );
			} )
			
		} );
	</script>
</head>

 
<body class="dt-example">
<?php

if(isset($_GET['submit'])){
	//include($path.'functions/check_box_tables_output.php');
	include($path.'functions/white_list.php');
	
	$submit = $_GET['submit'];
	
	//sample
	if($submit == 'sample'){
			
			
		////////////////////////////////////////////////////////////////////////////////////////////////
		//Define what kind of fields you are querying
		////////////////////////////////////////////////////////////////////////////////////////////////		
		
		$check_date = 'false';
		$check_field = 'false';
		$query_date = '';
		$query_field = '';
		$stmt = '';
		$selected_thing_id = '';
		if(isset($_GET['thing_select']) && $_GET['thing_select'] != 0){
			$selected_thing_id = $_GET['thing_select'];
		}
		if(($_GET['smydate'] != NULL) && ($_GET['emydate'] != NULL)){
		
			//sanatize user input to make safe for browser
			$p_smydate = htmlspecialchars($_GET['smydate']);
			$p_emydate = htmlspecialchars($_GET['emydate']);
		
			//make sure you cover the entire day
			$p_smydate = $p_smydate.' 00:00:00';
			$p_emydate = $p_emydate.' 23:59:00';
			$query_date = ' sample.start_samp_date_time BETWEEN (?) AND (?)'; //still going to pull this date time from the regular table
			$check_date = 'true';
		}
			
		if(($_GET['field'] != '0') && isset($_GET['query'])){
			$p_field = htmlspecialchars($_GET['field']);
			$p_query_basis = htmlspecialchars($_GET['query']);
			$thing_id = '';
			//check whitelist for p_field
			//$p_field_check = whiteList($p_field, 'column');
			//if($p_field_check == 'true'){
				if($p_field == 'sampler_name'){
					$query_field = " sample_sampler.$p_field = (?)";
				}
				elseif (preg_match("/thing(\d+)/i",$p_field,$matches)) {
					//$query_field = " store_user_things.$p_field = (?)";
					$query_field = " thing_storing.thing_id = (?) AND thing_storing.thing_value = (?)";
					$thing_id = $matches[1];
				}
				else{
					$query_field = " sample.$p_field = (?)";
				}
				$check_field = 'true';
			//}
		}
		//if(isset($_GET['column_names'])){$field_names = check_box_results($_GET['column_names']);}//removed
		if(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_dna'){
			$field_names = 'sample.sample_name,sample.d_conc,sample.sample_sort';
		}
		elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_storage'){
			$field_names = 'sample.sample_name,sample.sample_sort';
		}
		elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_things'){
			$field_names = 'sample.sample_name,sample.sample_sort';
		}
		else{
			//$field_names = "*";
			$general_field_names = 'sample.sample_name,sample.sample_sort,sample.barcode,sample.project_name,sample.location_name,sample.relt_loc_name,sample.media_type,sample.collector_name,sample.sample_type,sample.start_samp_date_time,sample.end_samp_date_time,sample.total_samp_time,sample.entered_by,sample.updated_by,sample.time_stamp';
			$dna_field_names = 'sample.d_extraction_date,sample.dna_extract_kit_name,sample.d_conc,sample.d_volume,sample.d_conc_instrument,sample.d_volume_quant,storage_info.dna_extr,sample.dExtrName,storage_info.DNA_sample_exists,storage_info.orig_sample_exists';
			$rna_field_names = 'sample.r_extraction_date,sample.rna_extract_kit_name,sample.r_conc,sample.r_volume,sample.r_conc_instrument,sample.r_volume_quant,storage_info.rna_extr,sample.rExtrName,storage_info.RNA_sample_exists,storage_info.orig_sample_exists';
			$analysis_field_names = 'sample.analysis_name';
			//$things_field_names = '';
			$note_field_names = 'sample.notes';
			
			$field_names = $general_field_names.','.$dna_field_names.','.$rna_field_names.','.$analysis_field_names.','.$note_field_names;


		}

		////////////////////////////////////////////////////////////////////////////////////////////////
		//Check what type of query you are doing
		////////////////////////////////////////////////////////////////////////////////////////////////		
	
		if(isset($_GET['db_content']) && $_GET['db_content'] == 'read_sub'){
			$query_main = "SELECT DISTINCT sample.sample_name,sample.sample_num,sample.sample_sort,sample.seq_id,read_submission.subm_id,read_submission.subm_db,read_submission.subm_date,read_submission.submitter,read_submission.type_exp FROM sample LEFT JOIN read_submission ON read_submission.sample_name = sample.sample_name LEFT JOIN thing_storing ON thing_storing.sample_name = sample.sample_name WHERE ";
		}
		elseif(isset($_GET['db_content']) && ($_GET['db_content'] == 'view_read_sub' || $_GET['db_content'] == 'update_read_sub')){
			$query_main = "SELECT DISTINCT sample.sample_name,sample.sample_sort,sample.seq_id,read_submission.subm_id,read_submission.subm_db,read_submission.subm_date,read_submission.submitter,read_submission.type_exp FROM sample RIGHT JOIN read_submission ON read_submission.sample_name = sample.sample_name LEFT JOIN thing_storing ON thing_storing.sample_name = sample.sample_name WHERE ";
		}
		else{
			$query_main = "SELECT $field_names FROM sample JOIN storage_info ON storage_info.sample_name = sample.sample_name LEFT JOIN thing_storing ON thing_storing.sample_name = sample.sample_name WHERE ";
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////
		//Build Query
		////////////////////////////////////////////////////////////////////////////////////////////////
		$query = "";
		$query_add = "";
		
		if($check_field == 'true' && $check_date == 'false'){//only query field populated
			$query = $query_main.$query_field;
			//$query_add = $query_field;
			$stmt = $dbc->prepare($query);
			if (preg_match("/thing/i",$p_field)) {
				$stmt -> bind_param('is', $thing_id, $p_query_basis);
			}
			else{
				$stmt -> bind_param('s', $p_query_basis);
			}
		}
		elseif ($check_field == 'false' && $check_date == 'true') {//only date is populated
			$query = $query_main.$query_date;
			//$query_add = $query_date;
			$stmt = $dbc->prepare($query);
			$stmt -> bind_param('ss',$p_smydate , $p_emydate);
		}
		elseif ($check_field == 'true' && $check_date == 'true') {//date and query fields are populated
			$query = $query_main.$query_field.' AND '.$query_date;
			$query_add = $query_field.' AND '.$query_date;
			$stmt = $dbc->prepare($query);
			
			if (preg_match("/thing/i",$p_field)) {
				$stmt -> bind_param('isss',$thing_id,$p_query_basis, $p_smydate , $p_emydate);
			}
			else{
				$stmt -> bind_param('sss', $p_query_basis, $p_smydate , $p_emydate);
			}

		}
		else{
			echo '<script>Alert.render("ERROR: No entries found. Please check fields");</script>';
			echo '<input action="action" class="btn btn-success" type="button" value="Go Back" onclick="history.go(-1);" />';
		}
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////
		//Build Table
		////////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($_GET['db_view']) && ($_GET['db_view'] == 'xls')){
			build_xls_output_table($stmt);
		}else{
			if(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_dna'){
				build_bulk_dna_table($stmt,$root);
			}
			elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_storage'){
				build_bulk_storage_update_table($stmt,$root);
			}
			elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'read_sub'){
				build_bulk_read_sub_id_table($stmt,$root);
			}
			elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'update_read_sub'){
				build_bulk_read_sub_id_update_table($stmt,$root);
			}
			elseif(isset($_GET['db_content']) && $_GET['db_content'] == 'bulk_things'){
				build_bulk_thing_update_table($stmt,$root,$selected_thing_id);
			}
			else{
				if($stmt){
					build_query_results_table($stmt,'display',$dbc);
				}
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	//Disaply Data dumps
	////////////////////////////////////////////////////////////////////////////////////////////////
	if($submit == 'other'){
		//project
		if($_GET['db_content']== 'project_all'){
			
			echo "<div class=\"page-header\">
			<h3>View All Project Info</h3>	
			</div>";
			
			$stmt = $dbc->prepare("SELECT * FROM project_name");
			basic_build_table($stmt,'display',$root);
		}
		
		// samplers
		if($_GET['db_content']=='sampler_all'){
			
			echo "<div class=\"page-header\">
			<h3>View All Sampler Info</h3>	
			</div>";
			
			$stmt = $dbc->prepare("SELECT * FROM sampler");
	    	basic_build_table($stmt,'display',$root);
		}
		
		//sensors
		if($_GET['db_content']=='partCt_all'){
			
			echo "<div class=\"page-header\">
			<h3>View All Sensor Info</h3>	
			</div>";
			
			$stmt = $dbc->prepare("SELECT * FROM particle_counter");
			basic_build_table($stmt,'display',$root);
		}
		
		//location
		if($_GET['db_content']=='location_all'){
			
			echo "<div class=\"page-header\">
			<h3>View All Location Info</h3>	
			</div>";
			
			$stmt = $dbc->prepare("SELECT * FROM location");
			basic_build_table($stmt,'display',$root);
		}
		
		//media type
		if($_GET['db_content']=='media_all'){
	
			echo "<div class=\"page-header\">
			<h3>View All Media Type Info</h3>	
			</div>";
			
			$stmt = $dbc->prepare("SELECT * FROM media_type");
			basic_build_table($stmt,'display',$root);
		}
	}

}
?>

</body>
</html>
