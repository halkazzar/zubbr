<?php
    /**
    * Zubr
    * Alumni's block
    * To use it properly
    * $relation_university_id needed 
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
    $alumnis = Relation::find_by_role("alumni", $relation_university_id, 5);   
    if (!empty($alumnis)):
    ?>

<div class="section">
    <div class="section-title">
        <div class="left">Выпускники</div>
        <?php
        //<div class="right"><a href="#">Все выпускники</a></div>
        ?>
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="section-content">
        <ul class="nice-list-alumni" id="alumnis">
            <?php
                $index = 0;
                if (!empty($alumnis)) foreach($alumnis as $alumni):
                $alumni_user = User::find_by_id($alumni->user_id);
                    ?>
                    <li id="alumni-<?php echo $index++;?>">
                        <div class="col3-avatar-top left"> 
                            <a href="/users/<?php echo $alumni_user->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $alumni_user->login . '.jpg')) {echo $alumni_user->login . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                        </div>
                        <div class="col3-mid-body2 left">
                            <h6><a href="/users/<?php echo $alumni_user->login?>/"><?php echo $alumni_user->first_name . ' ' . $alumni_user->last_name ?></a></h6> 
                            <h6><?php echo Degree::find_by_id($alumni->degree_id)->title . ' ' . Studyarea::find_by_id($alumni->studyarea_id)->title?></h6> 
                        </div>
                        <?php
                        //<div class="col3-mid-body3 right"> 
//                            <a href="#" class= "closeButton"><img src="/images/icon-cross.gif" alt="" /></a>
//                        </div>
                        
                        ?>
                        <div class="clearer">&nbsp;</div>
                    </li>
                    <?php endforeach;?>
        </ul>
    </div>
</div>
<?php endif;?>