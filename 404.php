<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head = "\n<script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";



    //Navigation block
    $sub_navigation = "main";
    if (!empty ($_GET['section'])){
        if ($_GET['section'] == 'abitur')  {$current_tab = "abitur";  $page_title = '}:) '.$pipe.' В зуббр '.$pipe.' Абитуриент';}
        elseif ($_GET['section'] == 'student') {$current_tab = "student"; $page_title = '}:) '.$pipe.' В зуббр '.$pipe.' Студент';} 
        elseif ($_GET['section'] == 'alumni')  {$current_tab = "alumni";  $page_title = '}:) '.$pipe.' В зуббр '.$pipe.' Выпускник';} 
    }
    else {$current_tab = "abitur"; $page_title = '}:) '.$pipe.' В зуббр '.$pipe.' Страница не найдена';}

    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

    
    
?>

<div class="main" id="main-one-column" style="height: 200px;">
    <div class="text-center">
    <h1>404 - Страница не найдена</h1>
    <h3>Хм.. странно. Вроде бы только что была..</h3>
    <h3>&nbsp;</h3>
    <h2><a href="/">Вернуться в Зуббр.кз</a></h2>
    </div>

    </div>
        
        
<?php require $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        
