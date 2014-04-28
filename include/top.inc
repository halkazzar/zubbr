<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    
    
    //AUTHENTIFICATION BLOCK
    if (!$session->is_logged_in()){
        if (isset($_POST['enter_user']) and $_POST['enter_user']=='Y'){
            $login = $_POST['login'];
            $pass = $_POST['password'];

            if (isset($login) && isset($pass)){
                $found_user = User::authenticate($login, $pass);
                if (isset($found_user) && !empty($found_user)) {
                    $session->login($found_user);
                    log_action('LOGIN', 'SUCCESS', 1, $found_user->login); 

                    $found_user->last_visit = date($dateformat);
                    $found_user->pass_remind = 0; //If user is logged in successfully, then we should clear the PASS_REMIND
                    $found_user->update();
                    $error_code = 0;
                    if('http://www.zubbr.kz/welcome' == $_SERVER['HTTP_REFERER']){
                        redirect_to('/');    
                    }
                    else{
                        redirect_to($_SERVER['HTTP_REFERER']);    
                    } 
                }
                else $error_code = 1;
                log_action('LOGIN', 'FAILED', 0);  
            }
        }
    }

    //LOGOUT BLOCK
    if(isset($_POST['logout']) && $_POST['logout']=='true'){ 
        // - Facebook logout
        $cookie_log_out = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
        if(!empty($cookie_log_out)){
            $fb_user_to_logout = User::find_by_id($session->user_id);
            $fb_user_to_logout->facebook_status = 0;
            $fb_user_to_logout->save();
        }
        // - Mailru logout
        parse_str(urldecode($_COOKIE['mrc']), $cookie_mailru);
        if(!empty($cookie_mailru)){
            $mailru_user_to_logout = User::find_by_id($session->user_id);
            $mailru_user_to_logout->mailru_status = 0;
            $mailru_user_to_logout->save();
        }
        $session->logout();
    }

    //CHANGING LANGUAGE
    if (isset($_GET['language'])) 
    {
        $_SESSION['language'] = $_GET['language'];
        redirect_to("index.php");
    }
    
    //REQUESTING PROMO CODE
    if (isset($_POST['request']) && !empty($_POST['yourself']) && preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9]+[a-zA-Z0-9_-]*)+$/", $_POST['email'])){

        //---SENDING TO US
        $mail = new PHPMailerLite();
        $mail->CharSet = 'utf-8';
        $mail->ContentType = 'text/html';
        $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');
        $mail->AddAddress('abylkassym@gmail.com', 'Zhanat Abylkassym');
        $mail->AddAddress('daur88@gmail.com', 'Dauren Sarsenov');
        $mail->Subject = 'Заявка на промокод';
        $mail->Body  = 'Новый чел хочет промокод:<br /><br />';
        $mail->Body .= 'Email: ' . $_POST['email'] . '<br />';
        $mail->Body .= 'О себе: ' . $_POST['yourself'] . '<br />';
        $mail->Send();

        //---SENDNING TO REQUESTER
        $mail2 = new PHPMailerLite();
        $mail2->CharSet = 'utf-8';
        $mail2->ContentType = 'text/html';
        $mail2->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');
        $mail2->AddAddress('abylkassym@gmail.com', 'Zhanat Abylkassym');
        $mail2->AddAddress('daur88@gmail.com', 'Dauren Sarsenov');
        //$mail2->AddAddress($_POST['email'], $_POST['yourself']);
        $mail2->Subject = 'Промо-код для Зуббр.кз';
        $mail2->Body  = 'Привет!';
        $mail2->Body .= '<br />Ты запросил промо-код для входа на портал Зуббр.кз';
        $mail2->Body .= '<br />Твой промо-код: ПРОМОКОД';
        $mail2->Body .= '<br />Воспользуйся следующей ссылкой, чтобы ввести промо-код <a href="http://www.zubbr.kz/welcome">http://www.zubbr.kz/welcome</a>';
        $mail2->Body .= '<br />(если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
        $mail2->Body .= '<br /><br />После входа Ты сможешь зарегистрироваться и пользоваться всеми сервисами сайта: узнаешь об обучении за рубежом, сможешь задать вопросы выпускникам ведущих ВУЗов, подготовиться к ЕНТ и пройти пробные тесты.';
        $mail2->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';

        //$mail2->Send();
        $_SESSION['error_code'] = -4; 
    }
    
    //INVITING A FRIEND
    if (isset($_POST['invite_friend']) && !empty($_POST['message']) && preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9]+[a-zA-Z0-9_-]*)+$/", $_POST['email'])){
        $mail = new PHPMailerLite();
        $mail->CharSet = 'utf-8';
        $mail->ContentType = 'text/html';
        $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');
        $mail->AddAddress('abylkassym@gmail.com', 'Zhanat Abylkassym');
        $mail->AddAddress($_POST['email']);
        $mail->Subject = 'Зуббр.кз - приглашение';
        $mail->Body = $_POST['message'] . '<br />';
        $mail->Body .= '<br />На Зуббре Ты найдешь информацию о высшем образовании в Казахстане и за рубежом, сможешь задать вопросы об обучении выпускникам ВУЗов, подготовиться к ЕНТ и другим тестам, узнать об образовательных грантах, стажировке, вакансиях и многом другом.';
        $mail->Body .= '<br />Воспользуйся ссылкой, чтобы ввести промо-код <a href="http://www.zubbr.kz/welcome">http://www.zubbr.kz/welcome</a>';
        $mail->Body .= '<br />(если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
        $mail->Body .= '<br /><br />После входа Ты сможешь зарегистрироваться и пользоваться всеми сервисами портала.';
        $mail->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';
        $mail->Send();

        $new_invited_email = new InvitedEmail();
        $new_invited_email->email = $_POST['email'];
        $new_invited_email->save();
    }
    //REMINDING PASSWORD
    if (isset($_POST['remind_pass_button']) && !empty($_POST['login_email'])){
        $user = User::find_by_login($_POST['login_email']);
        if(empty($user)) $user = User::find_by_email($_POST['login_email']);
        if(!empty($user)){
            //WE FOUND A USER by email or username
            $new_password = randompassword(8);
            $new_hash = md5($new_password);
            $user->pass_remind = $new_hash;
            $user->save();

            $mail = new PHPMailerLite();
            $mail->CharSet = 'utf-8';
            $mail->ContentType = 'text/html';
            $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');
            $mail->AddAddress($user->email);
            $mail->Subject = 'Зуббр.кз - Восстановление пароля';

            empty($user->first_name)? $greetings = $user->login : $greetings = $user->first_name;

            $mail->Body  = 'Привет! Мы получили Твой запрос на восстановление пароля. <br />' ;
            $mail->Body .= 'Зуббр хранит пароли в зашифрованном виде, поэтому мы не можем выслать прежний пароль. <br /><br />' ;
            $mail->Body .= 'Для сброса текущего пароля и генерации нового перейди по ссылке: ' ;
            $mail->Body .= '<a href="http://www.zubbr.kz/users/passremind/'.$user->usr_id.'/'.$new_hash.'/">http://www.zubbr.kz/users/passremind/'.$user->usr_id.'/'.$new_hash.'/</a>';
            $mail->Body .= '<br /> (если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
            $mail->Body .= '<br /><br />';
            $mail->Body .= 'Позднее Ты сможешь сменить сгенерированный пароль на любой другой в настройках аккаунта, в блоке «Изменить пароль»: <a href="http://www.zubbr.kz/users/settings">http://www.zubbr.kz/users/settings</a> <br />';

            $mail->Body .= 'Если Ты вспомнил пароль или не запрашивал восстановления, просто проигнорируй это письмо<br />';
            $mail->Body .= '<hr>';
            $mail->Body .= 'Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';
            $mail->Send();            
        }


    }

    //ENTERING PROMO
    if (isset($_POST['enter']) && (!empty($_POST['promo']))){
        $promocode = Promocode::find_by_title($_POST['promo']);

        if(!empty($promocode)){
            $session->login_promo($promocode);
            if(!empty($_SESSION['requested_uri'] )){
                if($_SESSION['requested_uri'] != '/favicon.ico'){
                    redirect_to($_SESSION['requested_uri'] );
                }else{
                    redirect_to('/');
                }
                unset($_SESSION['requested_uri'] );
            }
        }
    }

    //if(($session->is_promo_validataed()) || ($session->is_logged_in())):
    //if(($session->is_promo_validataed()) || ($session->is_logged_in())):




    // -----  WORKING WITH FACEBOOK
    $cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
    if(!empty($cookie)){
        $fb_user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$cookie['access_token']));
    }

    if(!empty($fb_user->email)) {
        $new_user_email = $fb_user->email;

        //Check if user is already registered in Zubr
        $already_reg_user_facebook = User::find_by_email($new_user_email);

        if(!empty($already_reg_user_facebook)){
            if(!empty($_GET['login_by'])){
                if ($_GET['login_by'] == 'facebook'){
                    $already_reg_user_facebook->facebook_status = 1;


                    if($already_reg_user_facebook->came_from_mailru == 1){
                        //User came from MAILRU with no info about education,
                        //So we need to update it with info from Facebook

                        //---COPY-N-Paste FROM Regiester

                    }    
                }
            }

            if($already_reg_user_facebook->facebook_status == '1'){
                $session->login($already_reg_user_facebook);

            }
        }
        else{
            //This is a new user from facebook
            $new_user_login = str_replace(strstr($fb_user->email, '@'), '', $fb_user->email);    
        }
    }

    // -----  WORKING WITH VKONTAKTE
    $member = authOpenAPIMember();

    if($member !== FALSE) {
        $VK = new vkapi(VKONTAKTE_APP_ID, VKONTAKTE_SECRET);
        $vk_user = $VK->api('getProfiles', array('uids'=>$member['id'], 'fields'=>'first_name, last_name, nickname, photo_big, photo_medium, education, email'));

        
        
        $vk_user = array_shift($vk_user);
        $vk_user = array_shift($vk_user);
        $vk_user = (object)($vk_user);
        
        if(isset($_POST['close'])){
        unset($vk_user);
        $_SESSION['confirmation_status'] = 0;
        }
        
   //     print_r($vk_user);
        
   //     print_r($_SESSION);

        if(!empty($vk_user->uid)){

            //Check if user is already registered in Zubr
            $already_reg_user_vk = User::find_by_vkontakte_id($vk_user->uid);

            if(!empty($already_reg_user_vk)){
                if(!empty($_GET['login_by'])){
                    if ($_GET['login_by'] == 'vk'){
                        $already_reg_user_vk->vkontakte_status = 1;    
                    }
                }

                if($already_reg_user_vk->vkontakte_status == '1'){
                    $session->login($already_reg_user_vk);

                }
            }
            else{
                //This is a new user from vkontakte
                if(!empty($vk_user->nickname)){
                   $new_user_login = $vk_user->nickname; 
                }
                elseif(!empty($vk_user->first_name)){
                   $new_user_login = translit($vk_user->first_name); 
                }
                else{
                   $new_user_login = $vk_user->uid; 
                }
            } 
        }


    } else {
        /* Пользователь не авторизирован в Open API */
    }


    // -----  WORKING WITH MAIL_RU
    if(!empty($_COOKIE['mrc'])){
    parse_str(urldecode($_COOKIE['mrc']), $cookie_mailru);
    if(!empty($cookie_mailru)){
        $sig_mailru = sign_server_server(array('method'=>'users.getInfo', 'app_id'=>MAILRU_APP_ID, 'session_key'=>$cookie_mailru['session_key'], 'secure'=>1), MAILRU_SECRET);
        $mail_ru_user = array_shift(json_decode(file_get_contents('http://www.appsmail.ru/platform/api?method=users.getInfo&app_id='.MAILRU_APP_ID.'&session_key='.$cookie_mailru['session_key'].'&secure=1&sig='.$sig_mailru)));
    }

    if(!empty($mail_ru_user->email)) {
        $new_user_email = $mail_ru_user->email;

        //Check if user is already registered in Zubr
        $already_reg_user_mailru = User::find_by_email($new_user_email);

        if(!empty($already_reg_user_mailru)){
            if(!empty($_GET['login_by'])){
                if ($_GET['login_by'] == 'mailru'){
                    $already_reg_user_mailru->mailru_status = 1;    
                }
            }

            if($already_reg_user_mailru->mailru_status == '1'){
                $session->login($already_reg_user_mailru);

            }
        }
        else{
            //This is a new user from mailru
            $new_user_login = str_replace(strstr($mail_ru_user->email, '@'), '', $mail_ru_user->email);    
        }
    }

    //print_r($already_reg_user);    
    }
    




?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr"
    xmlns:fb="http://www.facebook.com/2008/fbml"
    xmlns:addthis="http://www.addthis.com/help/api-spec">

<head>
    <meta name="alexaVerifyID" content="b2br_uGffYsD3lHKoHYbseIxkMo" />
    <meta http-equiv="Cache-Control" content="must-revalidate" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
    <meta http-equiv="last-modified" content="2011-2-03" />
    <meta name="google-site-verification" content="HXXXVNz-7ED8u-s6I8iNfB4amnPodezt4a5VSY-vmGk" />
    <meta name="google-site-verification" content="LvPapFUzGrHq62KpvAyij5MIpo3-BShLZfIXuopDurY" />
    <?php if (isset($meta_description)) echo '<meta name="description" content="' . $meta_description .'">';?>
    <?php if (isset($meta_keywords)) echo '<meta name="keywords" content="' . $meta_keywords .'">';?>
    <link rel="stylesheet" type="text/css" href="/stylesheets/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="/javascripts/jcarousel/skins/ie7/skin.css" media="screen" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type='text/javascript' src='/javascripts/jcarousel/jcarousel.js'></script>

    <script type='text/javascript' src='/javascripts/login.js' ></script>
    <script type='text/javascript' src='/javascripts/invite.js' ></script>
    <script type='text/javascript' src='/javascripts/messagebox.js' ></script>


    <?php if (isset($include_in_head)) echo $include_in_head?>

    <!--[if lt IE 7]>
    <script defer type="text/javascript" src="/javascripts/pngfix_IE.js"></script>
    <![endif]--> 

    <?php if (!empty($page_title)):?>
        <title><?php echo $page_title?></title>
        <?php else:?>
        <title>}:) - Образование в Казахстане и за рубежом</title>
        <?php endif;?>

    <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_google_an.inc";
    ?>
</head>

<body id="top">
<div id="network" style="height: 31px;">
    <div class="center-wrapper">
        <div class="left"><a href="/">www.ZUBBR.kz</a> <span class="quiet">beta</span><span class="text-separator"> |</span>
        <span class="quiet"><?php //echo date_translate("today","top")?></span>
        </div>
            <ul class="tabbed" id="network-tabs">
                <?php if (!$session->is_logged_in()):?>
                 <li class="social">

                        <a class="fb_button fb_button_medium" onclick='FB.login(function(response) {
                            if (response.session) {
                            window.location = "http://www.zubbr.kz/facebook/";// user successfully logged in
                            } else {
                            // user cancelled login
                            }
                            }, {perms:"user_education_history,email"})'><span class="fb_button_text">Войти с Facebook</span></a>

                    </li>
                    <li class="social ">
                        <div id="login_button" onclick='VK.Auth.login(function(response){
                            if (response.session) {
                            window.location = "http://www.zubbr.kz/vk/";// user successfully logged in
                            } else {
                            // user cancelled login
                            }    
                            });'></div>

                    </li>
                    <li id="mrc" >
                        <a class="mrc__connectButton">вход@mail.ru</a>

                    </li>       
                 <?php endif;?>
            </ul>
        <div class="right">
            <ul class="tabbed" id="network-tabs">
                    <?php if (!$session->is_logged_in()):?>
                    <li>
                        <a href="#" id='login_link' style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;Войти&nbsp;</span></a>
                    </li>
                    <?php else:?>
                    <li>
                        <a href="/users/settings/" style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;<?php echo User::find_by_id($session->user_id)->login?>&nbsp;</span></a>
                    </li>
                    <li>
                        <a href="#" id='invite_link' style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;Пригласить друзей&nbsp;</span></a>
                    </li>
                    <li>
                        <form action="/" method="post" class="hidden">    
                            <input type="hidden" name="logout" value="true"></input>
                            <input type="submit" class="button hidden" id="logout" value="Выход"></input>
                        </form>
                        <a onclick="document.getElementById('logout').click()" href="#" id='logout_link' style="font-weight: bold; text-decoration: none"><span style="border-bottom: 1px dotted; color: black">&nbsp;Выйти&nbsp;</span></a>
                    </li>
                    <?php endif;?>
                <li><a style="text-decoration: none;"><?php echo TOP_FOLLOW_US?></a></li>
                <li class="current-tab-first"><a href="http://www.facebook.com/pages/Zubbrkz-%D0%B2%D1%81%D0%B5-%D0%BE-%D0%B7%D0%B0%D1%80%D1%83%D0%B1%D0%B5%D0%B6%D0%BD%D0%BE%D0%BC-%D0%BE%D0%B1%D1%80%D0%B0%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B8/162881643764361?sk=wall" target="blank"><img src="/images/icon-fb.png" alt="Наша страница на Facebook"/></a></li>
                <li class="current-tab"><a href="http://twitter.com/zubbrkz" target="blank"><img src="http://twitter-badges.s3.amazonaws.com/t_small-a.png" alt="Следуйте за нами в Twitter"/></a></li>
                <li class="current-tab"><a href="http://vkontakte.ru/public24776912"><img src="/images/icon-vkontakte.gif" alt="Следуйте за нами вКонтакте"/></a></li>
                <li class="current-tab"><a href="http://my.mail.ru/mail/zubbrkz" target="blank"><img src="/images/icon-mailru.png" alt="Следуйте за нами в МойМир"/></a></li>
            </ul>

        </div>
        <div class="clearer">&nbsp;</div> 
    </div>
</div>
