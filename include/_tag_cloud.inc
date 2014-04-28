<?php
    $tagmap_temp = Tagmap::find_by_object_type('question');
    $tag_ids = array();
    if(!empty($tagmap_temp)){
        foreach($tagmap_temp as $temp){
            if(!in_array($temp->tag_id, $tag_ids)){
                $tag_ids[] = $temp->tag_id;
            }            
        }
    }
    if(!empty($tag_ids)):
    
    foreach($tag_ids as $key => $tag_id):
                        $tag_map = tagmap::find_by_object_type_tag_id('question', $tag_id);
                        if(!empty($tag_map)){
                            $result[$tag_id] = count($tag_map);
                        }
                        else{
                            $result[$tag_id] = 0;
                        }
    endforeach;
    arsort($result);
?>

<div class="section">
    <div class="section-title">
        <div class="left">Теги</div>
        <div class="clearer">&nbsp;</div>
    </div>
        <div class="section-content">
            <ul class="nice-list">
                <?php
                    
                        foreach($result as $tag_id => $total):
                        $tag_name = Tag::find_by_id($tag_id)->tag_name;
                ?>
            <li>
                <div class="left cloud">
                <p class="large nomargin nopadding">
                <a class="tag" href="/questions/tagged/<?php echo $tag_name?>/"><?php echo $tag_name?></a><span class="counter">
                                <span class="small counter_number"><?php echo $total?></span>
                            </span>
                </p>
                </div>
                <div class="clearer">&nbsp;</div>
            </li>
            <?php endforeach;?>

            
        </ul>
    </div>
    
</div>
<?php endif;?>