<?php

//unset specific session variables
function unset_session_vars($page_type){
	if($page_type == 'bulk_dna_update'){
		unset(
			$_SESSION['submitted'],
			$_SESSION['sample_array'],
			$_SESSION['dExtKit'],
			$_SESSION['d_extr_date'],
			$_SESSION['dVol'],
			$_SESSION['dInstru'],
			$_SESSION['dVol_quant'],
			$_SESSION['dStore_temp'],
			$_SESSION['dStore_name'],
			$_SESSION['DNA_sample_exist'],
			$_SESSION['dExtrName'],
			$_SESSION['orig_sample_exist']
		);
	}
	if($page_type == 'bulk_storage_update'){
		unset(
			$_SESSION['submitted'],
			$_SESSION['sample_array'],
			$_SESSION['sample_type'],
			$_SESSION['Store_temp'],
			$_SESSION['Store_name']
		);
	}
	if($page_type == 'bulk_seqSub_update'){
		unset(
	
			$_SESSION['submitted'],
			$_SESSION['sample_array'],
			$_SESSION['sample_type'],
			$_SESSION['container_type'],		 	
		    $_SESSION['method'],
			$_SESSION['read_length'],
			$_SESSION['quant_method'],
			$_SESSION['application'],
			$_SESSION['libPK'],
			$_SESSION['submittedBy'],
			$_SESSION['dtSub'],
			$_SESSION['seq_pool'],
			$_SESSION['amplicon_type'],
			$_SESSION['seqOther'],
			$_SESSION['primerL'],
			$_SESSION['primerR']
		);
	}
	if($page_type == 'storage_info'){
		unset(
	
			$_SESSION['oStore_name'],
			$_SESSION['dStore_name'],
			$_SESSION['rStore_name']
		
		);
	}
	if($page_type == 'bulk_read_insert'){
		unset(
	
			$_SESSION['submitted'],
			$_SESSION['sample_array'],
			$_SESSION['subm_date'],
			$_SESSION['submitter'],
			$_SESSION['subm_db'],
			$_SESSION['type_of_experiment']
		
		);
	}
	
	
}

?>
