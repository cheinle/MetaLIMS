<?php
 	if(!isset($_SESSION)) { session_start(); }

	$path = $_SESSION['include_path'];
	$root = $_SESSION['link_root'];
 	include($path.'database_connection.php');
	include($path.'functions/dropDown.php');
	$type= $_GET['type'];
	$submitted = 'false';
	echo '<div class="container-fluid">';
	echo '<div class="row">';

	if($type == 'drawer'){
		echo '<LEGEND><b>Add New Drawer:</b></LEGEND>';
  		echo '<div class="col-xs-6">';
		echo '<p>';
		echo '<label class="textbox-label">New Drawer Name:</label>';
	 	echo '<input type="text" name="newDrawer" class="fields" placeholder="Name" value="">';
		echo '</p>';
	
		/*echo '<p>';
		echo '<label class="textbox-label">Old Drawer Name:</label>';
		dropDown('drawer', 'drawer', 'drawer_id','drawer_id',$submitted);
		echo '</p>';
		 */
	}
	
	if($type == 'freezer'){
		echo '<LEGEND><b>Add New Freezer:</b></LEGEND>';
  		echo '<div class="col-xs-6">';
		echo '<p>';
		echo '<label class="textbox-label">New Freezer Name:</label>';
		echo '<input type="text" name="freezer" class="fields" placeholder="Name" value="">';
		echo '</p>';
	
	
		/*echo '<p>';
		echo '<label class="textbox-label">Old Freezer Name:</label>';
		dropDown('freezer', 'freezer', 'freezer_id','freezer_id',$submitted);	
		echo '</p>';
		 */
	}
	
	if($type == 'freezer_drawer'){
		echo '<LEGEND><b>Add Drawer To Freezer:</b></LEGEND>';
	  	echo '<div class="col-xs-6">';

		echo '<label class="textbox-label">Pick A Freezer:</label>';
		echo '<p>';
		dropDown('freezer', 'freezer', 'freezer_id','freezer_id',$submitted,$root);	
		echo '</p>';
	  	
	  	echo '<label class="textbox-label">Pick A Drawer:</label>';
		echo '<p>';
	  	dropDown('drawer', 'drawer', 'drawer_id','drawer_id',$submitted,$root);
	  	echo '</p>';
	}
  	
  	echo '</div></div>';
	

?>

