<?php
/*functions*/
function send_user_email($email_address,$role,$login_allow){
		$sent_success = 'false';
		
		$allow_deny = '';
		if($login_allow == 1){
			$allow_deny = 'active';
		}else{
			$allow_deny = 'inactive';
		}
		
		$new_role = 'User';
		if($role == 'Y'){
			$new_role = 'Admin';
		}
		
		$subject = "MetaLIMS User Update";
        $message = "Your role has been updated to: <b>{$new_role}</b>.<br/>Your login permission has been updated to: <b>{$allow_deny}</b><br/><br/><br/>";
        $message = wordwrap($message, 70, "\r\n");
        $headers = 'From: no-reply@metalims' . "\r\n" .
                   'MIME-Version: 1.0'."\r\n".
                   'Content-Type: text/html; charset=UTF-8'."\r\n";

		
    	if(mail($email_address, $subject, $message, $headers)){
    		$sent_success = 'true';
    	}
	
		return $sent_success;
}

function send_user_registration_email($email_address,$role,$login_allow){
		$sent_success = 'false';
		
		$allow_deny = '';
		if($login_allow == 1){
			$allow_deny = 'active';
		}else{
			$allow_deny = 'inactive';
		}
		
		$new_role = 'User';
		if($role == 'Y'){
			$new_role = 'Admin';
		}
		
		$subject = "MetaLIMS User Registration";
        $message = "You have been registered by an admin to use MetaLIMS. Your role has been set to: <b>{$new_role}</b>.<br/>Your login permission has been set to: <b>{$allow_deny}</b><br/>Please use 'Forgot Password?' feature on login screen to reset your password<br/><br/>";
        $message = wordwrap($message, 70, "\r\n");
        $headers = 'From: no-reply@metalims' . "\r\n" .
                   'MIME-Version: 1.0'."\r\n".
                   'Content-Type: text/html; charset=UTF-8'."\r\n";

		
    	if(mail($email_address, $subject, $message, $headers)){
    		$sent_success = 'true';
    	}
	
		return $sent_success;
}
	
	
	
	
function send_admin_email($username,$role,$login_allow,$dbc,$type_of_email){
		$sent_success = 'false';
		
		//get all admin's email
		$email_address_list = '';
		$admin = 'Y';
		$admin_visible = 1;
		$counter = 0;
		$query = "SELECT user_id FROM users WHERE admin = ? and visible = ?";
		$stmt = $dbc -> prepare($query);
		if(!$stmt){;
			die('prepare() failed: ' . htmlspecialchars($stmt->error));
			//throw new Exception("ERROR: Email to admins(s) was not sent<br>");
		}
							
		$stmt -> bind_param('si',$admin,$admin_visible);
		if(!$stmt -> execute()){
			die('execute() failed: ' . htmlspecialchars($stmt->error));
			//return $sent_success;
		}else{
			$stmt->bind_result($admin_email);
			while($stmt->fetch()) {
				if($counter == 0){
					$email_address_list = $admin_email;
				}else{
					$email_address_list = $email_address_list.', '.$admin_email;
				}
				 $counter++;
			}		
		}
		$stmt->close();
		
		
		//echo $email_address_list;
		
		
		$allow_deny = '';
		if($login_allow == 1){
			$allow_deny = 'active';
		}else{
			$allow_deny = 'inactive';
		}
		
		$new_role = 'User';
		if($role == 'Y'){
			$new_role = 'Admin';
		}
		
		if($type_of_email == 'update_user'){
			$subject = "Admin Notice: MetaLIMS User Update";
       		$message = "User <b>{$username}</b> role has been updated to: <b>{$new_role}</b>.<br/>Login permission has been updated to: <b>{$allow_deny}</b><br/><br/><br/>";
        	$message = wordwrap($message, 70, "\r\n");
		}
		if($type_of_email == 'new_user'){
			$subject = "Admin Notice: MetaLIMS New User Registered";
       		$message = "User <b>{$username}</b> has registered. Please update login permission and role (if needed).<br/><br/><br/>";
        	$message = wordwrap($message, 70, "\r\n");
		}
		if($type_of_email == 'project_approval'){
			$subject = "Admin Notice: MetaLIMS Project Approval Request";
       		$message = "User <b>{$username}</b> has requested approval for the following project:.<br/><br/><br/>";
        	$message = wordwrap($message, 70, "\r\n");
		}
		
		
        $headers = 'From: no-reply@metalims' . "\r\n" .
            	   'MIME-Version: 1.0'."\r\n".
                   'Content-Type: text/html; charset=UTF-8'."\r\n";

		
    	if(mail($email_address_list, $subject, $message, $headers)){
    		$sent_success = 'true';
    	}
	
		return $sent_success;

}
?>