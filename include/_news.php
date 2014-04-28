<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
?>

<?php
    if(!empty($_GET['ajax'])){
        if($_GET['invoker'] == 'news'){

            $tag_id = $_GET['tag_id'];

            if(Tag::find_by_id($tag_id)->group_id != 0){
                $syn_tags = Tag::find_by_group_id(Tag::find_by_id($tag_id)->group_id);
            }
            else{
                $syn_tags[] = Tag::find_by_id($tag_id);
            }

            $news = array();

            foreach($syn_tags as $syn_tag){
                $temp_n = Tagmap::find_by_object_type_tag_id('news', $syn_tag->tag_id);
                if(!empty($temp_n)){
                    foreach ($temp_n as $tagmap_n) {
                        if(!in_array(news::find_by_id($tagmap_n->object_id), $news)){
                            $news[] = news::find_by_id($tagmap_n->object_id);
                        }
                    }
                }
            }

            if(ctype_digit($_GET['first']) && ctype_digit($_GET['last'])){
                $per_page = $_GET['last'] - $_GET['first'] + 1;
                $page = $_GET['last'] / $per_page;

                $total = count($news);
                $pagination = new Pagination($page, $per_page, $total);

                $news =  array_slice($news, $pagination->offset(), $per_page);    
            }






            header('Content-Type: text/xml');
            echo '<data>';
            echo '<total>' . $total . '</total>';
        }
    }

?>

<?php
    if (!empty($news)):
        foreach ($news as $article) :?>
        <?php if(!empty($_GET['ajax'])) echo '<result><res_id>'.$article->news_id.'</res_id><res_data><![CDATA[';?>
        <div class="post">
            <div class="post-title"><h2><a href="/news/<?php echo $article->news_id?>/"><?php echo $article->title?></a></h2></div> 
            <div class="post-date"><?php $arr = date_translate($article->date_of_publish); echo $arr['hour'] . ':' . $arr['min'] . ', '. $arr['day_word'] .', '. $arr['day'] . ' '. $arr['month_word2']?></div> 
            <div class="post-body text-justified">
                <?php
                    $content = str_replace('images/', '/images/', $article->avatar);
                    $content = str_replace('<img ', '<img class="bordered left "', $content);
                    echo $content;

                    $content = str_replace('images/', '/images/', $article->description);
                    $content = str_replace('<img ', '<img class="bordered left "', $content);
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

                        <?php endforeach;
                        endif;?>


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
                addthis:description="<?php echo strip_tags($content) ;?>">
                <a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_vk"></a>
                <a class="addthis_button_mymailru"></a>

            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>
            <!-- AddThis Button END --> 

            <div class="clearer">&nbsp;</div> 
            <div class="right"><a href="/news/<?php echo $article->news_id?>/" class="more" title="Читать «<?php echo $article->title?>»">Полностью &#187;</a>
            </div>
        </div> 
        <div class="content-separator"></div>
        <?php if(!empty($_GET['ajax'])) echo ']]></res_data></result>';?>
        <?php endforeach;?>
    <?php endif;?>
<?php if(!empty($_GET['ajax'])) echo '</data>';?>         
            