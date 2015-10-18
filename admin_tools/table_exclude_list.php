<?php 
	function check_exclude_list($value){ 
		include ('../database_connection.php');
		$list = array(
		'sample',
		'daily_data2',
		'daily_data2_particle_counter',
		'drawer','freezer',
		'freezer_drawer',
		'number_of_submissions',
		'pooled_sample_lookup',
		'project_name',
		'sample_read_submission',
		'sample_sampler',
		'sample_sequencing2',
		'storage_info',
		'users'
         );	
		 if (in_array($value, $list)) {
    		return true;
		}
		 else{
		 	return false;
		 }
		 
		 
		
	}
?>