<?php
    $all = Stats_UniversityView::find_all_promoted('none');
    $universities = array();
    $i = 0;
    
    if (!empty($all)){
        foreach ($all as $every){
        $universities[$i] = University::find_by_id($every->university_id);
        $i++;        
    }    
    }
    ?>

<div class="section">
    <div class="section-title">
        <div class="left">Топ искомые</div>
        <div class="right">Просмотров</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
            $i = 0; 
            if (!empty($universities)):
    foreach ($universities as $uni):
            ?>
            <li>
                <div class="left"><span class="quiet"><?php echo $i+1?></span>. <a href="/uni/<?php echo $uni->alias?>/"><?php echo $uni->short_name?></a></div>
                <div class="right"><?php echo $all[$i]->views; $i++; ?></div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
</div>