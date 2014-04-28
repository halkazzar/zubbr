<?php
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";



    $include_in_head = "\n<script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";
    $include_in_head .= '<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>';

    if (!empty($_GET['news_id']))
        $article = News::find_by_id($_GET['news_id']);
    else redirect_to('/');

    $tagmaps = Tagmap::news_get_tags($article->news_id);
    if (!empty($tagmaps))
        foreach ($tagmaps as $tagmap){
            $meta_keywords = ', ' . Tag::find_by_id($tagmap->tag_id)->tag_name;    
    }


    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe. ' ' .$article->title;

    $meta_description = strip_tags($article->body);
    $meta_keywords = 'новости образования' . $meta_keywords;

    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "main";

    if($_SERVER['HTTP_REFERER'] == 'http://www.zubbr.kz/student')
        $current_tab = "student";
    elseif($_SERVER['HTTP_REFERER'] == 'http://www.zubbr.kz/alumni')
        $current_tab = "alumni";
    else
        $current_tab = "abitur";

    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     


?>

<div class="main" id="main-two-columns_2">
    <div class="left" id="main-left">

        <div class="post">
            <div class="post-title"><h2><a href="#"><?php echo $article->title?></a></h2></div> 
            <div class="post-date"><?php $arr = date_translate($article->date_of_publish); echo $arr['hour'] . ':' . $arr['min'] . ', '. $arr['day_word'] .', '. $arr['day'] . ' '. $arr['month_word2']?></div> 
            <div class="post-body text-justified"> 

                <?php  //str_replace is a little hack
                    $content = str_replace('images/', '/images/', $article->avatar);
                    $content = str_replace('<img ', '<img class="bordered left" ', $content);
                    //$content = str_replace('<img class="bordered" style="float: left;"', '<img class="bordered left"', $content);
                    //$content = str_replace('<img class="bordered" style="float: right;"', '<img class="bordered right"', $content);
                    echo $content;

                    $content = str_replace('images/', '/images/', $article->description);
                    $content = str_replace('<img ', '<img class="bordered left" ', $content);
                    //$content = str_replace('<img class="bordered" style="float: left;"', '<img class="bordered left"', $content);
                    //$content = str_replace('<img class="bordered" style="float: right;"', '<img class="bordered right"', $content);
                    echo $content;

                    //displaying main part
                    $content = str_replace('images/', '/images/', $article->body);
                    $content = str_replace('<img ', '<img class="bordered left" ', $content);
                    //$content = str_replace('<img class="bordered" style="float: left;"', '<img class="bordered left"', $content);
                    //$content = str_replace('<img class="bordered" style="float: right;"', '<img class="bordered right"', $content);
                    echo $content;
                ?> 
            </div>

            <p>
                <?php
                    $uni_maps = News_uni::find_universities($article->news_id);
                    if(!empty($uni_maps)):
                        foreach($uni_maps as $map) :
                            $uni = University::find_by_id($map->university_id);
                        ?>

                        <span class="uni">
                            <a href="/uni/<?php echo $uni->alias?>/" title="Перейти в профайл <?php echo $uni->short_name?>"><?php echo $uni->short_name?></a>
                        </span>

                <?php endforeach; endif;?>
                        

                <span class="tag"> 
                    <?php
                        $tagmaps = Tagmap::news_get_tags($article->news_id);
                        if (!empty($tagmaps)):
                            foreach ($tagmaps as $tagmap):
                            ?>

                            <a href="/tag/<?php echo $tagmap->tag_id?>/"><?php echo Tag::find_by_id($tagmap->tag_id)->tag_name?></a>

                            <?php endforeach; else:?>
                        Без тегов
                        <?php endif;?>
                </span>
            </p>

            <div 
                class="addthis_toolbox addthis_default_style" 
                addthis:url='http://www.zubbr.kz/news/<?php echo $article->news_id?>/'
                addthis:title="<?php echo $article->title?>"
                addthis:description="<?php echo  strip_tags($content);?>">
                <a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_vk"></a>
                <a class="addthis_button_mymailru"></a>

            </div>
            
            <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>


            <div class="clearer">&nbsp;</div>
            <?php if (isset($_SERVER['HTTP_REFERER'])):?>
                <div class="right"><a href="<?php echo $_SERVER['HTTP_REFERER']?>" class="more">« Вернуться назад</a>
                    
            </div>
            <?php endif; ?>
        </div>
        <div class="content-separator"></div>

        <?php require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>

        <div class="clearer">&nbsp;</div>

    </div>

    <div class="right sidebar" id="sidebar-2">
    <?php
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/_banner_mini.inc";
    ?>
        <div class="section network-section">
            <div class="section-title">
                <div class="left"></div>
                <div class="right">Университеты</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/univers_abitur.inc";?>
            </div>
        </div>

        <div class="section network-section">
            <div class="section-title">
                <div class="left"></div>
                <div class="right">Тесты</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">
                <?php $test_mode = 'random'; require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_test.php";?>
            </div>
        </div>        

        <div class="section network-section">
            <div class="section-title">
                <div class="left"></div>
                <div class="right">Вопросы/Ответы</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">    
                <?php $question_mode = 'random';  
                    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";
                ?>
            </div>
        </div>
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        