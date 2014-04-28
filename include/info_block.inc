<div>
<?php if(isset($_SESSION['confirmation_status']) && $_SESSION['confirmation_status'] == 1):?> 
        <div id="social_network" class="myform window2 reg_window">
            <form name="reg" id="reg" action="/" method="post">   
                <h1>На ваш адрес был выслан ключ подтверждения</h1>
                <p>Используя учетную запись вКонтакте для входа на проект ZUBBR.KZ, Вы автоматический принимаете условия <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>.</p>
                
                <button type="submit" class="center" name="close">Закрыть</button>
                <div class="spacer"></div>
            </form> 
        </div>

        <div id="wait">
        </div> 
<?php 
elseif(empty($already_reg_user_vk) && !$session->is_logged_in() && !empty($vk_user) && !empty($new_user_login) && ($_SESSION['confirmation_status'] == 2)):?> 
        <div id="social_network" class="myform window2 reg_window">
            <form name="reg" id="reg" action="/users/registration/index_controller.php" method="post">   
                <h1>Ваш Email адрес подтвержден</h1>
                <p>Используя учетную запись вКонтакте для входа на проект ZUBBR.KZ, Вы автоматический принимаете условия <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>.</p>
                <label>
                    <span class="loginWarning small"></span>
                </label>
                <input class="fb_login hidden" name="reg_login"  type="text"  tabindex="1" value="<?php if(!empty($new_user_login)) echo $new_user_login?>"/>
                <div class="loginCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="loginWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input class="fb_password hidden" name="reg_password" type="password" tabindex="2" value="<?php echo randompassword(6)?>"/>  
                <label>
                    <span class="emailWarning small"></span>
                </label>
                <input class="fb_email hidden" name="reg_email" type="text" tabindex="3" value="<?php if(!empty($new_user_email)) echo $new_user_email?>"/>  
                <div class="emailCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="emailWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input name="network" type="hidden" value="vk"></hidden>
                
                <button type="submit" class="center" name="register_user" id="fb_login">ОК</button>
                <div class="spacer"></div>
            </form> 
        </div>

        <div id="wait">
        </div> 
   <?php  elseif(empty($already_reg_user_vk) && !$session->is_logged_in() && !empty($vk_user) && !empty($new_user_login)):?> 
        <div id="social_network" class="myform window2 reg_window">
            <form name="reg" id="reg" action="/users/registration/index_controller.php" method="post">   
                <h1>Вход с помощью вКонтакте</h1>
                <p>Используя учетную запись вКонтакте для входа на проект ZUBBR.KZ, Вы автоматический принимаете условия <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>.</p>
                <label>Ваше имя
                    <span class="loginWarning small"></span>
                </label>
                <input class="fb_login" name="reg_login"  type="text"  tabindex="1" value="<?php if(!empty($new_user_login)) echo $new_user_login?>"/>
                <div class="loginCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="loginWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input class="fb_password hidden" name="reg_password" type="password" tabindex="2" value="<?php echo randompassword(6)?>"/>  
                <label>Ваш E-mail
                    <span class="emailWarning small">Для получения кода активации</span>
                </label>
                <input class="fb_email" name="reg_email" type="text" tabindex="3" value="<?php if(!empty($new_user_email)) echo $new_user_email?>"/>  
                <div class="emailCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="emailWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input name="network" type="hidden" value="vk"></hidden>
                
                <button type="submit" class="center" name="register_user" id="fb_login">ОК</button>
                <div class="spacer"></div>
            </form> 
        </div>

        <div id="wait">
        </div> 
   <?php elseif(empty($already_reg_user_facebook) && !$session->is_logged_in() && !empty($cookie) && !empty($new_user_login)):?> 
        <div id="social_network" class="myform window2 reg_window">
            <form name="reg" id="reg" action="/users/registration/index_controller.php" method="post">   
                <h1>Вы вошли с Facebook</h1>
                <p>Используя учетную запись Facebook для входа на проект ZUBBR.KZ, Вы автоматический принимаете условия <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>.</p>
                <label>Выберите себе имя
                    <span class="loginWarning small"></span>
                </label>
                <input class="fb_login" name="reg_login"  type="text"  tabindex="1" value="<?php if(!empty($new_user_login)) echo $new_user_login?>"/>
                <div class="loginCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="loginWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input class="fb_password hidden" name="reg_password" type="password" tabindex="2" value="<?php echo randompassword(6)?>"/>  
                <input class="fb_email hidden" name="reg_email" type="text" tabindex="3" value="<?php if(!empty($new_user_email)) echo $new_user_email?>"/>  
                <input name="network" type="hidden" value="facebook"></hidden>
                <button type="submit" class="center" name="register_user" id="fb_login">ОК</button>
                <div class="spacer"></div>
            </form> 
        </div>

        <div id="wait">
        </div> 
<?php elseif(empty($already_reg_user_mailru) && !$session->is_logged_in() && !empty($cookie_mailru) && !empty($new_user_login)):?> 
        <div id="social_network" class="myform window2 reg_window">
            <form name="reg" id="reg" action="/users/registration/index_controller.php" method="post">   
                <h1>Вы вошли с Mail.ru</h1>
                <p>Используя учетную запись Mail.ru для входа на проект ZUBBR.KZ, Вы автоматический принимаете условия <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>.</p>
                <label>Выберите себе имя
                    <span class="loginWarning small"></span>
                </label>
                <input class="fb_login" name="reg_login"  type="text"  tabindex="1" value="<?php if(!empty($new_user_login)) echo $new_user_login?>"/>
                <div class="loginCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="loginWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                <input class="fb_password hidden" name="reg_password" type="password" tabindex="2" value="<?php echo randompassword(6)?>"/>  
                <input class="fb_email hidden" name="reg_email" type="text" tabindex="3" value="<?php if(!empty($new_user_email)) echo $new_user_email?>"/>  
                <input name="network" type="hidden" value="mailru"></hidden>
                <button type="submit" class="center" name="register_user" id="fb_login">ОК</button>
                <div class="spacer"></div>
            </form> 
        </div>

        <div id="wait">
        </div> 
<?php endif;?>
    <div id="basic" class="myform window2">
        <form name="login_form" id="login_form" action="/" method="post">   
            <h1 class="left">Вход</h1>
            <a href="#" class="close right" style="text-decoration: none">&nbsp;</a>
            <div class="clearer">&nbsp;</div>

            <p class="left">Введите свой логин и пароль</p>
            <p class="text-right"><a href="/users/registration/">Регистрация</a></p>
            <div class="clearer">&nbsp;</div>

            <label for="login">Логин

            </label>
            <input type="hidden" name="enter_user" value="Y" />
            <input type="text" class="login nopadding" id="login" name="login" tabindex="5"/> 
            <label for="password">Пароль
                <span class="passwordWarning small"><a href="#" id="remind_pass_link">Напомнить пароль</a></span>
            </label>
            <input type="password" class="login nopadding" id="password" name="password" tabindex="6"/>
            <button type="submit" class="center" name="login_user" tabindex="7">Вход</button>
            <div class="spacer"></div>
        </form>
    </div>
    <?php if($session->is_logged_in()):?>
        <div id="invite" class="myform window2"  style="background: #FAFAFA; border: 2px solid #cdc9c9;"> 
            <form name="promo_form" id="promo_form" action="/" method="post">   
                <h1 class="left">Подтяни друзей</h1>
                <a href="#" class="close right" style="text-decoration: none">&nbsp;</a>
                <div class="clearer">&nbsp;</div>

                <p class="left">Введи email адрес получателя</p>


                <div class="content-separator">&nbsp;</div>
                <label for="email">Email получателя:
                </label>
                <input type="text" class="promo right normargin" id="email" name="email" tabindex="3"/>
                <div class="clear"></div>

                <label for="message">Сообщение:
                </label>
                <?php 
                    $inviter = User::find_by_id($session->user_id);
                    $text = 'Привет! Твой друг ';
                    if (empty($inviter->first_name) && empty($inviter->last_name)) $text .= $inviter->login; else $text .= $inviter->first_name .' '.$inviter->last_name;
                    $text .= ' приглашает Тебя на Зуббр.кз.';
                ?>
                <textarea class="promo right" tabindex="4" id="message" name="message" rows="5" cols="10"><?php echo $text?>                      
                </textarea>
                <div class="clear">&nbsp;</div>
                <button type="submit" class="right" name="invite_friend" tabindex="5">Отправить</button>

                <div class="spacer"></div>
            </form> 
        </div>
        <?php endif?>
    <div id="remind_pass" class="myform window2"  style="background: #FAFAFA; border: 2px solid #cdc9c9;"> 
        <form name="remind_pass_form" action="/" method="post">   
            <h1 class="left">Восстановление пароля</h1>
            <a href="#" class="close right" style="text-decoration: none">&nbsp;</a>
            <div class="clearer">&nbsp;</div>

            <p class="left">Введите свой логин или e-mail</p>


            <div class="content-separator">&nbsp;</div>
            <label for="login_email">Логин или e-mail:
            </label>
            <input type="text" class="promo right normargin" id="login_email" name="login_email" tabindex="3"/>
            <div class="clear"></div>

            <div class="clear">&nbsp;</div>
            <button type="submit" class="right" name="remind_pass_button" tabindex="5">Восстановить</button>

            <div class="spacer"></div>
        </form> 
    </div>       
    <div id="mask2">
    </div>

    <?php 
        /// Facebook SCRIPTS
    ?>
    <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
    <script type="text/javascript">
        FB.init({appId: '<?php echo FACEBOOK_APP_ID ?>', status: true,
            cookie: true, xfbml: true});

    </script>
    <?php 
        /// MAIL.RU SCRIPTS
    ?>
    <script type="text/javascript" src="http://cdn.connect.mail.ru/js/loader.js"></script>
    <script type="text/javascript">
        window.onload = function(){
        mailru.loader.require('api', function() {
            mailru.connect.init('<?php echo MAILRU_APP_ID ?>',
            '<?php echo MAILRU_PRIVATE ?>');

            mailru.events.listen(mailru.connect.events.login, function(session){
                window.location = "http://www.zubbr.kz/mailru/";// user successfully logged in
            });
            mailru.connect.initButton();
        });    
        }
        
    </script>
    
    <?php 
        /// VKontakte SCRIPTS
    ?>
    <script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>

    <script type="text/javascript">
        
        VK.init({
            apiId: <?php echo VKONTAKTE_APP_ID ?>, nameTransportPath: '/xd_receiver.html'
        });



        VK.UI.button('login_button');
    </script>



    
</div>


<div id="site">
<div class="center-wrapper">
<div id="header"> 
<div id="toolbar">
<?php require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/_banner_top.inc";?>
</div>






<div id="site-title">
    <div class="left">
        <h1><a href="/">Зуббр.кз</a><span> / }:)</span></h1>
    </div>

    <?php if(!$session->is_logged_in()):?>
        <div class="left">
            <table style="vertical-align: middle; margin: 0px; padding: 0px; margin-left: 50px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Обучение за рубежом
                    </td>
                </tr>
            </table>
            <table style="vertical-align: middle; margin: 0px; padding: 0px; margin-left: 150px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Ответы от выпускников
                    </td>
                </tr>
            </table>
            <table style="vertical-align: middle; margin: 0px; padding: 0px; margin-left: 250px; width: 250px;">
                <tr style="float: left; margin: 0px; padding: 0px; width: 250px;">
                    <td style="float: left; margin: 0px; padding: 0px;">
                        <img src="/images/check.jpg" alt=""/>
                    </td>
                    <td style="text-transform: uppercase; font-size: 10pt; font-weight: bold; font-family: arial; padding-left: 0px;">
                        Тесты для поступающих
                    </td>
                </tr>
            </table>


        </div>
<?php endif;?>

        <div class="right" style="margin-top: 10px;">
            <?php if(!$session->is_logged_in()):?>
            <a href="/users/registration/"><img src="/images/join.png" alt="Перейти к регистрации" /></a>
            <?php else:?>
            <a href="/questions/ask/"><img src="/images/ask.png" alt="Задать вопрос" /></a>
            <?php endif;?>
        </div>
        

    <div class="clearer">&nbsp;</div>
</div>

            
    