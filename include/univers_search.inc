<?php
    if(!empty($_GET['range_min'])) $range_min = $_GET['range_min']; else $range_min = 0;
    if(!empty($_GET['range_max'])) $range_max = $_GET['range_max']; else $range_max = 100000000;

    if(!empty($_GET['location_value'])){
        for($depth = 1; $depth <= 3; $depth++){
            if (empty($universities))
                $universities = University::search_by_keyword($keyword, $depth, $_GET['location_value'], $range_min, $range_max, $per_page, $pagination->offset());
        }
    } else {
        $universities = University::search_by_keyword($keyword, 2, -1, $range_min, $range_max, $per_page, $pagination->offset());
    }

    $temp = $total_universities;

    if (!empty($universities)):
    ?>        
    <div class="section">
        <div class="section-title">
            <div class="left">Результаты поиска</div>
            <div class="right">Найдено: <?php echo $total_universities?></div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="section-content">
            <?php     foreach ($universities as $uni):
                      if ($temp != $total_universities) echo '<div class="university-separator"></div>';
                      
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
                    <a href="/uni/<?php echo $uni->alias ?>/"><img src="/images/universities/avatars/<?php echo $uni->alias . $uni->picture_extension ?>" alt="Фото <?php echo $uni->short_name?>" class="left bordered" /></a> 
                    <h3><a href="/uni/<?php echo $uni->alias ?>/"><?php echo $uni->short_name?></a><?php if($loc_id != -1):?><a href="/uni/search/?location_value=<?php echo $loc_id?>"><img class="flag" src="/images/locations/<?php echo $loc_id?>.gif" alt="<?php $loc = Location::find_by_id($loc_id); if(!empty($loc)) echo $loc->location_name ?>"/></a><?php endif;?></h3> 
                    <p><?php echo $uni->short_description?></p>

                    <div class="toolbar" >
                        <a href="/uni/like/<?php echo $uni->alias?>/" title="Добавить <?php echo $uni->short_name?> в профайл"><img src="/images/icon-plus.gif" width="20" height="20" alt="" /> я хочу тут учиться</a>    
                    </div> 
                    <div class="clearer">&nbsp;</div> 
                </div>
                <?php $temp--;
                endforeach;?>
        </div>
    </div>  
    <?php            
            

        endif;

?>