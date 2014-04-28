<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    //Ajax requests
    if(isset($_GET['email'])){
        $result['email_status'] = User::is_email_free($_GET['email']);
        echo json_encode($result);
    }
    if(isset($_GET['login'])){
        $result['login_status'] = User::is_login_free($_GET['login']);
        echo json_encode($result);
    }
    
    //Register user
    if(isset($_POST['register_user'])){
        //Checking once again if the login and the email are free
        if (User::is_login_free($_POST['reg_login']) && (User::is_email_free($_POST['reg_email']))){
        $new_user = new User();
        $new_user->login    = $_POST['reg_login'];
        $new_user->password = md5($_POST['reg_password']);
        $new_user->email = $_POST['reg_email'];
        $new_user->date_of_join = date($dateformat);
        $new_user->last_visit = date($dateformat);
        $new_user->system_role = 'visiter';
        $new_user->picture_extension = '.jpg';
        $new_user->save();    
        }
        
        if($session->is_promo_validataed()){
            $promocode = Promocode::find_by_id($session->get_promo());
            $promocode->registered++;
            $promocode->save();
        }
        
        //Logging in new user
        $session->login($new_user);
         
        //SEND EMAIL HERE;
        //php_mail
        
        if ($_POST['reg_role_value'] != 'abitur'){
            //User has selected either STUDENT or ALUMNI
            //Saving relation between the user and a university
            //
            $relation = new Relation();
            $relation->user_id = $session->user_id;
            $relation->role = $_POST['reg_role_value'];
            $relation->date_of_enroll = date($dateformat);
            $relation->date_of_graduation = date($dateformat);
            
            //finding a university
                $uni = University::find_by_long_name($_POST['reg_university']);
                
                if(!empty($uni)){
                $relation->university_id = $uni->university_id;    
                }
                else{
                $new_uni = new University();
                $new_uni->long_name = $_POST['reg_university'];
                $new_uni->status = 'draft';
                $new_uni->save();
                $relation->university_id = $new_uni->university_id;    
                }
            
            $relation->save();
            
            
            //These are for saving additional relations        
            $add_relations = $_POST['addcount'];
            unset($relation->university_relation_id);
            for ($i = 1; $i <= $add_relations; $i++){
                $relation->user_id = $session->user_id;
                $relation->role = $_POST['reg_role_value'.$i];
                
                //finding a university
                $uni = University::find_by_long_name($_POST['reg_university'.$i]);
                
                if(!empty($uni)){
                $relation->university_id = $uni->university_id;    
                }
                else{
                $new_uni = new University();
                $new_uni->long_name = $_POST['reg_university'.$i];
                $new_uni->status = 'draft';
                $new_uni->save();
                $relation->university_id = $new_uni->university_id;    
                }
                
                $relation->save();
                unset($relation->university_relation_id);            
                unset($uni);            
            }
        }
        $error_code = 0;
        
        redirect_to("/");
    }
?>