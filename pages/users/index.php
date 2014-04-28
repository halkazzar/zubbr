<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Пипл ' .$pipe. ' Зал славы';
    $meta_keywords = 'студент, выпускник, абитуриент, проверенные люди, кто где хочет учиться';
    $meta_description = 'Самые выдающиеся пользователи Зуббр.кз';
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "users";
    $current_tab = "hall of fame";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     


?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="col3 left">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/include/users_topanswered.inc";?>
        </div>

        <div class="col3 col3-mid left">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/users_verified.inc";?>         
        </div>

        <div class="col3 right">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/users_whowanttoo.inc";?>             
        </div>
    </div>

    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        