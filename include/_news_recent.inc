<?php

    $news = News::find_recent(5);
?>

<div class="section">
    <div class="section-title">
        <div class="left">Свежие новости</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
        <?php
                $i = 0; 
                if (!empty($news)):
                    foreach ($news as $article):
                    ?>
            <li>
                <div class="left"><span class="quiet"><?php echo ++$i?>. </span><a href="/news/<?php echo $article->news_id?>/"><?php echo $article->title?></a></div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>
            <?php endif;?>
            
        </ul>
    </div>
    
</div>