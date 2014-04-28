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
                        <span class="quiet"><?php echo ++$i?>. </span><a href="/users/<?php echo $user->login?>/" ><?php echo $user->login?></a>
                    </li>
                    <?php endforeach;?>
                <li>
                    <a href="/users/" class="more">Зал славы &#187;</a>
                </li>
                <?php endif;?>
        </ul>
    </div>
</div>