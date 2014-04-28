<?php

    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";


    // template: iclude top part
    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/role_selector.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/registration_index.js' ></script>";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Регистрация ';
    $meta_keywords = 'результаты, вузы казахстана, ент 2011, ент 2010, история казахстана';
    $meta_description = 'Страница регистрации на проекте Зуббр.кз';
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

    //Navigation block
    $sub_navigation = "registration";
    $current_tab = "registration";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

?>


<div class="main" id="main-two-columns-reg">
    <div class="left" id="main-left-reg">
        <h1>Регистрация</h1>
        <div id="boxes"> 
<div id="role_selector" class="window">
                <div id="roles" class="col-countries left">
                    <ul class="countries_regions-list">
                        <li id="abitur">Абитуриент</li>
                        <li id="student">Студент</li>
                        <li id="alumni">Выпускник</li>
                    </ul>
                </div>                
            </div>             
            <div id="mask">
            </div> 
        </div>

        <div id="basic" class="myform">
            <form name="reg" id="reg" action="index_controller.php" method="post">   
                <h1>Внимание!</h1>
                <p>Регистрируясь на проекте ZUBR.kz, Вы автоматический принимаете условиями <a href="/help/rules/" id="popup" name="modal" target="_blank">Пользовательского соглашения</a>. 
                    Все поля обязательны к заполнению.</p>
                <input name="addcount" class="hidden" value="0" id="addcount"/>
                <label>Логин
                    <span class="loginWarning small"></span>
                </label>
                <input class="reg_login" name="reg_login"  type="text"  tabindex="1"/>
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
                <input class="reg_email" name="reg_email" type="text" tabindex="3"/>  
                <div class="emailCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                <div class="emailWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                
                
                
                <div class="additional_part_main">
                    <label>Я
                        <span class="roleWarning small"></span>
                    </label>
                    <input class="reg_role first"   type="text"  name="reg_role_display" tabindex="4"/>
                    <input class="reg_role hidden"  type="text"  name="reg_role_value"/>
                    <div class="roleCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                    <div class="roleWrongDiv box"   style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>


                    <div class="additional_part hidden">

                        <label>ВУЗ
                            <span class="universityWarning small" ></span>
                        </label>
                        <input class="reg_university suggest"   id="registration_uni"        type="text"  name="reg_university" tabindex="5"/>
                        <div class="universityCorrectDiv box"   style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                        <div class="universityWrongDiv box"     style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                        
                        <div class="clear"></div>
                    </div> 
                </div>

                <div id="container">
                    <div class="clear"><p></p></div>
                </div>

                
                
                <div class="additional_part hidden add_command">
                    <h3 class="center text-center"><a href="#" id="addMore">Добавить еще вуз</a></h3>
                </div>
                
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
                
                
                <div id="reg_button_place">
                </div>
                <div class="spacer"></div>
            </form> 
        </div> 
    </div>

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/reg_right_sidebar.inc"; ?>
    <div class="clearer">&nbsp;</div>
</div>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc"; ?>
