<?php

//this is a funciton to check if collector names are valid
function check_collector_names($arr,$true){
					$cat_name='';
					$cat_count = 0;
					
					$array = array_filter($arr);
					if(empty($array) && $true == 'false'){//ok to be empty on update
						return array(
    						'boolean'    => 'true',
    						'cat_name' => $cat_name
  						);
					}
					elseif (empty($array) && $true == 'true') {
						return array(
							'boolean'   => 'false',
    						'cat_name' => $cat_name
    					);
					}
					else{
						$explode = array();
						#sort($array);
						foreach($array as $collector){
							$p_collector = htmlspecialchars($collector);
							
							//check if valid name (no numbers or special characters, allow parenthasis)
							if (!preg_match("#^[a-zA-Z, ()]+$#", $p_collector)) {
   								 return array(
    								'boolean'    => 'false',
    								'cat_name' => $cat_name
  									);
							} 
							else {
								//check if name is already a string of sorted names
								//if yes, split array and push all names into a new array to be sorted
								if(preg_match("#[,]+#", $p_collector)){
									$explode = explode(",",$p_collector);
									
								}
								else{
									array_push($explode, $p_collector);
								
								}
							}
						}
						sort($explode);
						//check no duplicates
						if ( count($explode) != count(array_unique($explode)) ) {
  								echo 'ERROR: Names must be unique';
								return array(
    								'boolean'    => 'false',
    								'cat_name' => $cat_name
  								);
						}
						else{
							foreach($explode as $p_collector2){
								if(strlen($p_collector2)<=0){
									return array(
	    								'boolean'    => 'false',
	    								'cat_name' => $cat_name
	  								);
								}
								else{
									if($cat_count == 0){
										$cat_name = $p_collector2;
										$cat_count++;
									}
									else{
										$cat_name .= ','.$p_collector2;
									}
			 					} 
			 				}
						}
					
						#echo "cat names:".$cat_name; 
						return array(
    							'boolean'    => 'true',
    							'cat_name' => $cat_name
  						);
					}
}

?>

<!--
	//In the class:
public function getUserInfo() {
  ...
  return array(
    'id'    => $user_id,
    'thumb' => $thumb
  );
}

//And then for your call:
$user = $login->getUserInfo();

echo $user['id'].' '.$user['thumb'];-->