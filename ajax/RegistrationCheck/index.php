<?php
//Description: registration check
//this is ajax file
//created by Mus in Jan 2010

	
	
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
     
    $error_message = "";
    $login = $_GET['login'];
    $pass1  = $_GET['pass1'];
    $email = $_GET['email'];
	function RegistrationCheck($out_login, $out_pass1,  $out_email)
	{
		$result = '';	
	
        
        if ($out_login != "") {
            if (User::is_login_free($out_login)){
                 $result = '0';
            }
            else $result = '1'; 
        }
        else $result = '1'; 
        
        if ($out_email != "") {
            if (User::is_email_free($out_email)){
                 $result .= '0';
            }
            else $result .= '1'; 
        }
        else  $result .='1';
        return $result;
	}


	{
		$val = RegistrationCheck($login, $pass1, $email);

		echo $val;		
	}	

?>