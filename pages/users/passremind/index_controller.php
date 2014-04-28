<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    
    //Resetting the password
    if(isset($_POST['reset_pass'])){
        
        if(isset($_POST['a']) && isset($_POST['b'])){
        $user = User::find_by_id($_GET['a']);
        if ($user->pass_remind == $_GET['b']){
            $user->password = md5($_POST['reg_password']);
            $user->pass_remind = 0;
            $user->save();
            
            $error_code = 0;
            redirect_to("/");    
        }
        else{
            $error_code = -1;
            //LOG HERE that someone tries to hack
            redirect_to("/");
        }
    }
    }
?>