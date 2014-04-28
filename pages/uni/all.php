<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/advanced_search.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/location_selector.js' ></script>";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом'.$pipe.' ВУЗы ' .$pipe. ' Все ВУЗы ';
    $meta_keywords = 'стоимость обучения, вузы, university, университет, военная кафедра, казгу, кбту, муит, назарбаев университет';
    $meta_description = 'Подбери для себя вуз из каталога, содержащего более чем 50 вузов';
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "universities";
    $current_tab = "all";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc"; 

    $total_universities = University::count_all('published');
    

    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }
    else {
        $current_page = 1;
    }
    $per_page = 5;
    $temp = $per_page;
    $pagination = new Pagination($current_page, $per_page, $total_universities);

    $universities = University::find_all($per_page, $pagination->offset(),'published');
?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="post">
            <?php if(!empty($universities)):?>
                <div class="section">
                    <div class="section-title">
                        <div class="left">Все ВУЗы</div>
                        <div class="right">Всего: <?php echo $total_universities?></div>
                        <div class="clearer">&nbsp;</div>
                    </div>
                    <div class="section-content">
                        <?php     foreach ($universities as $uni):
                                  if ($temp != $per_page) echo '<div class="university-separator"></div>'
                        ?>
                            <div class="post"> 
                                <a href="/uni/<?php echo $uni->alias ?>/"><img src="/images/universities/avatars/<?php echo $uni->alias . $uni->picture_extension ?>" alt="" class="left bordered" /></a> 
                                <h3><a href="/uni/<?php echo $uni->alias ?>/"><?php echo $uni->short_name?></a></h3> 
                                <p><?php echo $uni->short_description?></p>

                                <div class="toolbar" >
                    <a href="/uni/like/<?php echo $uni->alias?>/" title="Добавить <?php echo $uni->short_name?> в профайл"><img src="/images/icon-plus.gif" width="20" height="20" alt="" /> я хочу тут учиться</a>    
                </div> 
                                <div class="clearer">&nbsp;</div> 
                            </div>
                            
                            <?php 
                            $temp--;
                            endforeach;?>
                    </div>
                </div>  
                <?php            
                    $temp--;    

                    endif;

            ?>        


            <?php
                if($pagination->total_pages() > 1) :  ?>
                <div class="post-meta archive-pagination">

                    <?php    if($pagination->has_previous_page()) { 
                            echo "<a href=\"/uni/all/";
                            echo "?page=";
                            echo $pagination->previous_page();
                            echo "\">&laquo; Назад</a> "; 
                        }
                        
                        echo "<a href=\"/uni/all/";
                            echo "?page=1";
                            echo "\">Первая</a> ";
                            
                            
                        for($i=1; $i <= $pagination->total_pages(); $i++) {
                            if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                            if($i == $current_page) {
                                echo " <span class=\"selected\">{$i}</span> ";
                            } else {
                                echo "<a href=\"/uni/all/";
                                echo "?page=";
                                echo $i;
                                echo "\">{$i}</a> ";  
                            }    
                            }
                            
                        }

                        echo "<a href=\"/uni/all/";
                            echo "?page=".$pagination->total_pages();
                            echo "\">Последняя</a> ";
                        
                        if($pagination->has_next_page()) { 
                            echo "<a href=\"/uni/all/"; 
                            echo "?page=";
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
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        