<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head .= '<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>';
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/newsslider.js'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/testslider.js'></script>";


    if(isset($_GET['tag_id'])){
        if(is_numeric($_GET['tag_id'])){
            $tag_id = $_GET['tag_id'];
            $sub_navigation = "main";
            $current_tab = "tag";
            $current_nav_link = 'tag'; 
        }
        elseif($_GET['tag_id']== 'grants'){
            $tag_id = 100; 
            $sub_navigation = "grants";
            $current_tab='all';   
        }
    }



    if(Tag::find_by_id($tag_id)->group_id != 0){

        $syn_tags = Tag::find_by_group_id(Tag::find_by_id($tag_id)->group_id);

    }
    else{

        $syn_tags[] = Tag::find_by_id($tag_id);

    }
    $questions = array();
    $news = array();
    $tests = array();
    $universities = array();

    foreach($syn_tags as $syn_tag){
        $meta_keywords = ', ' . $syn_tag->tag_name;
        $temp_n = Tagmap::find_by_object_type_tag_id('news', $syn_tag->tag_id);
        if(!empty($temp_n)){
            foreach ($temp_n as $tagmap_n) {
                if(!in_array(news::find_by_id($tagmap_n->object_id), $news)){
                    $news[] = news::find_by_id($tagmap_n->object_id);
                }
            }
        };

        if($_GET['tag_id']== 'grants'){
            if(!empty($news)){
                foreach($news as $grant_news){
                    $unis_grant = news_uni::find_universities($grant_news->news_id);
                    if(!empty($unis_grant)){
                        //print_r($unis_grant);
                        foreach($unis_grant as $uni){
                            $uni_to_add = university::find_by_id($uni->university_id);
                            if(!empty($uni_to_add)){
                                $universities[] = $uni_to_add;    
                            }
                        }
                    }
                }
            }
            //print_r($universities);
        }

        $temp_q = Tagmap::find_by_object_type_tag_id('question', $syn_tag->tag_id);
        if(!empty($temp_q)){
            foreach ($temp_q as $tagmap_q) {
                if(!in_array(Question::find_by_id($tagmap_q->object_id), $questions)){
                    $questions[] = Question::find_by_id($tagmap_q->object_id);
                }
            }
        };
        
        //$temp_q = Tagmap::find_by_object_type_tag_id('question', $syn_tag->tag_id);
//        if(!empty($temp_q)){
//            foreach ($temp_q as $tagmap_q) {
//                print_r($questions);
//                echo '<br />';
//                if(!in_array(question::find_by_id($tagmap_q->object_id), $questions)){
//                    $questions[] = question::find_by_id($tagmap_q->object_id);
//                }
//                print_r($questions);
//                echo '<br />';
//            }
//            
//        };

        if($_GET['tag_id']== 'grants'){
            $questions_grants = question::find_string('грант');
            if(!empty($questions_grants)){
                foreach($questions_grants as $que_grant){
                    if(!in_array($que_grant, $questions)){
                        $questions[] =  $que_grant;   
                    }
                }    
            }
        }

        $temp_t = Tagmap::find_by_object_type_tag_id('test', $syn_tag->tag_id);
        if(!empty($temp_t)){
            foreach ($temp_t as $tagmap_t) {
                if(!in_array(test::find_by_id($tagmap_t->object_id), $tests)){
                    $tests[] = test::find_by_id($tagmap_t->object_id);
                }
            }
        };

    }

    //print_r($news);


    // uasort – сортирует массив, используя пользовательскую функцию dateSort
    uasort($news,"dateSort");
    //print_r($arr);


    if(!empty($news)) {
        $tag_page_mode = 3;
    }
    else{
        $tag_page_mode = 2;
    }


    $tag_name = tag::find_by_id($tag_id)->tag_name;
    //Navigation block
    if($sub_navigation == "grants"){
        $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Гранты';
        $meta_keywords = 'бесплатная учеба за границей, бесплатная учеба за рубежом, бесплатное образование за рубежом, бесплатное обучение за границей, бесплатное обучение за рубежом, 
болашак, болашак стипендия, гранты на обучение, исследовательские гранты, образование за рубежом бесплатно, обучение за рубежом бесплатно, стипендия болашак, учеба за границей бесплатно, учеба за рубежом бесплатно, учиться за границей бесплатно';
        $meta_description = 'Узнайте как отучится за рубежом бесплатно. Все гранты на Зуббр.кз';
    }
    else{
        $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Все по тегу '. $tag_name;
        $meta_keywords = 'тег'.$meta_keywords;
        $meta_description = 'Вся информация с Зуббр.кз по тегу '. $tag_name;

    }
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

?>

<div class="main" id="main-two-columns_2">
    <div class="left" id="main-left">
        <?php if($tag_page_mode == 3 && !empty($news)):?>
            <div class="post">
                <div class="section-content">
                    <div id="newscarousel" class="newscarousel jcarousel-skin-ie7">
                        <ul id=<?php echo $tag_id?>> 
                            <!-- The content will be dynamically loaded in here -->
                            <?php //require($_SERVER['DOCUMENT_ROOT'] . "/include/_news.inc");?> 
                        </ul> 
                    </div>
                </div>
            </div>
            <?php endif;?>

        <?php if($tag_page_mode == 2 && !empty($tests)):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Тесты</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <div id="testcarousel" class="testcarousel jcarousel-skin-ie7">
                        <ul id=<?php echo $tag_id?>> 
                            <!-- The content will be dynamically loaded in here -->
                            <?php //require($_SERVER['DOCUMENT_ROOT'] . "/include/_test.inc");?> 
                        </ul> 
                    </div>    
                </div>
            </div>
            <?php endif;?>

        <div class="clearer">&nbsp;</div>

    </div>

    <div class="right sidebar" id="sidebar-2">
    <?php
    if($sub_navigation == "grants"){
        require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/_banner_mini.inc";
    }
    ?>
        <?php if($tag_page_mode == 3 && !empty($universities)):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Гранты в вузах</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <?php
                        $university_mode = 'ready';
                        require($_SERVER['DOCUMENT_ROOT'] . "/include/univers_abitur.inc");
                    ?>    
                </div>
            </div>
            <?php endif;?>

        <?php if($tag_page_mode == 3 && !empty($tests)):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Тесты</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <div id="testcarousel" class="testcarousel jcarousel-skin-ie7">
                        <ul id=<?php echo $tag_id?>> 
                            <!-- The content will be dynamically loaded in here -->
                            <?php //require($_SERVER['DOCUMENT_ROOT'] . "/include/_test.inc");?> 
                        </ul> 
                    </div>    
                </div>
            </div>
            <?php endif;?>

        <?php if($tag_page_mode == 2 && !empty($questions)):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Вопросы/Ответы</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <?php
                        $question_mode = 'ready';
                        require($_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc");
                    ?>    
                </div>
            </div>
            <?php endif;?>

        <?php if($tag_page_mode == 3 && !empty($questions)):?>
            <div class="section network-section">
                <div class="section-title">
                    <div class="left"></div>
                    <div class="right">Вопросы/Ответы</div>
                    <div class="clearer">&nbsp;</div>
                </div>

                <div class="section-content">
                    <?php
                        $question_mode = 'ready';
                        require($_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc");
                    ?>    
                </div>
            </div>
            <?php endif;?>
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        
