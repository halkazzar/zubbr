<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    $questions = Question::find_unanswered();
    
    
    $answers = Answer::find_unsent();
    $sent_answers = Answer::find_sent(10);
    $universities = University::find_all(0, -1, 'draft');
    $users     = User::find_recent_users(10);
    $odd_row = 0; 
?>  

<div id="main">
    <div>
    <div class="dashboard_wrapper">
        <form name="university" action="/admin/page/universities/" enctype="multipart/form-data" class="jNice" method="Post">
            <h3>Новые ВУЗы: <?php if (empty($universities)) echo "(0)"?></h3>
            <table cellpadding="0" cellspacing="0" class="dashboard">
                <?php if (!empty($universities)):?>
                    <?php foreach ($universities as $uni):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td>
                                <span class="main">
                                    <?php echo mb_substr($uni->long_name, 0, 50) . '...'?>
                                </span><br />
                                <span class="meta">
                                    <?php echo $uni->short_name;?>
                                </span><br />
                            </td>
                            <td class="action">
                                <span class="meta">
                                    <a href="/admin/page/universities/<?php echo $uni->university_id?>/merge/" >Объединить</a><br />
                                    <a href="/admin/page/universities/<?php echo $uni->university_id?>/" class="edit">Редактировать</a><br />
                                    <a href="#" class="delete_university" id="<?php echo $uni->alias?>">Удалить</a>
                                </span>
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>
                    <?php endif;?>                       
            </table>



        </form>
    </div>
    <div class="dashboard_wrapper">
        <h3>Последние 10 юзеров, (всего <?php echo User::count_all()?>; онлайн <?php echo $session->get_online_users()?> )</h3>
        <div >
            <table cellpadding="0" cellspacing="0" class="dashboard">
                <?php if (!empty($users)) foreach ($users as $user):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><span class="main">
                                    <a href="/users/<?php echo $user->login;?>/" target="_blank"><?php echo $user->login;?></a>
                                <?php   if($user->came_from_facebook == 1) echo ' (fb)';
                                        elseif($user->came_from_vkontakte == 1) echo ' (vk)';
                                        elseif($user->came_from_mailru == 1) echo ' (m.ru)';
                                        else{echo '(reg)';};
                                        ?>
                                </span>
                            </td>
                            <td><span>
                                    <?php echo date("d.m.Y", strtotime($user->date_of_join))?>
                                
                                </span>
                            </td>
                            <td class="action">
                                <span class="meta">
                                    <a href="/admin/page/users/<?php echo $user->login?>/" class="edit">Редактировать</a>
                                </span>
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>
        </div>
    </div>
    </div>
<div class="clear">&nbsp;</div> 
    <div>
    <div class="dashboard_wrapper">

        <form name="questions" action="/admin/page/questions/" enctype="multipart/form-data" method="Post">
            <h3>Неразосланные вопросы:</h3>
            <div>
                <table cellpadding="0" cellspacing="0" class="dashboard">
                    <?php if (!empty($questions)) foreach ($questions as $question):
                    if($question->sent_date =='0000-00-00 00:00:00'):
                    ?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><span class="main"><?php echo $question->question_body;?></span><br /><br />
                                <span class="meta">
                                    <?php if ($question->question_category == 'university') echo 'ВУЗ: '. University::find_by_id($question->category_object_id)->short_name;
                                        if ($question->question_category == 'location') echo Location::find_by_id($question->category_object_id)->location_name;
                                        if ($question->question_category == 'scholarship') echo Scholarship::find_by_id($question->category_object_id)->title;
                                    ?>
                                </span>
                                <br />
                                <span class="meta">
                                    <?php 
                                           echo 'НОВЫЙ ВОПРОС';    
                                    
                                    ?>
                                </span>

                                <td class="action">
                                    <span class="meta">
                                        <a href="/admin/page/send_question/<?php echo $question->question_id?>/" class="view">Разослать</a><br />
                                        <a href="/admin/page/questions/<?php echo $question->question_id?>/" class="edit">Редактировать</a><br />
                                        <a href="#" class="delete_question" id="<?php echo $question->question_id?>">Удалить</a><br />
                                    </span>
                                </td>
                            </tr>                        
                            <?php $odd_row++; endif; endforeach;?>                       
                </table>
            </div>
            
            <h3>Неотвеченные вопросы:</h3>
            <div>
                <table cellpadding="0" cellspacing="0" class="dashboard">
                    <?php if (!empty($questions)) foreach ($questions as $question):
                    if($question->sent_date != '0000-00-00 00:00:00'):
                    ?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><span class="main"><?php echo $question->question_body;?></span><br /><br />
                                <span class="meta">
                                    <?php if ($question->question_category == 'university') echo 'ВУЗ: '. University::find_by_id($question->category_object_id)->short_name;
                                        if ($question->question_category == 'location') echo Location::find_by_id($question->category_object_id)->location_name;
                                        if ($question->question_category == 'scholarship') echo Scholarship::find_by_id($question->category_object_id)->title;
                                    ?>
                                </span>
                                <br />
                                <span class="meta">
                                    <?php 
                                        echo 'ЗАДАН: '. substr($question->published_date, 0, 10).'<br />';
                                        echo 'РАЗОСЛАН: '. substr($question->sent_date, 0, 10);
                                    ?>
                                </span>

                                <td class="action">
                                    <span class="meta">
                                        <a href="/admin/page/send_question/<?php echo $question->question_id?>/" class="view">Разослать еще раз</a><br />
                                        <a href="/admin/page/questions/<?php echo $question->question_id?>/" class="edit">Редактировать</a><br />
                                        <a href="#" class="delete_question" id="<?php echo $question->question_id?>">Удалить</a><br />
                                    </span>
                                </td>
                            </tr>                        
                            <?php $odd_row++; endif; endforeach;?>                       
                </table>
            </div>
        
            
        </form>
        
    </div>
    <div class="dashboard_wrapper">
        <form name="answers" action="/admin/page/answers/" enctype="multipart/form-data" method="Post">
            <h3>Неразосланные ответы: <?php if (empty($answers)) echo "(0)"?> </h3>
            <div >
                <table cellpadding="0" cellspacing="0" class="dashboard">
                    <?php if (!empty($answers)) foreach ($answers as $answer):?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><span class="main">
                                        <?php echo mb_substr($answer->answer_body, 0, 40).'...';?>
                                    </span><br /><br />
                                    <span class="meta">
                                        <?php 
                                            echo mb_substr(Question::find_by_id($answer->question_id)->question_body, 0, 40).'...'
                                        ?>
                                    </span>
                                    <br />
                                    <span class="meta">
                                        <?php  echo 'ОТВЕЧЕН: '.substr($answer->published_date, 0, 10);?>
                                    </span>
                                </td>
                                <td class="action">
                                    <span class="meta">
                                        <a href="/admin/page/send_answer/<?php echo $answer->answer_id?>/" class="view">Разослать</a>
                                        <a href="/admin/page/answers/<?php echo $answer->answer_id?>/" class="edit">Редактировать</a>
                                        <a href="#" class="delete_answer" id="<?php echo $answer->answer_id?>">Удалить</a>
                                    </span>
                                </td>
                            </tr>                        
                            <?php $odd_row++; endforeach;?>                       
                </table>
            </div>
            <h3>Разосланные ответы: <?php if (empty($sent_answers)) echo "(0)"?> </h3>
            <div >
                <table cellpadding="0" cellspacing="0" class="dashboard">
                    <?php if (!empty($sent_answers)) foreach ($sent_answers as $answer):?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><span class="main">
                                        <?php echo mb_substr($answer->answer_body, 0, 40).'...';?>
                                    </span><br /><br />
                                    <span class="meta">
                                        <?php 
                                            echo mb_substr(Question::find_by_id($answer->question_id)->question_body, 0, 40).'...'
                                        ?>
                                    </span>
                                    <br />
                                    <span class="meta">
                                        <?php  echo 'РАЗОСЛАН: '.substr($answer->sent_date, 0, 10);?>
                                    </span>
                                </td>
                                <td class="action">
                                    <span class="meta">
                                        <a href="/admin/page/answers/<?php echo $answer->answer_id?>/" class="edit">Редактировать</a>
                                        <a href="#" class="delete_answer" id="<?php echo $answer->answer_id?>">Удалить</a>
                                    </span>
                                </td>
                            </tr>                        
                            <?php $odd_row++; endforeach;?>                       
                </table>
            </div>
        </form>
    </div>
    </div>
    

             


    </div>
                <!-- // #main -->