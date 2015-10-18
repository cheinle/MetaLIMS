<link rel="shortcut icon" href="/favicon.ico" type="image/icon"> <link rel="icon" href="/favicon.ico" type="image/icon">


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config/path.php');

//if user is not logged in, do not let him access any of the pages/directories
session_start(); 
if(!isset($_SESSION['username'])){  
	header('Location:'.$root.'login.php');
	exit();
}
else{//if user is logged in, check to see how long he has been idle. Log user out after x amt of time
	
	//Expire the session if user is inactive for 45
	//minutes or more.
	$expireAfter = 45;
	 
	//Check to see if our "last action" session
	//variable has been set.
	if(isset($_SESSION['last_action'])){
	    
	    //Figure out how many seconds have passed
	    //since the user was last active.
	    $secondsInactive = time() - $_SESSION['last_action'];
	    
	    //Convert our minutes into seconds.
	    $expireAfterSeconds = $expireAfter * 60;
	    
	    //Check to see if they have been inactive for too long.
	    if($secondsInactive >= $expireAfterSeconds){
	        //User has been inactive for too long.
	        //Kill their session.
	        session_unset();
	        session_destroy();
	    }
	    
	}
	 
	//Assign the current timestamp as the user's
	//latest activity
	$_SESSION['last_action'] = time();
	
	//unset session vars for bulk DNA update if you are not on one of the specified pages
	$page_name = basename($_SERVER['SCRIPT_NAME']);
	if(($page_name != 'dna_bulk_update.php') && ($page_name != 'query_results_mod.php')){
		include('functions/unset_session_vars.php');
		unset_session_vars('bulk_dna_update');
	}
	
	//unless you are the admin, don't give access to this page
	if(($page_name == 'update_proj_name.php') && ($_SESSION['username'] != 'cheinle@ntu.edu.sg')){
		header('Location: '.$root.'home.php');
		exit();
	}
}

?>

<?php include('config/css.php'); ?>
<?php include('config/js.php'); ?>

<nav class="navbar navbar-default" role="navigation">
	  <div class="container-fluid">
		
			<ul class="nav navbar-nav">
				<li ><a href="<?php echo $root;?>home_page.php"><span class="glyphicon glyphicon-home"></a></li>
				<li ><a href="<?php echo $root;?>form_insert.php">Insert Sample</a></li>
				<li ><a href="<?php echo $root;?>sample_update_lookup.php">Update Sample</a></li>
			</ul>
			<ul class="nav navbar-nav ">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Storage Info<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>update_storage/update_storage_info.php">Query/Update Storage Info</a></li>
					</ul>
				</li>
			</ul>	
			<ul class="nav navbar-nav ">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Update Sample Fields<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>update_tables/update_air_sampler.php">Add Air Sampler</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_analysis.php">Add Analysis</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_dna_extr.php">Add DNA Extraction Kit</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_media_type.php">Add Media Type</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_samp_loc.php">Add Sampling Location</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_samp_rel_loc.php">Add Sampling Relative Location</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_part_sens.php">Add New Sensor</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_proj_name_for_approval.php">Add Project Name For Approval</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_rna_extr.php">Add RNA Extraction Kit</a></li>
					</ul>
				</li>
			</ul>
				
			<ul class="nav navbar-nav">
				<li ><a href="<?php echo $root;?>query_select_mod.php">Query Sample Info</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Sample Pooling<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>pool_samples.php">Create Sample Pools</a></li>
						<li><a href="<?php echo $root;?>query_select_pooled.php">View Pooled Sample Info</a></li>
						
					</ul>
				</li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Sequencing Submission Info<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>seq_submission_UDF_check.php">Bulk Sequencing Submission</a></li>
						<li><a href="<?php echo $root;?>query_select_seq_subbed.php">View Past Submission</a></li>
						
					</ul>
				</li>
				<li ><a href="<?php echo $root;?>label_prep.php">Label Prep</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Files<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>browse_files/">Browse Files</a></li>
					</ul>
				</li>
				</li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Daily Data<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>query_select_daily_data.php">Query Daily Data</a></li>
						<li><a href="<?php echo $root;?>update_tables/add_daily_data.php">Add Daily Data</a></li>
						<li><a href="<?php echo $root;?>update_tables/update_daily_data1.php">Update Daily Data</a></li>
					</ul>
				</li>
				<li ><a href="<?php echo $root;?>FAQ.php">FAQ</a></li>
				
				<?php if($_SESSION['username'] == $admin_user){ echo '
				<ul class="nav navbar-nav ">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Admin Tools<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="'.$root.'admin_tools/update_freezer_drawers.php">Add Freezer/Drawer</a></li>
						<li><a href="'.$root.'admin_tools/add_sample_type.php">Add Sample Type</a></li>
						<li><a href="'.$root.'admin_tools/add_quant_instrument.php">Add Instrument Used To Quantify DNA/RNA</a></li>
						<li><a href="'.$root.'admin_tools/update_proj_name.php">Add Project Name</a></li>
						<li><a href="'.$root.'admin_tools/add_delete_users.php">Add Users</a></li>
						<li><a href="'.$root.'admin_tools/add.php"></a>Add Entries</li>
						<li><a href="'.$root.'admin_tools/update.php"></a>Update Entries</li>
						<li><a href="'.$root.'admin_tools/delete.php">Delete Entries</a></li>
					</ul>
				</li>
				</ul> 
				';}?>
				<li ><a href="<?php echo $root;?>logout.php"><span class="glyphicon glyphicon-log-out"></a></li>
			</ul>
	</div>			
	</nav><!--End nav Main Nav-->
	
	

		
	