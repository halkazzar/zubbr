<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = "\n<script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";
    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }
    $per_page = 4;

    //Setting up nessessary variables used in "_ask.inc" block;  
    if (isset($_GET['cat_id']) && isset($_GET['object_id'])){       
        $question_mode = 'object';
        $current_object_id = $_GET['object_id'];
        $current_category = $_GET['cat_id'];

        
        //Title                                                          
        if ($current_category == 'university'){
            $page_title = '}:) - Образование в Казахстане и за рубежом'.$pipe.' Вопрос/Ответ ' .$pipe. ' ' . University::find_by_id($current_object_id)->short_name;
            //$meta_keywords = '';
            $meta_description = 'Что люди спрашивают насчет ' . University::find_by_id($current_object_id)->short_name;
        }
        elseif ($current_category == 'location'){
            $page_title = '}:) '.$pipe.' Вопрос/Ответ ' .$pipe. ' ' . Location::find_by_id($current_object_id)->location_name;
            //$meta_keywords = '';
            $meta_description = 'Что люди спрашивают насчет ' . Location::find_by_id($current_object_id)->location_name;
        }
        elseif ($current_category == 'scholarship'){
            $page_title = '}:) '.$pipe.' Вопрос/Ответ ' .$pipe. ' ' . Scholarship::find_by_id($current_object_id)->title;
            //$meta_keywords = '';
            $meta_description = 'Что люди спрашивают насчет ' . Scholarship::find_by_id($current_object_id)->title;
        }
            
        //Used for pagination
        $total_questions = Question::count_by_object($current_category, $current_object_id); 
    }
    else {
        $question_mode = 'all';
        
        //TItle
        $page_title = '}:) '.$pipe.' Вопрос/Ответ ' .$pipe. ' Все вопросы';
        
        //Used for pagination 
        $total_questions = Question::count_all();
    }

    $pagination = new Pagination($current_page, $per_page, $total_questions);
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "qa";
    $current_tab = "all questions";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

    
?>

<div class="main" id="main-two-columns">
    <div class="left" id="main-left">
        <div class="post">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";?> 
            
                <?php
                    if($pagination->total_pages() > 1) :?>
                    <div class="content-separator"></div>
                    <div class="post-meta archive-pagination">
                        <?php    if($pagination->has_previous_page()) { 
                                echo "<a href=\"/questions";
                                if (isset($current_object_id) && isset($current_category)) echo "/cat/{$current_category}/{$current_object_id}";
                                echo "/page_";
                                echo $pagination->previous_page();
                                echo "/";
                                echo "\">&laquo; Назад</a> "; 
                            }

                            echo "<a href=\"/questions";
                            if (isset($current_object_id) && isset($current_category)) echo "/cat/{$current_category}/{$current_object_id}";
                            echo "/page_1/";
                            echo "\">Первая</a> ";
                            
                            for($i=1; $i <= $pagination->total_pages(); $i++) {
                                if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                                if($i == $current_page) {
                                    echo " <span class=\"selected\">{$i}</span> ";
                                } else {
                                    echo "<a href=\"/questions";
                                    if (isset($current_object_id) && isset($current_category)) echo "/cat/{$current_category}/{$current_object_id}";
                                    echo "/page_";
                                    echo $i;
                                    echo "/";
                                    echo "\">{$i}</a> ";  
                                }    
                                }
                                
                            }

                            echo "<a href=\"/questions";
                            if (isset($current_object_id) && isset($current_category)) echo "/cat/{$current_category}/{$current_object_id}";
                            echo "/page_".$pagination->total_pages()."/";
                            echo "\">Последняя</a> ";
                            
                            if($pagination->has_next_page()) { 
                                echo "<a href=\"/questions";
                                if (isset($current_object_id) && isset($current_category)) echo "/cat/{$current_category}/{$current_object_id}";
                                echo "/page_";
                                echo $pagination->next_page();
                                echo "/"; 
                                echo "\">Вперед &raquo;</a> ";
                        }  ?>
                    </div>
                    <?php endif;?>
            
        </div>    
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="right sidebar" id="sidebar-2">
        
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_tag_cloud.inc";?>     
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        