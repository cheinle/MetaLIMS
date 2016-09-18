<?php

//this is a whitelist to check that all table/field names are safe to use
function whiteList($name_check,$type){// Example string to test against
	
	//whitelist table mames from db
	if($type == 'table'){
		$white_list = array('sampler',
							'analysis',
							'collectors',
							'daily_data2',
							'daily_weather',
							'dna_extraction',
							'location',
							'relt_location',
							'particle_counter',
							'pool_extractions',
							'project_name',
							'rna_extraction',
							'sample',
							'sequencing2',
							'quant_instruments',
							'sample_type',
							'freezer',
							'drawer',
							'media_type',
							'library_prep_kit',
							'sequencer_names',
							'records',
							'users',
							'read_submission',
							'read_subm_database',
							'read_subm_exp_types',
							'sample_read_submission',
							'sequencing_method',
							'read_length',
							'quantitation_method',
							'container_type',
							'application',
							'type_seq_sample',
							'create_user_things',
							'store_user_things'
							); 
		if( in_array($name_check,$white_list) ){
	    	#echo $name_check.' is whitelisted.';
			return 'true';
		}
		else{
			#echo $name_check.' is NOT whitelisted.';
			return 'false';
		}
	}
	
	//whitelist column names from sample table
	if($type == 'column'){
		$white_list = array('sample_sort',
							'sample_name',
							'location_name',
							'relt_loc_name',
							'air_sampler_name',
							'air_sampler_name',
							'part_sens_name',
							'collector_name',
							'pool_extracts_id',
							'dna_extract_kit_name',
							'rna_extract_kit_name',
							'sequencing_id',
							'analysis_name',
							'barcode',
							'start_samp_date_time',
							'end_samp_date_time',
							'total_samp_time',
							'sample_type',
							'particle_ct_csv_file',
							'project_name',
							'd_conc',
							'd_conc_instrument',
							'd_volume',
							'd_volume_quant',
							'd_extraction_date',
							'dExtrName',
							'r_conc',
							'r_conc_instrument',
							'r_volume',
							'r_volume_quant',
							'r_extraction_date',
							'dExtrName',
							'sequencing_info',
							'notes',
							'daily_data',
							'daily_weather',
							'flow_rate',
							'flow_rate_eod',
							'time_stamp',
							'sample_num',
							'entered_by',
							'orig_time_stamp',
							'updated_by',
							'sampling_height',
							'seq_vol',
							'seq_dna_conc',
							'seq_id',
							//from analysis,
							'analysis_name',
							//from particle_counter
							'part_sens_name',
							'sensor_type',
							'serial_num',
							//from location table
							'loc_name',
							'address',
							'loc_type',
							'environmental_type',
							'latitude',
							'longitude',
							'circulation_type',
							//from sample_type table
							'sample_type_name',
							'sample_type_id',
							//from daily data and daily weather table
							'date',
							'daily_date',
							//from pool_extractions
							'pool_extracts_name',
							//from dna_extraction table
							'd_kit_name',
							//from rna_extraction table
							'r_kit_name',
							//from quant_instruments
							'kit_name',
							//from sampler
							'sampler_name',
							'serial_num',
							'sampler_identifier',
							'media_type',
							//from storage_info
							'original',
							'remaining',
							'dna_extr',
							'rna_extr',
							//from freezer
							'freezer_id',
							//from drawer
							'drawer_id',
							//from library_prep_kit
							'lib_prep_kit',
							//from sequencer_names
							'seqName',
							//from project_name
							'seq_id_start',
							//from records,
							'records',
							//from daily_data2
							'temp',
							'temp_record',
							'hum',
							'hum_record',
							'co2',
							'co2_record',
							'wind',
							'wind_record',
							'rain',
							'rain_record',
							'haze',
							'haze_record',
							'record_source',
							//from users
							'first_name',
							'last_name',
							'user_id',
							//from read_submission
							'subm_id',
							'subm_date',
							'subm_db',
							'submitter',
							//from read_subm_database
							'database_name',
							//from read_subm_exp_types
							'exp_types',
							//sample_read_submission
							'read_submn_id',
							'type_exp',
							//sequencing_method
							'method',
							//read_length
							'read_length',
							//quantitation_method
							'quant_method',
							//container_type
							'container_type',
							//application
							'application',
							//type_seq_sample
							'sample_type',
							//create_user_things
							'thing_id',
							'label_name',
							'type',
							'select_values',
							'visible',
							'required',
							//store_user_things
							'thing1',
							'thing2',
							'thing3',
							'thing4',
							'thing5',
							'thing6',
							'thing7',
							'thing8',
							'thing9',
							'thing10',
							'thing11',
							'thing12',
							'thing13',
							'thing14',
							'thing15',
							'thing16',
							'thing17',
							'thing18',
							'thing19',
							'thing20',
							); 
		if( in_array($name_check,$white_list) ){
	    	#echo $name_check.' is whitelisted.';
			return 'true';
		}
		else{
			#echo $name_check.' is NOT whitelisted.';
			return 'false';
		}
	}
}

?>