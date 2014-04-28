<?php
    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    if(isset($_GET['user_id']) && isset($_GET['h'])){
        $user = User::find_by_id($_GET['user_id']);
        if (($user->email_activated == $_GET['h']) && ($_GET['h'] != 0)){
            $user->activate_email();
            if(isset($_GET['r'])){
                if(isset($vk_user)){
                    unset($vk_user);
                }
                $_SESSION['confirmation_status'] = 2;
                redirect_to("/");
            }
            else{
                redirect_to("/users/settings/?email_activated=1");    
            }
                
        }
        else{
            $error_code = 0;
            //LOG HERE that someone tries to hack
            redirect_to("/");
        }
    }    
