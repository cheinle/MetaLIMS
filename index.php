<?php if(!isset($_SESSION)) { session_start(); }?>
<link rel="shortcut icon" href="/favicon.ico" type="image/icon"> <link rel="icon" href="/favicon.ico" type="image/icon">
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

//if user is not logged in, do not let him access any of the pages/directories
if(!isset($_SESSION['username'])){  
	$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
	header("Location: http://".$url);
	exit();
}
else{//if user is logged in, check to see how long he has been idle. Log user out after x amt of time
	$root = $_SESSION['link_root'];
	$path = $_SESSION['include_path']; 
	

	
	include($path.'database_connection.php');
	include($path.'path.php');
	
	include($path.'config/css.php');
	include($path.'config/js.php');
	
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
	        //Kill their session and log them out
	        session_unset();
	        session_destroy();
			$url = $_SERVER["HTTP_HOST"].$logout_path."login.php"; 
			header("Location: http://".$url);
			exit();
	    }
	    
	}
	 
	//Assign the current timestamp as the user's
	//latest activity
	$_SESSION['last_action'] = time();
}




//get admin email address
$admin_email = '';
$admin_Y = 'Y';
if($stmt1 = $dbc->prepare("SELECT user_id FROM users WHERE admin = ?")){
	$stmt1 -> bind_param('s', $admin_Y );
		if ($stmt1->execute()){
			$stmt1->bind_result($admin_username);
			if ($stmt1->fetch()){
				$admin_email = $admin_username;
			}
		}
}
$stmt1->close();


//if user is an admin, show admin options
$admin_user = 'N';//no by default
if($stmt = $dbc->prepare("SELECT admin FROM users WHERE user_id = ?")){
	$stmt -> bind_param('s', $_SESSION['username']);
	if ($stmt->execute()){
		$stmt->bind_result($admin_check);
		if ($stmt->fetch()){
			if($admin_check == 'Y'){
				$admin_user = 'Y';
			}
		}	
	}	
}
$stmt->close();		
?>

<nav class="navbar navbar-default" role="navigation">
	  <div class="container-fluid">
		
			<ul class="nav navbar-nav">
				<li ><a href="<?php echo $root?>home_page.php"><span class="glyphicon glyphicon-home"></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Sample Management<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li ><a href="<?php echo $root;?>sample_insert/form_insert_jquery.php">Insert Sample</a></li>
						<li ><a href="<?php echo $root;?>sample_update/sample_update_lookup_jquery.php">Update Sample</a></li>
						<li><a href="<?php echo $root;?>pooling/pool_samples.php">Create Sample Pools (For DNA Extr)</a></li>
					</ul>
				</li>
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
						<li><a href="<?php echo $root;?>update_tables/update_sampler.php">Add Sampler</a></li>
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
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Query Info<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li ><a href="<?php echo $root;?>query_samples/query_select_mod.php">Query Sample Info</a></li>
						<li><a href="<?php echo $root;?>pooling/query_select_pooled.php">Query Pooled Sample Info</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Sequencing Submission Info<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>sequencing/seq_submission_UDF_check.php">Bulk Sequencing Submission</a></li>
						<li><a href="<?php echo $root;?>sequencing/query_past_seq_submissions/query_select_seq_subbed.php">View Past Submission</a></li>
						
					</ul>
				</li>
				<li ><a href="<?php echo $root;?>labels/label_prep.php">Label Prep</a></li>
				</li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Sensor Data<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $root;?>daily_data/query_select_daily_data.php">Query Sensor Data</a></li>
						<li><a href="<?php echo $root;?>daily_data/add_daily_data.php">Add Sensor Data</a></li>
						<li><a href="<?php echo $root;?>daily_data/update_daily_data1.php">Update Sensor Data</a></li>
					</ul>
				</li>
				<li ><a href="<?php echo $root;?>FAQ.php">FAQ</a></li>
				
				<?php
				if($admin_user == 'Y'){
				echo '
				<ul class="nav navbar-nav ">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle"  data-toggle="dropdown">Admin Tools<b class="caret"></b></a>
					<ul class="dropdown-menu multi-level" role="menu">
						<li><a href="'.$root.'admin_tools/dynamically_add_user_fields/add_a_user_thing.php">Create Sample Fields</a></li>
						<li class="dropdown-submenu">
		                <a tabindex="-1" href="#">Manipulate Dropdown Options</a>
		                <ul class="dropdown-menu">
							<li><a tabindex="-1" href="'.$root.'admin_tools/add.php">Add Dropdown Options</a></li>
							<li><a tabindex="-1" href="'.$root.'admin_tools/update.php">Update Dropdown Options</a></li>
							<li><a tabindex="-1" href="'.$root.'admin_tools/delete.php">Delete Dropdown Options</a></li>
							
							<li><a tabindex="-1" href="'.$root.'admin_tools/update_proj_name.php">Add Project Name</a></li>
							<li><a tabindex="-1" href="'.$root.'admin_tools/add_sample_type.php">Add Sample Type</a></li>
							<li><a tabindex="-1" href="'.$root.'admin_tools/update_freezer_drawers.php">Add Freezer/Drawer</a></li>
		                </ul>
		                </li>
						
		              	<li class="dropdown-submenu">
		                <a tabindex="-1" href="#">User Info</a>
		                <ul class="dropdown-menu">
		                  <li><a tabindex="-1" href="'.$root.'admin_tools/add_users.php">Add Users</a></li>
						  <li><a tabindex="-1" href="'.$root.'admin_tools/update_user_login_info.php">Update User Login</a></li>
		                </ul>
		              </li>
					</ul>
				</li>';}
				echo '<li ><a href="'.$root.'logout.php"><span class="glyphicon glyphicon-log-out"></a></li>
				</ul> ';
				
				?>
				
			</ul>
	</div>			
	</nav><!--End nav Main Nav-->
	
	

		
	