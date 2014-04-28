<?php

    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$cookie['access_token']));

    if(!empty($user->email)) {
        $new_user_email = $user->email;
        
        //Check if user is already registered in Zubr
        $already_reg_user = User::find_by_email($new_user_email);
        if(!empty($already_reg_user)){
            $session->login($already_reg_user);
            redirect_to('/');
        }
        $new_user_login = str_replace(strstr($user->email, '@'), '', $user->email);
    }



    // template: iclude top part
    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/role_selector.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/registration_index.js' ></script>";
    
    $page_title = '}:) '.$pipe.' Регистрация ';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

    //Navigation block
    $sub_navigation = "registration";
    $current_tab = "registration";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

?>

 
<div class="main" id="main-two-columns-reg">
    <div class="left" id="main-left-reg">
        <h1>Регистрация через Facebook</h1>
        <div id="basic" class="myform">
            <form name="reg" id="reg" action="index_controller.php" method="post">   
                <h1>Внимание!</h1>
                <p>Регистрируясь на проекте ZUBR.kz, Вы автоматический принимаете условия <a href="#dialog" id="popup" name="modal">Пользовательского соглашения</a>. 
                    Все поля обязательны к заполнению.</p>
                <input name="addcount" class="hidden" value="0" id="addcount"/>
                <label>Логин
                    <span class="loginWarning small"></span>
                </label>
                <input class="reg_login" name="reg_login"  type="text"  tabindex="1" value="<?php if(!empty($new_user_login)) echo $new_user_login?>"/>
                <div class="loginCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="loginWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>


                <label>Пароль
                    <span class="passwordWarning small"></span>
                </label>
                <input class="reg_password" name="reg_password" type="password" tabindex="2"/>  
                <div class="passwordCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="passwordWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <label>E-mail
                    <span class="emailWarning small"></span>
                </label>
                <input class="reg_email" name="reg_email" type="text" tabindex="3" value="<?php if(!empty($new_user_email)) echo $new_user_email?>"/>  
                <div class="emailCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="emailWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>
                
                <?php
                    $rand_one = rand(0,9);
                    $rand_two = rand(0,9);
                ?>
                <label><b><span class="one"><?=$rand_one?></span>+<span class="two"><?=$rand_two?></span>=</b>
                    <span class="antibotWarning small">Решите уравнение</span>
                </label>
                <input class="reg_antibot"           type="text"  name="reg_antibot_display" tabindex="6"/>
                <div class="antibotCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="antibotWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>
                
                
                
                <button type="submit" class="center" name="register_user">Регистрация</button>
                <div class="spacer"></div>
            </form> 
        </div> 
    </div>

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/reg_right_sidebar.inc"; ?>
    <div class="clearer">&nbsp;</div>
</div>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc"; ?>
