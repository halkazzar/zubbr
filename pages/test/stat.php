<?php
    // index.php
    // Home page for tests
    // Created by Dd on 11/03/2010
    // Updated by Sarsenov Dauren on 07.09.2010

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php"; 

    
    

    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }
    $per_page = 7;

    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Пройденные тесты';
    $meta_keywords = 'статистика, ент, ент 2011, ент 2011, баллы, сколько набрал баллов';
    $meta_description = 'Статистика по тестам';
    
    
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
    
    //Navigation block
    $sub_navigation = "tests";
    $current_tab = "stats";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

    
    //Count how many test are in this language
    if($session->is_logged_in()){
        $records = Passed_test::find_by_user($session->user_id);
    }
    elseif(!empty($session->passed_test_record_id)){
        $records = Passed_test::find_by_id($session->passed_test_record_id);
        $tmp = array();
        $tmp[] = $records;
        $records = $tmp;
    }
    $total = count($records);
    $pagination = new Pagination($current_page, $per_page, $total);
    
    
    
?>

<div class="main" id="main-three-columns">
    <div class="left" id="main-left">
        <div class="post">
            <div class="post-title"><h2>Статистика по сдаче тестов</h2></div>
            <div class="post-date">&nbsp;</div>

            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_test_stat.inc";?>

            <div class="post-meta archive-pagination">
                <?php
                    if($pagination->total_pages() > 1) {
                        if($pagination->has_previous_page()) { 
                            echo "<a href=\"/test";
                            if ($lang_id == 0) echo "/ru"; elseif ($lang_id == 1) echo "/kk";
                            //if (isset($current_object_id) && isset($current_category)) echo "/{$current_category}/{$current_object_id}";
                            echo "/page_";
                            echo $pagination->previous_page();
                            echo "/";
                            echo "\">&laquo; Назад</a> "; 
                        } 

                        for($i=1; $i <= $pagination->total_pages(); $i++) {
                            if($i == $current_page) {
                                echo " <span class=\"selected\">{$i}</span> ";
                            } else {
                                echo "<a href=\"/test";
                                if ($lang_id == 0) echo "/ru"; elseif ($lang_id == 1) echo "/kk";
                            //if (isset($current_object_id) && isset($current_category)) echo "/{$current_category}/{$current_object_id}";
                                echo "/page_";
                                echo $i;
                                echo "/";
                                echo "\">{$i}</a> ";  
                            }
                        }

                        if($pagination->has_next_page()) { 
                            echo "<a href=\"/test";
                            if ($lang_id == 0) echo "/ru"; elseif ($lang_id == 1) echo "/kk";
                            //if (isset($current_object_id) && isset($current_category)) echo "/{$current_category}/{$current_object_id}";
                            echo "/page_";
                            echo $pagination->next_page();
                            echo "/"; 
                            echo "\">Вперед &raquo;</a> ";
                        }
                    }
                ?>
            </div>
        </div>
        <div class="content-separator"></div>

        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/horizontal_info.inc";?>    
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="left sidebar" id="sidebar-1">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/univers.inc";?>            
    </div>

    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_user_rating.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_partners.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
     </div>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
