<?php
    /**
    * Zubr
    * Question listing asking block
    * To use it properly 
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';

    
//    $url = 'http://jooble.kz/rss/RssHandler.ashx?id=23';
//    $limit       = 3; // nombre d'actus à afficher
//    $rss = new lastRSS;
//    $rss->cache_dir   = '/core/cache';
//    
//    $rss->cache_time  = 3600;      // fréquence de mise à jour du cache (en secondes)
//    $rss->date_format = 'd/m';     // format de la date (voir fonction date() pour syntaxe)
//    $rss->CDATA       = 'content'; // on retire les tags CDATA en conservant leur contenu

//    
//    if ($rs = $rss->get($url)) 
//    {
//    for($i=0;$i<$limit;$i++)
//    {
//    
//    echo '<strong>'.$rs['items'][$i]['pubDate'].'</strong> &middot; <a href="'.$rs['items'][$i]['link'].'">'.$rs['items'][$i]['title'].'</a><br />';
//    }
//    }
//    else 
//    {
//     echo ('Flux RSS non trouvé');
//    }
    if(isset($_GET['invoker'])){
        if($_GET['invoker'] == 'job'){
            if(ctype_digit($_GET['elementsPerPage']) && ctype_digit($_GET['actualPage'])){
            $postings = jobposting::find_recent($_GET['elementsPerPage'], $_GET['actualPage']);    
            }
        }
    }
    else{
        $postings = jobposting::find_recent(4);
    }
    
    
    
    
    ?>

<div>
    <?php
        $index = 0;
            if (!empty($postings))foreach ($postings as $post) :?>
            <p class="result">
            <div class="post">
                <div class="post-title"><h2><a href="/job/<?php echo $post->jobposting_id?>/"><?php echo $post->title?></a></h2></div> 
                <div class="post-date"><?php $arr = date_translate($post->date_of_publish); echo $arr['hour'] . ':' . $arr['min'] . ', '. $arr['day_word'] .', '. $arr['day'] . ' '. $arr['month_word2']?></div> 
                <div class="post-body text-justified"> 
                    <?php 
                    //$content = str_replace('images/', '/images/', $post->body);
                    //$content = str_replace('<img ', '<img class="bordered left"', $content);
                    //echo $content
                    
                    //$content = strip_tags($post->body);
                    $content = n_words($post->body, 20);
                    echo close_dangling_tags($content);
                    ?> 
                </div> 
                <div class="clearer">&nbsp;</div>
                <div class="right"><a href="/job/<?php echo $post->jobposting_id?>/" class="more" title="Читать «<?php echo $post->title?>»">Полностью &#187;</a>
                </div>
            </div>
            </p> 
            <div class="content-separator"></div>
            <?php endforeach;?>
    </div>
