<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    //TItle
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Выпускник ' .$pipe. ' Все предложения о работе';
    $meta_description = 'Узнайте о самых актуальных вакансиях для студентов и выпускников';
    $meta_keywords = 'работа для студентов, выпускник, стажировка, практика, асистент, помощник, неполный рабочий день';
    
    //Used for pagination
    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }else{
        $current_page = 1;
    }
    $total_jobs = count(Jobposting::find_all());
    $per_page = 4;
    $pagination = new Pagination($current_page, $per_page, $total_jobs);
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "main";
    $current_tab = "alumni";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

    $postings = Jobposting::find_recent($per_page, $pagination->offset());
?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="post">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_jobpostings.php";?> 
            
                <?php
                    if($pagination->total_pages() > 1) :?>
                    <div class="content-separator"></div>
                    <div class="post-meta archive-pagination">
                        <?php    if($pagination->has_previous_page()) { 
                                echo "<a href=\"/job";
                                echo "/page_";
                                echo $pagination->previous_page();
                                echo "/";
                                echo "\">&laquo; Назад</a> "; 
                            }

                            for($i=1; $i <= $pagination->total_pages(); $i++) {
                                if($i == $current_page) {
                                    echo " <span class=\"selected\">{$i}</span> ";
                                } else {
                                    echo "<a href=\"/job";
                                    echo "/page_";
                                    echo $i;
                                    echo "/";
                                    echo "\">{$i}</a> ";  
                                }
                            }

                            if($pagination->has_next_page()) { 
                                echo "<a href=\"/job";
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
        