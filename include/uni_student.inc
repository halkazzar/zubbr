<?php
    /**
    * Zubr
    * student's block
    * To use it properly
    * $relation_university_id needed 
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
    $students = Relation::find_by_role("student", $relation_university_id, 3);   
    $total = count(Relation::find_by_role("student", $relation_university_id, -1));   
    if (!empty($students)):
    ?>

<div class="section">
    <div class="section-title">
        <div class="left">Студенты</div>
        <?php if($total > 3):?>
        <div class="right">Всего: <?php echo $total?></div>
        <?php endif;?>
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="section-content">
        <ul class="nice-list-alumni" id="students">
            <?php
                $index = 0;
                if (!empty($students)) foreach($students as $student):
                $student_user = User::find_by_id($student->user_id);
                    ?>
                    <li id="student-<?php echo $index++;?>">
                        <div class="col3-avatar-top left"> 
                            <a href="/users/<?php echo $student_user->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $student_user->usr_id . '.jpg')) {echo $student_user->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                        </div>
                        <div class="col3-mid-body2 left">
                            <?php 
                        if (!empty($student_user->first_name) && !empty($student_user->last_name)):
                        ?>
                            <h6><a href="/users/<?php echo $student_user->login?>/"><?php echo $student_user->first_name . ' ' . $student_user->last_name ?></a></h6> 
                        <?php
                        else:
                        ?>
                        <h6><a href="/users/<?php echo $student_user->login?>/"><?php echo $student_user->login ?></a></h6> 
                        <?php
                        endif;
                        ?>
                            
                            <h6><?php echo Degree::find_by_id($student->degree_id)->title . ' ' . Studyarea::find_by_id($student->studyarea_id)->title?></h6> 
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