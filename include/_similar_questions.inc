<?php

    $tags = Tagmap::question_get_tags($current_id);
    if(!empty($tags)):
    
    $similar_q = array();
    foreach($tags as $tag){
        if(!empty($tag)){
            $arr = Question::find_by_tag($tag->tag_id);
            foreach($arr as $q){
             if(!in_array($q, $similar_q)){
                 $similar_q[] = $q;
             }   
            }
        }
    }
?>

<div class="section">
    <div class="section-title">
        <div class="left">Похожие вопросы</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
        <?php
                $i = 0; 
                if (!empty($similar_q)):
                    foreach ($similar_q as $question):
                    ?>
            <li>
                <div class="left"><span class="quiet"><?php echo ++$i?>. </span><a href="/questions/<?php echo $question->question_id?>/"><?php echo $question->question_body?></a></div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>
            <?php endif;?>
            
        </ul>
    </div>
    
</div>
<?php endif;?>