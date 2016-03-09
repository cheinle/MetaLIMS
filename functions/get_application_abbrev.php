<?php

function get_application_abbrev($application,$type){
	
	if($type == 'abbrev'){
		if($application == 'Amplicon sequencing'){
			RETURN 'A';
		}
		else if($application == 'ChIP-sequencing'){
			RETURN 'C';
		}
		else if($application == 'Exome sequencing'){
			RETURN 'E';
		}
		else if($application == 'Metagenome PacBio'){
			RETURN 'MP';
		}
		else if($application == 'Metagenome sequencing'){
			RETURN 'M';
		}
		else if($application == 'Metatranscriptome sequencing'){
			RETURN 'MT';
		}
		else if($application == 'Small RNA sequencing'){
			RETURN 'S';
		}
		else if($application == 'Transcriptome Sequencing'){
			RETURN 'T';
		}
		else if($application == 'Whole Genome Sequencing'){
			RETURN 'G';
		}
		else if($application == 'PacBio Isolates'){
			RETURN 'P';
		}
		else{
			RETURN "ERROR";
		}
	}
	else if($type == 'query'){
		if($application == 'Amplicon sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET A = ? WHERE sample_name = ?';
		}
		else if($application == 'ChIP-sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET C = ? WHERE sample_name = ?';
		}
		else if($application == 'Exome sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET E = ? WHERE sample_name = ?';
		}
		else if($application == 'Metagenome PacBio'){
			RETURN 'UPDATE number_of_seq_submissions SET MP = ? WHERE sample_name = ?';
		}
		else if($application == 'Metagenome sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET M = ? WHERE sample_name = ?';
		}
		else if($application == 'Metatranscriptome sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET MT = ? WHERE sample_name = ?';
		}
		else if($application == 'Small RNA sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET S = ? WHERE sample_name = ?';
		}
		else if($application == 'Transcriptome Sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET T = ? WHERE sample_name = ?';
		}
		else if($application == 'Whole Genome Sequencing'){
			RETURN 'UPDATE number_of_seq_submissions SET G = ? WHERE sample_name = ?';
		}
		else if($application == 'PacBio Isolates'){
			RETURN 'UPDATE number_of_seq_submissions SET P = ? WHERE sample_name = ?';
		}
		else{
			RETURN "ERROR";
		}
	}
	else{
		RETURN "ERROR";
	}
	
	
	

}
?>