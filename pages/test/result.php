<?php

    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if(!empty($_GET['record_id'])){
        if(ctype_digit($_GET['record_id'])){
            $test_id = Passed_test::find_by_id($_GET['record_id'])->test_id;
            $record_id = $_GET['record_id'];
        }    
    }
    else{   
        $new_passing = Passed_test::find_by_id($session->passed_test_record_id);
       // $new_passing->status = 'done';
       // $new_passing->save();
        $test_id = $new_passing->test_id;
        $record_id = $session->passed_test_record_id;    
    }
        
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Статистика по тесту ' . Test::find_by_id($test_id)->test_title;
    $meta_keywords = Test::find_by_id($test_id)->test_title . ' ент, ент 2011, ент 2011, пробный тест';
    $meta_description = 'Результаты пробного теста по ' .Test::find_by_id($test_id)->test_title;
    
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

    //Navigation block
    $sub_navigation = "tests";
    $current_tab = "stats";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

?>


<div class="main" id="main-two-columns">
    <div class="left test_results" id="main-left-reg">

    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/finish_test.inc"; ?>
    </div>

<div class="right sidebar" id="sidebar-2">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_user_rating.inc";?>     
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_partners.inc";?>          
            </div>
        <div class="clearer">&nbsp;</div>
</div>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc"; ?>
