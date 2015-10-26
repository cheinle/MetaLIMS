<?php 
	function check_exclude_list($value,$type){ //type = 'add', 'update','delete' 
		include ('../database_connection.php');
		
		$list = array(
		'sample',
		'daily_data2',
		'daily_data2_particle_counter',
		'freezer_drawer',
		'number_of_seq_submissions',
		'pooled_sample_lookup',
		'project_name', //no update for project name due to key constraints
		'read_submission',
		'sample_read_submission',
		'sample_sampler',
		'sample_sequencing2',
		'sample_type', //no update for sample type due to key constraints
		'sequencing2',
		'storage_info'
         );	
		 
		 if($type == 'add'){
		 	array_push($list,'users');
			array_push($list,'freezer');
			array_push($list,'drawer');
		 }
		 if (in_array($value, $list)) {
    		return true;
		}
		 else{
		 	return false;
		 }
		 
		 
		
	}
?>