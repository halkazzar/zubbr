<?php
//  $questions is array of arrays
    $questions = Question::find_top_asked_universities();
    $top_asked_universities = array();
    $i = 0;
    
    if (!empty($questions)){
        foreach ($questions as $question_array){
        $top_asked_universities[$i] = University::find_by_id($question_array['category_object_id']);
        $i++;        
    }    
    }
    
    
?>

<div class="section">
    <div class="section-title">
        <div class="left">Топ обсуждаемые</div>
        <div class="right">Вопросов</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
            $i = 0; 
            if (!empty($top_asked_universities)):
    foreach ($top_asked_universities as $uni):
            ?>
            <li>
                <div class="left"><span class="quiet"><?php echo $i+1?></span>. <a href="/uni/<?php echo $uni->alias?>/"><?php echo $uni->short_name?></a></div>
                <div class="right"><?php echo $questions[$i]['COUNT( * )']; $i++; ?></div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
</div>