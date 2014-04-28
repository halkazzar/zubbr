<?php

    $relations = Relation::find_iwantostudy_universities();
    $iwantostudy_universities = array();
    $i = 0;
    
    if (!empty($relations)){
        foreach ($relations as $relation_array){
        $iwantostudy_universities[$i] = University::find_by_id($relation_array['university_id']);
        $i++;        
    }    
    }
    
    
?>

<div class="section">
    <div class="section-title">
        <div class="left">Где хотят учиться</div>
        <div class="right">Желающих</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
            $i = 0; 
            if (!empty($iwantostudy_universities)):
    foreach ($iwantostudy_universities as $uni):
            ?>
            <li>
                <div class="left"><span class="quiet"><?php echo $i+1?></span>. <a href="/uni/<?php echo $uni->alias?>/"><?php echo $uni->short_name?></a></div>
                <div class="right"><?php echo $relations[$i]['COUNT( * )']; $i++; ?></div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
</div>