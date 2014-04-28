<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    if(!empty($_GET['page'])){
        switch ($_GET['page']) {
           case 'rules':
             $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Правила сайта ';
             $meta_description = 'Нестрогие правила пребывания на проекте Зуббр.кз';
             $meta_keywords = "правила, соглашение, регистрация, зубр, зуббр, zubr, zubbr";
             $page = 'help_rules.inc';
             $current_tab = "rules";
             break;
           case 'promo':
             $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Сотдрудничество ';
             $meta_description = 'Узнайте об условиях сотрудничества с Зуббр.кз';
             $meta_keywords = "реклама, баннер, вуз, университет, зубр, зуббр, zubr, zubbr";
             $page = 'help_promo.inc';
             $current_tab = "promo";
             break;
           case 'about':
           $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' О проекте ';
           $meta_description = 'Узнайте о целях и сути проекта Зуббр.кз';
           $meta_keywords = "зубр, зуббр, zubr, zubbr, зубр.кз, зуббр.кз, zubr.kz, zubbr.kz";
             $page = 'help_about.inc';
             $current_tab = "about";
             break; 
           case 'contacts':
           $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Контакты ';
           $meta_description = 'Наши контакты здесь. Звоните, пишите, следите';
           $meta_keywords = "следить за зубр, зуббр, zubr, zubbr";
           $page = 'help_contacts.inc';
             $current_tab = "contacts";
             break;
           case 'ads':
           $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Реклама ';
           $meta_description = 'Узнайте о предложениях насчет реклама на проекте';
           $meta_keywords = "реклама, баннер, вуз, университет, зубр, зуббр, zubr, zubbr";
           $page = 'help_ads.inc';
             $current_tab = "ads";
             break;
        }
    }
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

    //Navigation block
    $sub_navigation = "help";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     


?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/".$page;?>
    </div>
    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        