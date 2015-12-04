<?php

//this is a whitelist to check that all table/field names are safe to use
function whiteList($name_check,$type){// Example string to test against
	
	//whitelist table mames from db
	if($type == 'table'){
		$white_list = array('air_sampler',
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
							'isolate_collection_temp',
							'isolates',
							'isolate_storing_method',
							'isolate_location_type',
							'sequencing_method',
							'read_length',
							'quantitation_method',
							'container_type',
							'application',
							'type_seq_sample'
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
							'analysis_pipeline',
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
							//isolate_collection_temp
							'temp',
							//isolates
							'iso_coll_temp',
							'iso_store_method',
							'iso_date',
							'closest_hit',
							'seq_sang',
							'send_pac_bio',
							//isolate_storing_method
							'storing_method',
							//isolate_location_type
							'loc_type',
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
							'sample_type'
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