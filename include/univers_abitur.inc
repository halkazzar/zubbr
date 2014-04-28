
<?php

    $random = 2;
    $temp = $random;
    
    if(empty($university_mode)){
        $uni_list = University::find_random($random);    
    }
    else{
        if(count($universities) >= 2){
            $uni_list = array();
            $uni_list[] = array_pop($universities);
            $uni_list[] = array_pop($universities);
        }
        else{
            $uni_list = $universities;
        }
        
    }
    
    if (!empty($uni_list)): 
        foreach ($uni_list as $uni):
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
            
            if ($temp != $random) echo '<div class="university-separator"></div>'
        ?>        

        <div class="section-university">
            <div class="post"> 
                <p><a href="/uni/<?php echo $uni->alias ?>/"><img src="/images/universities/avatars/<?php echo $uni->alias . $uni->picture_extension ?>" alt="Фото <?php echo $uni->short_name?>" class="left bordered" /></a></p> 
                <h3><a href="/uni/<?php echo $uni->alias ?>/"><?php echo $uni->short_name?></a><?php if($loc_id != -1):?><a href="/uni/search/?location_value=<?php echo $loc_id?>"><img class="flag" src="/images/locations/<?php echo $loc_id?>.gif" alt="<?php $loc = Location::find_by_id($loc_id); if(!empty($loc)) echo $loc->location_name ?>"/></a><?php endif;?></h3>
                <p><?php echo $uni->short_description?></p>
                <div class="toolbar">
                    <a class="like" href="/uni/like/<?php echo $uni->alias?>/" title="Добавить <?php echo $uni->short_name?> в профайл"><img src="/images/icon-plus.gif" width="20" height="20" alt="" /> я хочу тут учиться</a>    
                </div> 
                <div class="clearer">&nbsp;</div> 
            </div>
        </div>   <?php            
            $temp--;    
            endforeach;
        endif;

?>