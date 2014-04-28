<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/advanced_search.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/location_selector.js' ></script>";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' ВУЗы ' .$pipe. ' Результаты поиска';
    if(!empty($_GET['location_value'])){
        $loc_meta = Location::find_by_id($_GET['location_value']);
        $meta_temp = "вузы $loc_meta->location_name, обучение в $loc_meta->location_name, образование в $loc_meta->location_name, университеты $loc_meta->location_name, ";
    }
    
    $meta_keywords = $meta_temp . 'поиск вуза, грант в вузе, вузы рк, универ, военная кафедра, стоимость обучения';
    $meta_description = 'Подбери для себя вуз с учетом таких параметров как, стоимость обучения, наличие военной кафедры и т.д.';
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "universities";
    $current_tab = "search";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc"; 

    //Used for pagination
    if(!empty($_GET['query'])){
        $keyword = $_GET['query'];
    }
    else{
        $keyword = '';
    }
    
    if(!empty($_GET['range_min'])) $range_min = $_GET['range_min']; else $range_min = 0;
    if(!empty($_GET['range_max'])) $range_max = $_GET['range_max']; else $range_max = 100000000;
    
    if(!empty($_GET['location_value'])){
        $total_universities = 0;
        for($depth = 1; $depth <= 3; $depth++){
            $total_universities  += University::count_by_keyword($keyword, $depth, $_GET['location_value'], $range_min, $range_max);
    }
    } else {
    $total_universities = University::count_by_keyword($keyword, 2, -1, $range_min, $range_max);
    }
    
    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }
    else {
        $current_page = 1;
    }
    $per_page = 5;

    $pagination = new Pagination($current_page, $per_page, $total_universities);
    //

?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        
        <div class="post">
            <?php 
                require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/univers_search.inc";
            ?>
            <div class="clearer">&nbsp;</div>        
            <?php
                    if($pagination->total_pages() > 1) :  ?>
                    <div class="post-meta archive-pagination">
                
                   <?php    if($pagination->has_previous_page()) { 
                            echo '<a href="/uni/search/?query='.$keyword;
                            echo '&amp;location_value=' . $_GET['location_value'];
                            echo '&amp;range_min=' . $_GET['range_min'];
                            echo '&amp;range_max=' . $_GET['range_max'];
                            echo '&amp;page=';
                            echo $pagination->previous_page();
                            echo '">&laquo; Назад</a> '; 
                        }

                        for($i=1; $i <= $pagination->total_pages(); $i++) {
                            if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                            if($i == $current_page) {
                                echo " <span class=\"selected\">{$i}</span> ";
                            } else {
                                echo "<a href=\"/uni/search/?query={$keyword}";
                                echo "&amp;location_value=" . $_GET['location_value'];
                            echo "&amp;range_min=" . $_GET['range_min'];
                            echo "&amp;range_max=" . $_GET['range_max'];
                            echo "&amp;page=";
                                echo $i;
                                echo "\">{$i}</a> ";  
                            }    
                            }
                        }

                        if($pagination->has_next_page()) { 
                            echo "<a href=\"/uni/search/?query=$keyword"; 
                            echo "&amp;location_value=" . $_GET['location_value'];
                            echo "&amp;range_min=" . $_GET['range_min'];
                            echo "&amp;range_max=" . $_GET['range_max'];
                            echo "&amp;page=";
                            echo $pagination->next_page();
                            echo "\">Вперед &raquo;</a> ";
                        } ?>
                        </div>
                   <?php endif;?> 
                
             
        </div>    

    </div>


    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/univers_search_filters.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        