<?php
require_once "../core/initialize.php";

	$login = $_GET['login'];
	$pass  = $_GET['pass'];
    $email = $_GET['email'];    
	$location_id = $_GET['location_id'];	
	$error_message = "";


	function processRegistration($out_login, $out_pass, $out_email, $out_location_id)
	{
		
		$login_free = User::is_login_free($out_login);      
        if (!$login_free){
             return -1;
        }
		$email_free = User::is_email_free($out_email);
        if (!$email_free){
            return -2;
        }
        
		if (strlen($out_pass)<5)
			{
				$error_message = "strlen failed";
				return -3;
			}			
		
		$user = new User;
        $user->login = $out_login;
        $user->password = md5($out_pass);
        $user->email = $out_email;
        $user->date_of_join = date($dateformat);
        $user->location_id = $out_location_id;
        $user->create();
    
		return 0;
	}


	if (isset($login) && isset($pass) && isset($email) && isset($location_id))
	{

		$val = processRegistration($login, $pass, $email, $location_id);
		if ($val==0)		
		echo "OK, new user registered !!!";
		else
		echo "FAILED: login=$login  pass=$pass  email=$email ".$val;		
		
		unset($_GET['login']);
		unset($_GET['pass']);	
		unset($_GET['email']);	
	}	

?>