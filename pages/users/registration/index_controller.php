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

        //Fetching data from social networks
        if(!empty($_POST['network'])){
            
            if($_POST['network'] == 'facebook'){
                $network = 'facebook';

                //Fetching data from facebook
                $cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
                if(!empty($cookie)){
                    $user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$cookie['access_token']));
                }
            }
            elseif($_POST['network'] == 'mailru'){
                $network = 'mailru';

                //Fetching data from Mailru
                parse_str(urldecode($_COOKIE['mrc']), $cookie_mailru);
                if(!empty($cookie_mailru)){
                    $sig_mailru = sign_server_server(array('method'=>'users.getInfo', 'app_id'=>MAILRU_APP_ID, 'session_key'=>$cookie_mailru['session_key'], 'secure'=>1), MAILRU_SECRET);
                    $mail_ru_user = array_shift(json_decode(file_get_contents('http://www.appsmail.ru/platform/api?method=users.getInfo&app_id='.MAILRU_APP_ID.'&session_key='.$cookie_mailru['session_key'].'&secure=1&sig='.$sig_mailru)));
                }
            }
            elseif($_POST['network'] == 'vk'){
                $network = 'vk';

                //Fetching data from Vkontakte
                $member = authOpenAPIMember();
                if($member !== FALSE) {
                    $VK = new vkapi(VKONTAKTE_APP_ID, VKONTAKTE_SECRET);
                    $vk_user = $VK->api('getProfiles', array('uids'=>$member['id'], 'fields'=>'first_name, last_name, nickname, photo_big, photo_rec,education,email'));

                    $vk_user = array_shift($vk_user);
                    $vk_user = array_shift($vk_user);
                    $vk_user = (object)($vk_user);
                }
                $VK_email_free = User::is_email_free($_POST['reg_email']);
                if($VK_email_free == 0){

                    // Email is not free, which means this user could be
                    // already registered by other social network

                    $temp = User::find_by_login('vk' . $vk_user->uid);
                    if(!empty($temp)){
                        $VK_email_confirmed = $temp->is_email_activated();    
                    }
                    else{
                        $VK_email_confirmed = 0;
                    }


                    if($VK_email_confirmed == 0){

                        //Email is not confirmed, sending a confirmation
                        $temp_user = new User();
                        $temp_user->login = 'vk' . $vk_user->uid;
                        $temp_user->vkontakte_email = $_POST['reg_email'];
                        $temp_user->save();
                        $temp_user->new_email_activation();

                        $hash = $temp_user->email_activated;

                        $mail = new PHPMailerLite();
                        $mail->AddAddress($_POST['reg_email']);
                        $mail->CharSet = 'utf-8';
                        $mail->ContentType = 'text/html';
                        $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');

                        $mail->Subject = 'Зуббр.кз – подтверждение адреса электронной почты';

                        $mail->Body = 'Привет!  Для работы с порталом Тебе необходимо подтвердить электронный адрес. Для этого перейди по ссылке ';
                        $mail->Body .= '<a href = "http://www.zubbr.kz/users/emailactivate/'.$temp_user->usr_id.'/'. $hash .'/r">'. 'http://www.zubbr.kz/users/emailactivate/'.$temp_user->usr_id.'/'. $hash .'/r'. '</a>';
                        $mail->Body .= '<br />(если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
                        $mail->Body .= '<br /><br />После подтверждения Ты сможешь получать уведомления о заданных Тобой вопросах и ответах на них.';
                        $mail->Body .= '<br />Настроить уведомления можно в Твоем аккаунте <a href="http://www.zubbr.kz/users/settings/">http://www.zubbr.kz/users/settings/</a> в блоке «Подписка на вопросы».';
                        $mail->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';

                        $mail->Send();

                        $_SESSION['confirmation_status'] = 1;
                        redirect_to('/');
                    }
                }
            }
        }

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

            if($network == 'facebook'){
                if(!empty($user->first_name)) {
                    $new_user->first_name = $user->first_name;    
                }
                if(!empty($user->last_name)) {
                    $new_user->last_name = $user->last_name;    
                }
                $new_user->came_from_facebook = 1; //Flag indicates the user came from Facebook
            }
            if($network == 'mailru'){
                if(!empty($mail_ru_user->first_name)) {
                    $new_user->first_name = $mail_ru_user->first_name;    
                }
                if(!empty($mail_ru_user->last_name)) {
                    $new_user->last_name = $mail_ru_user->last_name;    
                }
                $new_user->came_from_mailru = 1; //Flag indicates the user came from Mailru
            }
            if($network == 'vk'){
                if(!empty($vk_user->first_name)) {
                    $new_user->first_name = $vk_user->first_name;    
                }
                if(!empty($vk_user->last_name)) {
                    $new_user->last_name = $vk_user->last_name;    
                }
                $new_user->came_from_vkontake = 1; //Flag indicates the user came from Vkontakte
                if($VK_email_free == 1){
                    $new_user->vkontakte_id = $vk_user->uid;
                }
            }
            
            $new_user->save();
            log_action('NEW USER', 'success', '1', $new_user->login);
        }
        elseif($VK_email_confirmed == 1){
            $new_user = User::find_by_email(User::find_by_login('vk' . $vk_user->uid)->vkontakte_email);
            $new_user->vkontakte_id = $vk_user->uid;
            $new_user->save();
            User::delete_by_login('vk' . $vk_user->uid);
            //print_r($new_user);
            //print_r($_POST);
        }

        if($session->is_promo_validataed()){
            $promocode = Promocode::find_by_id($session->get_promo());
            $promocode->registered++;
            $promocode->save();
        }

        //Logging in new user
        if(!empty($new_user)){
            $session->login($new_user);
            //redirect_to('/');    
        }




        //SEND EMAIL ABOUT REGISTRATION HERE;
        //php_mail


        //User registered via FACEBOOK
        if(!empty($user) && ($network == 'facebook')){
            //Email is activated
            $new_user->activate_email();    

            //These lines are for universities came from Facebook
            if(!empty($user->education)){
                $j = count($user->education);
                foreach($user->education as $edu_record){
                    if($edu_record->type == 'Graduate School' || $edu_record->type == 'College'){
                        $j--;

                        $relation = new Relation();
                        $relation->user_id = $new_user->usr_id;
                        if($edu_record->year->name < date('Y')){
                            $relation->role = 'alumni';
                        }else{
                            $relation->role = 'student';
                        }

                        $relation->date_of_graduation = date(str_replace('Y', $edu_record->year->name, $dateformat));
                        $relation->date_of_enroll = date(str_replace('Y', $edu_record->year->name * 1 - 4, $dateformat));


                        //finding a university
                        $syn = Synonym::find_by_title($edu_record->school->name);
                        if(!empty($syn)){
                            $uni = University::find_by_id($syn->university_id);
                        }
                        else{
                            $uni = University::find_by_long_name($edu_record->school->name);
                        }

                        if(!empty($uni)){
                            $relation->university_id = $uni->university_id;    
                        }
                        else{
                            $new_uni = new University();
                            $new_uni->long_name = $edu_record->school->name;
                            $new_uni->status = 'draft';
                            $new_uni->save();
                            log_action('NEW UNIVERSITY', $new_uni->long_name, '1', $new_user->login);
                            $relation->university_id = $new_uni->university_id;    
                        }

                        //finding a degree
                        $degree = Degree::find_by_title($edu_record->degree->name);

                        if(!empty($degree)){
                            $relation->degree_id = $degree->degree_id;    
                        }
                        else{
                            $new_degree = new Degree();
                            $new_degree->title = $edu_record->degree->name;
                            $new_degree->save();
                            log_action('NEW DEGREE', $new_degree->title, '1', $new_user->login);
                            $relation->degree_id = $new_degree->degree_id;    
                        }

                        $relation->save();


                    }
                }
            }

            //Now saveing avatars from Facebook
            $ava_small = file_get_contents('https://graph.facebook.com/me/picture?access_token='.$cookie['access_token']);
            $ava_large = file_get_contents('https://graph.facebook.com/me/picture?type=large&access_token='.$cookie['access_token']);
            if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg", $ava_large)){
                $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/";
                $handle = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
                $handle->image_resize            = true;
                $handle->image_ratio_crop        = 'L';
                $handle->file_overwrite          = true;
                //$handle->image_ratio_x           = false;
                $handle->image_y                 = 165;
                $handle->image_x                 = 165;
                $handle->file_new_name_body      = $new_user->usr_id . "_big";
                $handle->image_convert           = 'jpg';
                $handle->jpeg_quality            = 100;
                $handle->Process($dir_pics);
                unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
            }
            if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg", $ava_small)){
                $handle2 = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
                $handle2->image_resize            = true;
                $handle2->image_ratio_crop        = 'L';
                $handle2->file_overwrite          = true;
                //$handle2->image_ratio_x           = false;
                $handle2->image_y                 = 50;
                $handle2->image_x                 = 50;
                $handle2->file_new_name_body      = $new_user->usr_id;
                $handle2->image_convert           = 'jpg';
                $handle2->jpeg_quality            = 100;
                $handle2->Process($dir_pics);    
                unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
            }
            redirect_to("/");
        }
        elseif(!empty($mail_ru_user) && ($network == 'mailru')){
            //Email is activated
            $new_user->activate_email();    

            //Mail.ru doesn't give us info about educaation

            //Now saveing avatars from Mailru
            $ava_small = file_get_contents($mail_ru_user->pic);
            $ava_large = file_get_contents($mail_ru_user->pic_big);
            if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg", $ava_large)){
                $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/";
                $handle = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
                $handle->image_resize            = true;
                $handle->image_ratio_crop        = 'L';
                $handle->file_overwrite          = true;
                //$handle->image_ratio_x           = false;
                $handle->image_y                 = 165;
                $handle->image_x                 = 165;
                $handle->file_new_name_body      = $new_user->usr_id . "_big";
                $handle->image_convert           = 'jpg';
                $handle->jpeg_quality            = 100;
                $handle->Process($dir_pics);
                unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
            }
            if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg", $ava_small)){
                $handle2 = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
                $handle2->image_resize            = true;
                $handle2->image_ratio_crop        = 'L';
                $handle2->file_overwrite          = true;
                //$handle2->image_ratio_x           = false;
                $handle2->image_y                 = 50;
                $handle2->image_x                 = 50;
                $handle2->file_new_name_body      = $new_user->usr_id;
                $handle2->image_convert           = 'jpg';
                $handle2->jpeg_quality            = 100;
                $handle2->Process($dir_pics);    
                unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
            }
            redirect_to("/");
        }
        elseif(!empty($vk_user) && ($network == 'vk')){
            //Email is set to NOT activated
            //$new_user->activate_email();    

            //These lines are for universities came from VKONTAKTE
            if(!empty($vk_user->university) && (($VK_email_confirmed == 1) || ($VK_email_free == 1)) ){
                $relation = new Relation();
                $relation->user_id = $new_user->usr_id;
                if($vk_user->graduation < date('Y')){
                    $relation->role = 'alumni';
                }else{
                    $relation->role = 'student';
                }

                $relation->date_of_graduation = date(str_replace('Y', $vk_user->graduation, $dateformat));
                $relation->date_of_enroll = date(str_replace('Y', $vk_user->graduation * 1 - 4, $dateformat));


                //finding a university
                $syn = Synonym::find_by_title($vk_user->university_name);
                if(!empty($syn)){
                    $uni = University::find_by_id($syn->university_id);
                }
                else{
                    $uni = University::find_by_long_name($vk_user->university_name);
                }

                if(!empty($uni)){
                    $relation->university_id = $uni->university_id;    
                }
                else{
                    $new_uni = new University();
                    $new_uni->long_name = $vk_user->university_name;
                    $new_uni->status = 'draft';
                    $new_uni->save();
                    log_action('NEW UNIVERSITY', $new_uni->long_name, '1', $new_user->login);
                    $relation->university_id = $new_uni->university_id;    
                }


                $relation->save();
            }

            if($VK_email_free == 1){
                //Now saveing avatars from VKontakte
                $ava_small = file_get_contents($vk_user->photo_rec);
                $ava_large = file_get_contents($vk_user->photo_big);
                if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg", $ava_large)){
                    $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/";
                    $handle = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
                    $handle->image_resize            = true;
                    $handle->image_ratio_crop        = 'L';
                    $handle->file_overwrite          = true;
                    //$handle->image_ratio_x           = false;
                    $handle->image_y                 = 165;
                    $handle->image_x                 = 165;
                    $handle->file_new_name_body      = $new_user->usr_id . "_big";
                    $handle->image_convert           = 'jpg';
                    $handle->jpeg_quality            = 100;
                    $handle->Process($dir_pics);
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp_big.jpg");
                }
                if(file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg", $ava_small)){
                    $handle2 = new upload($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
                    $handle2->image_resize            = true;
                    $handle2->image_ratio_crop        = 'L';
                    $handle2->file_overwrite          = true;
                    //$handle2->image_ratio_x           = false;
                    $handle2->image_y                 = 50;
                    $handle2->image_x                 = 50;
                    $handle2->file_new_name_body      = $new_user->usr_id;
                    $handle2->image_convert           = 'jpg';
                    $handle2->jpeg_quality            = 100;
                    $handle2->Process($dir_pics);    
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $new_user->login . "_temp.jpg");
                }
            }
            redirect_to("/");
        }

        //User registered via REG FORM
        else{
            if ($_POST['reg_role_value'] != 'abitur' && !empty($_POST['reg_university'])){
                //User has selected either STUDENT or ALUMNI
                //Saving relation between the user and a university
                //
                $relation = new Relation();
                $relation->user_id = $new_user->usr_id;
                $relation->role = $_POST['reg_role_value'];
                $relation->date_of_enroll = date($dateformat);
                $relation->date_of_graduation = date($dateformat);

                //finding a university
                $syn = Synonym::find_by_title($_POST['reg_university']);
                if(!empty($syn)){
                    $uni = University::find_by_id($syn->university_id);
                    log_action($_POST['reg_university'] . 'uni found from syn');
                }
                else{
                    $uni = University::find_by_long_name($_POST['reg_university']);
                    log_action($_POST['reg_university'] . 'uni found from fullname'); 
                }

                //Uni is found
                if(!empty($uni)){
                    $relation->university_id = $uni->university_id;    
                }
                else{
                    $new_uni = new University();
                    $new_uni->long_name = $_POST['reg_university'];
                    $new_uni->status = 'draft';
                    $new_uni->save();
                    log_action('NEW UNIVERSITY', $new_uni->long_name, '1', $new_user->login);
                    $relation->university_id = $new_uni->university_id;    
                }

                $relation->save();


                //These are for saving additional relations        
                $add_relations = $_POST['addcount'];
                unset($relation->university_relation_id);
                for ($i = 1; $i <= $add_relations; $i++){
                    $relation->user_id = $new_user->usr_id;
                    $relation->role = $_POST['reg_role_value'.$i];

                    //finding a university
                    $syn = Synonym::find_by_title($_POST['reg_university'.$i]);
                    if(!empty($syn)){
                        $uni = University::find_by_id($syn->university_id);
                        log_action($_POST['reg_university'.$i] . 'uni found from syn');
                    }
                    else{
                        $uni = University::find_by_long_name($_POST['reg_university'.$i]);
                        log_action($_POST['reg_university'.$i] . 'uni found from fullname'); 
                    }

                    if(!empty($uni)){
                        $relation->university_id = $uni->university_id;    
                    }
                    else{
                        $new_uni = new University();
                        $new_uni->long_name = $_POST['reg_university'.$i];
                        $new_uni->status = 'draft';
                        $new_uni->save();
                        log_action('NEW UNIVERSITY', $new_uni->long_name, '1', $new_user->login);
                        $relation->university_id = $new_uni->university_id;    
                    }

                    $relation->save();
                    unset($relation->university_relation_id);            
                    unset($uni);            
                }
            }
            redirect_to("/");    
        }
    }
?>