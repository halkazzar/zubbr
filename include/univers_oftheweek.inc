<?php
    $promotions = Stats_UniversityView::find_all_promoted('free');
    $promoted_universities = array();
    $i = 0;

    if (!empty($promotions)){
        foreach ($promotions as $promo){
        $promoted_universities[$i] = University::find_by_id($promo->university_id);
        $i++;        
    }    
    }
    
    if (!empty($promoted_universities)):
    ?>        
<div class="section">
    <div class="section-title">
        <div class="left">Университет недели</div>
        <div class="right"></div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
    <?php     
    $i = 0;
    foreach ($promoted_universities as $uni):
    
    $cycle = 0;
            $loc_id = $uni->location_id;
            while(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/locations/'.$loc_id . '.gif')){
                $loc_id = Location::find_by_id($loc_id)->parent_id;
                if($cycle < 3){
                    $cycle++;
                }else{
                    $loc_id='-1';
                    break;
                }
            }
    ?>
        <div class="post"> 
        <a href="/uni/<?php echo $uni->alias ?>/free_id=<?php echo $promotions[$i]->stats_university_views_id?>/"><img src="/images/universities/avatars/<?php echo $uni->alias . $uni->picture_extension ?>" alt="Фото <?php echo $uni->short_name?>" class="left bordered" /></a> 
        <h3><a href="/uni/<?php echo $uni->alias ?>/free_id=<?php echo $promotions[$i]->stats_university_views_id?>/"><?php echo $uni->short_name . " (FREE)"?></a><?php if($loc_id != -1):?><a href="/uni/search/?location_value=<?php echo $loc_id?>"><img class="flag" src="/images/locations/<?php echo $loc_id?>.gif" alt="<?php $loc = Location::find_by_id($loc_id); if(!empty($loc)) echo $loc->location_name ?>"/></a><?php endif;?></h3> 
        <p><?php echo $uni->short_description?></p>
        
        <div class="toolbar" >
                    <a href="/uni/like/<?php echo $uni->alias?>/" title="Добавить <?php echo $uni->short_name?> в профайл"><img src="/images/icon-plus.gif" width="20" height="20" alt="" /> я хочу тут учиться</a>    
                </div> 
        <div class="clearer">&nbsp;</div> 
    </div>
    </div>
    </div>  
    <?php
    $i++;            
   //     $temp--;    
    endforeach;
    endif;

?>
