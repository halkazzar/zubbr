<?php

    $answers = Answer::find_top_answered_users();
    $top_answered_users = array();
    $i = 0;

    if (!empty($answers)){
        foreach ($answers as $answer_array){
            $top_answered_users[$i] = User::find_by_id($answer_array['user_id']);
            $i++;        
        }    
    }

?>

<div class="section">
    <div class="section-title">
        <div class="left">Топ контрибуторов</div>
        <div class="right"></div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
                $i = 0; 
                if (!empty($top_answered_users)):
                    foreach ($top_answered_users as $user):
                    ?>
                    <li>
                        <div class="col3-avatar-top left"> 
                            <a href="/users/<?php echo $user->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $user->usr_id . '.jpg')) {echo $user->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                        </div>
                        <div class="col3-mid-body2 left">
                            <a href="/users/<?php echo $user->login?>/" class = "largest"><?php echo $user->login?></a>
                            <!--<p class="nomargin"><a href="/users/<?php echo $user->login?>/"><?php echo $user->first_name . ' ' . $user->last_name?></a></p> 
                            --><p class=" nomargin">Ответов: <?php echo $answers[$i]['COUNT( * )']; $i++; ?></p>
                        </div>

                        <div class="right"></div>
                        <div class="clearer">&nbsp;</div>
                    </li>
                    <?php endforeach;?>
                <?php endif;?>
        </ul>
    </div>
</div>