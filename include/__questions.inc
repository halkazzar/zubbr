<?php
    /**
    * Zubr
    * Question listing asking block
    * To use it properly 
    *   '$question_mode'
    *   '$current_id'        - 2 mode
    *   '$current_object_id'  - 1 mode
    *   '$current_category' variables are required - 1 mode
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
    
    
    switch ($question_mode) {
        case 'ready':
            $questions = $questions;
            break;
        case 'none':
            $questions = '';
            break;
        case 'tagged':
            if(!empty($tag)){
            $questions = Question::find_by_tag($tag->tag_id, $per_page, $pagination->offset());
            }
            else{
                $questions = array();
            }
            break;
        case 'object':
            $questions = Question::find_by_object($current_category, $current_object_id, $per_page, $pagination->offset());
            break;
        case 'random':
            //$array = Answer::find_answered_questions();
            //print_r($array);
            $questions = Question::find_random(3);
            break;
        case 'random_for_stud':
            $questions = Question::find_random_for_stud(4, $que_user_id);
            break;
        case 'random_for_alumni':
            $questions = Question::find_random_for_alumni(4, $que_user_id);
            break;
        case 'all':
            $questions = Question::find_all($per_page, $pagination->offset());
            break;
        case 'unanswered':
            $questions = Question::find_unanswered($per_page, $pagination->offset());
            break;
        case 'id':
            $questions = Question::find_by_id_array($current_id);
            break;
        case 'random_answered':
            $questions_ids = Answer::find_answered_questions();
            $questions = array();

            if (!empty($questions_ids)){
                foreach($questions_ids as $id){
                    $questions[] = Question::find_by_id($id['question_id']);
                }
            }
            break;
    }

?>

<div id = "questions">
    <?php
        $index = 0;
        if (!empty($questions)):
            foreach($questions as $question):
                if(!empty($question)):
                $subscriptions = Subscription::find_by_object('question', $question->question_id);
    
                //Checking whether the user is logged in and already subscribed
                if ($session->is_logged_in()){
                    $sub = subscription::find_by_object('question', $question->question_id, $session->user_id);
                    if(!empty($sub)){
                        $already_subscribed = true;
                    }
                }
                else{
                    $already_subscribed = false;    
                }
            ?>             
            <div class="question-post" id = "question-<?php echo $index++;?>">
                <div class="col3-avatar left avatar"> 
                    <a href="/users/<?php echo User::find_by_id($question->user_id)->login?>/" class = " _avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $question->user_id . '.jpg')) {echo $question->user_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="center bordered" /></a> 
                    <div class="meta-hidden text-center"><p class="nomargin"><br /><?php if ($current_tab != "abitur"):?> <br /><?php endif;?><br /></p></div>
                    <div class="meta text-center">
                        <span class="_login">
                            <?php echo User::find_by_id($question->user_id)->login?>
                        </span>
                        <br />
                        <?php if ($current_tab != "abitur"):?>
                            <span class="_published_date">
                                <?php echo date_long_ago($question->published_date)?>
                            </span>
                            <br />
                            <?php endif;?>
                        <!--<span class="_actions">X+-
                        </span>-->
                    </div>
                </div>

                <div class="col3-mid-body body"> 
                    <p class="_question_body large"><?php echo htmlspecialchars($question->question_body);?></p>
                    <p class="large nomargin nopadding">
                        <?php
                            if($question->question_category == 'university'):
                                $object_text = University::find_by_id($question->category_object_id)->short_name;
                        ?>
                        <span class="uni">
                        <a href="/questions/cat/<?php echo $question->question_category?>/<?php echo $question->category_object_id?>/" title="Найти все вопросы касательно <?php echo $object_text?>"><?php echo $object_text?></a>
                        </span>
                        <?php
                            endif;
                        ?>
                        <span class="tag"> 
                            <?php
                                $tagmaps = Tagmap::question_get_tags($question->question_id);
                                if (!empty($tagmaps)):
                                    foreach ($tagmaps as $tagmap):
                                    ?>
                                    <?php
                                    $tag_title_tmp = tag::find_by_id($tagmap->tag_id)->tag_name;
                                    ?>
                                    <a href="/questions/tagged/<?php echo $tag_title_tmp?>/"><?php echo $tag_title_tmp?></a>

                                    <?php endforeach; else:?>
                                Без тегов
                                <?php endif;?>
                        </span>
                    </p>
                    <p class="large nomargin nopadding">    
                        <a class="answer" href="/questions/<?php echo $question->question_id?>/"><?php echo Answer::count_answers($question->question_id)?> ответов &#187;</a>
                        
                    </p>
                    <div class="toolbar">
                        <p class="large" id="q<?php echo $question->question_id?>">
                            <?php if (!empty($already_subscribed)):?>
                                <a class="follow_question" href="#">я тоже хочу узнать</a>
                                <?php else:?>
                                <a class="notfollow_question" href="#">я тоже хочу узнать</a>
                                <?php endif; unset($already_subscribed);?>
                            <span class="counter">
                                <span class="small counter_number"><?php if(!empty($subscriptions)){ echo count($subscriptions);} else echo '0'?></span>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="clearer">&nbsp;</div>
            </div>
            <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>

    <?php if($session->is_logged_in()):?>

        <div class="question-post hidden" id = "buffer">
            <div class="col3-avatar left avatar"> 
                <a href="/users/<?php echo User::find_by_id($session->user_id)->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $session->user_id . '.jpg')) {echo $session->user_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="center bordered" /></a> 
                <div class="meta-hidden text-center"><p class="nomargin"><br /><br /><br /></p></div>
                <div class="meta text-center">
                    <span class="_login">login
                    </span>
                    <br />

                    <span class="_published_date">date
                    </span>
                    <br />

                    <!--<span class="_actions">X+-
                    </span>-->

                </div>
            </div>

            <div class="col3-mid-body body "> 
                <p class="_question_body large">Question Body</p>
                <p class="large">
                <a class="_question_id answer" href="#">ответов &#187;</a>
                <span class="tag">Без тегов</span>
                </p>
                
            </div>
            <div class="clearer">&nbsp;</div>
        </div>

        <?php endif;?>

    </div>
