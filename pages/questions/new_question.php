<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    $include_in_head = "<link rel='stylesheet' type='text/css' href='/stylesheets/form.css' media='screen' />";
    $include_in_head .= "<link rel='stylesheet' type='text/css' href='/javascripts/tabs/jquery-ui-1.8.7.custom.css' media='screen' />";
    
    //$include_in_head .= "\n<script type='text/javascript' src='/javascripts/_answer.js'></script>";
    //$include_in_head .= "\n<script type='text/javascript' src='/javascripts/__questions.js'></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/tabs/jquery-ui-1.8.7.custom.min.js' ></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/_ask.js' charset='utf-8'></script>";
    

    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Вопрос/Ответ ' .$pipe. ' ' . 'Задать вопрос';
    $meta_description = 'Задай вопрос про образование в Казахстане и за рубежом';
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "qa";
    $current_tab = "ask";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     
?>
<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="post">
<div class="post-title"><h2>Как задавать вопрос</h2></div> 
    <div class="post-date">&nbsp;</div> 
    <div class="post-body">
        <p><b>И снова привет!</b></p>
        <p>Мы с удовольствием готовы помочь тебе. Только вот не на все вопросы получается ответить. Чтобы повысить свои шансы получить ответ, и чтобы все было тип-топ следуй советам ниже:</p>
        <p><b>Поищи свой вопрос</b></p>
        <p>Очень часто случается, что пользователи задают вопрос, а потом находят ответ совсем в другом месте. По этому очень рекомендуется просмотреть уже имеющиеся вопросы на сайте.<p>
        <p><b>Будь предельно конкретен</b></p>
        <p>Как известно, на общий вопрос дается общий ответ. Так что если хочешь узнать что то, то не поленись добавить деталей.
        </p>
        <p><b>Будь готов ко всему</b></p>
        <p>Иногда можно получить весьма неожиданный ответ, но тем не менее помни, что неожиданный ответ не значит неправильный ответ =)
    </div>
    
    <div class="content-separator">&nbsp;</div>
    
    <div class="section-content">
        <div class="col3-avatar_ask left avatar"> 
            <a href="#"><img src="/images/users/avatars/<?php if ($session->is_logged_in() && (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . User::find_by_id($session->user_id)->usr_id . '.jpg'))) {echo User::find_by_id($session->user_id)->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered center" /></a> 
            
        </div> 
        <div class="col3-mid-body body"> 
            <div id="ask" class="myform">
                <form name="ask" action="">
                    <label>У меня есть вопрос:</label><br />
                    <textarea name="question" id="questionText" rows="10" cols="10"></textarea>
                    
                    <div class="clear"></div>
                    <div class="add">
                    <label>Теги к вопросу:</label><br />
                    <input name="tags" id="tagsText"></input>
                    <button type="submit" id="submitQuestion" class="right">Задать вопрос</button>
                    
                    <div class="clear"></div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        <div class="clearer">&nbsp;</div>
        <?php
            $question_mode = 'none';
        ?>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";?> 
    </div>
    <div class="clearer">&nbsp;</div>
         
    </div>
    
        
    
         <div class="clearer">&nbsp;</div>

    </div>



    <div class="right sidebar" id="sidebar-2">
          
    </div> 
    <div class="clearer">&nbsp;</div>

        </div>         
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        