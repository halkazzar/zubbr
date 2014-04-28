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
        $question_mode = 'unanswered';
        
        //TItle
        $page_title = '}:) '.$pipe.' Вопрос/Ответ ' .$pipe. ' Неотвеченные вопросы';
        
        //Used for pagination 
        $total_questions = count(Question::find_unanswered());
    

    $pagination = new Pagination($current_page, $per_page, $total_questions);
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "qa";
    $current_tab = "unanswered";
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
                                echo "<a href=\"/questions/unanswered";
                                echo "/page_";
                                echo $pagination->previous_page();
                                echo "/";
                                echo "\">&laquo; Назад</a> "; 
                            }

                            echo "<a href=\"/questions/unanswered";
                            echo "/page_1/";
                            echo "\">Первая</a> ";
                            
                            for($i=1; $i <= $pagination->total_pages(); $i++) {
                                if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                                if($i == $current_page) {
                                    echo " <span class=\"selected\">{$i}</span> ";
                                } else {
                                    echo "<a href=\"/questions/unanswered";
                                    echo "/page_";
                                    echo $i;
                                    echo "/";
                                    echo "\">{$i}</a> ";  
                                }    
                                }
                                
                            }

                            echo "<a href=\"/questions/unanswered";
                            echo "/page_".$pagination->total_pages()."/";
                            echo "\">Последняя</a> ";
                            
                            if($pagination->has_next_page()) { 
                                echo "<a href=\"/questions/unanswered";
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
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        