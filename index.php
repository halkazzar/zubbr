<?php
   require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    
                          


    //Navigation block
    $sub_navigation = "main";
    if (!empty ($_GET['section'])){
        if ($_GET['section'] == 'abitur')  {$current_tab = "abitur";  $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' В зуббр '.$pipe.' Абитуриент';}
        elseif ($_GET['section'] == 'student') {$current_tab = "student"; $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' В зуббр '.$pipe.' Студент';} 
        elseif ($_GET['section'] == 'alumni')  {$current_tab = "alumni";  $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' В зуббр '.$pipe.' Выпускник';} 
    }
    else {$current_tab = "abitur"; $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' В зуббр '.$pipe.' Абитуриент';}
    
    
    
    
    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    
    
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/registration_index.js' charset='utf-8'></script>";
    $include_in_head .= '<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>';

    if($current_tab != "abitur"){
        $include_in_head .= "\n <script type='text/javascript' src='/javascripts/jobslider.js' charset='utf-8'></script>";
    } 
    

    if($current_tab == "abitur"){
        $meta_keywords = 'ягиик, ягик, образование за границей, образование за рубежом, образование сша, обучение, обучение в китае, обучение в сша, специальное образование за рубежом, современное образование за рубежом, высшее образование, история казахстана';
        $meta_description = 'Узнай как получить грант в РК, подбери для себя ВУЗ, сдай ЕНТ на отлично';
    }
    elseif($current_tab == "student"){
        $meta_keywords = 'ягиик, ягик, bolashak, магистратура за границей, магистратура за рубежом, сша обучение, учеба за границей, учеба за рубежом, второе высшее, работа для студентов';
        $meta_description = 'Узнай как сдать сессию, найди работу на пол дня, планируй карьеру';
    }elseif($current_tab == "alumni"){
        $meta_keywords = 'ягиик, ягик, ассоциация, выпускник, болашак, работа';
        $meta_description = 'Узнай как поступить по болашаку, вступай в ассоциации, планируй карьеру';
    }

    
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }else{
        $current_page = 1;
    }
    $per_page = 4;
    $total_news = News::count_all();
    $pagination = new Pagination($current_page, $per_page, $total_news);


    $news = News::find_recent($per_page, $pagination->offset());


?>

<div class="main" id="main-two-columns_2">
    <div class="left" id="main-left">
        <div class="post">
        
           <?php
           require($_SERVER['DOCUMENT_ROOT'] . "/include/_news.php");
           ?>
            <div class="post-meta archive-pagination">
                <?php
                    if($pagination->total_pages() > 1) {
                        if($pagination->has_previous_page()) { 
                            echo "<a href=\"/" . $current_tab;
                            echo "/page_";
                            echo $pagination->previous_page();
                            echo "/";
                            echo "\">&laquo; Назад</a> "; 
                        } 

                        echo "<a href=\"/" . $current_tab;
                            echo "/page_1/";
                            echo "\">Первая</a> ";
                        
                        for($i=1; $i <= $pagination->total_pages(); $i++) {
                            if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                            if($i == $current_page) {
                                echo " <span class=\"selected\">{$i}</span> ";
                            } else {
                                echo "<a href=\"/" . $current_tab;
                                echo "/page_";
                                echo $i;
                                echo "/";
                                echo "\">{$i}</a> ";  
                            }    
                            }
                            
                            
                            
                        }
                        echo "<a href=\"/" . $current_tab;
                            echo "/page_".$pagination->total_pages()."/";
                            echo "\">Последняя</a> ";
                            
                        if($pagination->has_next_page()) { 
                            echo "<a href=\"/" . $current_tab;
                            echo "/page_";
                            echo $pagination->next_page();
                            echo "/"; 
                            echo "\">Вперед &raquo;</a> ";
                        }
                        
                        
                    }
                ?>
            </div>
        </div>
        <div class="clearer">&nbsp;</div>

    </div>

    <div class="right sidebar" id="sidebar-2">
        <?php if ($current_tab == 'abitur'):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right"><a href="/uni/">Университеты</a></div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/univers_abitur.inc";?>
                </div>
            </div>

            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right"><a href="/test/">Тесты</a></div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">

                    <?php $test_mode = 'random'; require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_test.php";?>
                </div>
            </div>        
            <?php endif;?>

        <div class="section network-section">
            <div class="section-title">
                <div class="left"></div>
                <div class="right"><a href="/questions/">Вопросы/Ответы</a></div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">    
                <?php 
                    if ($current_tab == 'abitur') {
                        //$question_mode = 'random_answered';  
                        $question_mode = 'random_answered';  
                    }          
                    elseif($current_tab == 'student') {
                        if ($session->is_logged_in()){
                            $que_user_id  = $session->user_id;
                            $question_mode = 'random_for_stud';
                        }
                        else{
                            $question_mode = 'random';    
                        }
                    }  
                    elseif ($current_tab == 'alumni') {
                        if ($session->is_logged_in()){
                            $que_user_id  = $session->user_id;
                            $question_mode = 'random_for_alumni';

                        }
                        else $question_mode = 'random';

                    }  
                    require $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";?>          
            </div>
        </div>

        <?php if ($current_tab == 'alumni'):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Ассоциации</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">    
                    <?php
                        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/organizations_alumni.inc"; 
                    ?>          
                </div>
            </div>
            <?php endif;?>

        <?php if ($current_tab != 'abitur'):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right"><a href="/job/">Предложения о работе</a></div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <div id="jobcarousel" class="jobcarousel jcarousel-skin-ie7">
                        <ul> 
                            <!-- The content will be dynamically loaded in here --> 
                        </ul> 
                    </div>
                </div>
            </div>
            <?php endif;?> 
    </div>
    <div class="clearer">&nbsp;</div>
    </div>
        
        
<?php require $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
