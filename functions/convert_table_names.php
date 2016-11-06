<?php

function convert_table_names($table_name){
	//convert table names output in admin update.php, add.php, and delete.php

	if($table_name == 'relt_location'){
		$table_name = 'relative_location';
	}
	if($table_name == 'particle_counter'){
		$table_name = 'sensor_name';
	
	}
	if($table_name == 'analyis'){
		$table_name = 'analysis_pipeline';
	
	}
	if($table_name == 'dna_extraction'){
		$table_name = 'DNA_extraction_kit';
	}
	if($table_name == 'quant_instrument'){
		$table_name = 'Instrument/Kit Used to Measure DNA/RNA Concentration';
	}
	if($table_name == 'quantitation_method'){
		$table_name = 'false';//not using for default clarity lims form
	}
	if($table_name == 'read_subm_database'){
		$table_name = 'Read Submission-Database';
	}
	if($table_name == 'read_subm_exp_types'){
		$table_name = 'Read Submission-Type Of Exeperiment';
	}
	if($table_name == 'records'){
		$table_name = 'Record Source For Sensor Measurement';
	}
	if($table_name == 'rna_extraction'){
		$table_name = 'RNA_extraction_kit';
	}
	if($table_name == 'type_seq_sample'){
		$table_name = 'Sequencing Sample Type';
	}
	
	
	return $table_name;		
	
}
					

?>
