<?php
    if ($session->is_logged_in()):
    
    //$universities_i_want_to_study_in = Relation::find_iwantostudy_universities($session->user_id);    
    $relations = Relation::find_whowantstostudywithme_universities($session->user_id);
?>

<div class="section">
    <div class="section-title">
        <div class="left">Вместе с вами хотят учиться</div>
        <div class="right"></div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
                $i = 0; 
                if (!empty($relations)):
                    foreach ($relations as $relation):
                    
                    $user = User::find_by_id($relation->user_id);
                    $uni  = University::find_by_id($relation->university_id);
                    ?>
                    <li>
                        <div class="col3-avatar-top left"> 
                            
                            <a href="/users/<?php echo $user->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $user->usr_id . '.jpg')) {echo $user->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                        </div>
                        <div class="col3-mid-body2 left">
                            <a href="/users/<?php echo $user->login?>/" class = "largest"><?php echo $user->login?></a>
                            <p class="nomargin">Хочет учиться в <a href="/uni/<?php echo $uni->alias?>/"><?php echo $uni->short_name?></a></p> 
                            
                            
                        </div>

                        <div class="right"></div>
                        <div class="clearer">&nbsp;</div>
                    </li>
                    <?php endforeach;?>
                <?php endif;?>
        </ul>
    </div>
</div>
<?php endif;?>