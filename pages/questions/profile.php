<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    $include_in_head = "<link rel='stylesheet' type='text/css' href='/stylesheets/form.css' media='screen' />";
    $include_in_head .= "\n<script type='text/javascript' src='/javascripts/jquery-1.4.2.min.js'></script>";
    $include_in_head .= "\n<script type='text/javascript' src='/javascripts/_answer.js'></script>";
    $include_in_head .= "\n<script type='text/javascript' src='/javascripts/__questions.js'></script>";

    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Вопрос/Ответ ' .$pipe. ' ' . Question::find_by_id($_GET['question_id'])->question_body;
    $meta_description = 'Узнай ответ на вопрос: ' . Question::find_by_id($_GET['question_id'])->question_body;
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "qa";
    $current_tab = "all questions";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     



    if (isset($_GET['question_id'])){
        $question_mode = 'id';
        $current_id = $_GET['question_id'];

        $answers = Answer::find_by_question_id($_GET['question_id']);
        
    }

?>
<div class="main" id="main-two-columns">
    <div class="left" id="main-left">
        <div class="post">
            <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";?> 
            <div id="answers">
                <?php
                    $index = 0;
                    if (!empty($answers)) foreach($answers as $answer):
                    $votes = Vote::find_by_answer($answer->answer_id);
                    //Checking whether the user is logged in and already voted for answer
                if ($session->is_logged_in()){
                    $vote = Vote::find_by_all($answer->answer_id, $session->user_id);
                    if(!empty($vote)){                                              
                        $already_voted = true;
                    }
                }
                else{
                    $already_voted = false;    
                }
                    
                        ?>             
                        <div class="question-post" id = "answer-<?php echo $index++;?>">
                            <div class="col3-avatar left avatar"> 
                                <a href="/users/<?php echo User::find_by_id($answer->user_id)->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $answer->user_id . '.jpg')) {echo $answer->user_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="center bordered" /></a> 
                                <div class="meta-hidden text-center"><p class="nomargin"><br /><?php if ($current_tab != "abitur"):?> <br /><?php endif;?><br /></p></div>
                                <div class="meta text-center">
                                    <span class="_login"><?php echo User::find_by_id($answer->user_id)->login?>
                                    </span>
                                    <br />

                                    <span class="_published_date"><?php echo date_long_ago($answer->published_date)?>
                                    </span>

                                    <br />
                                    
                                </div>
                            </div>

                            <div class="col3-mid-body body "> 
                                <p class="_answer_body large"><?php echo htmlspecialchars($answer->answer_body);?></p>
                                <div class="toolbar">
                                <p class="large" id="a<?php echo $answer->answer_id?>">
                                    <?php if (!empty($already_voted)):?>
                                        <a class="thumb_up" href="#">полезный ответ</a>
                                        <?php else:?>
                                        <a class="thumb_down" href="#">полезный ответ</a>
                                        <?php endif; unset($already_voted);?>
                                    <span class="counter">
                                        <span class="small counter_number"><?php if(!empty($votes)){ echo count($votes);} else echo '0'?></span>
                                    </span>
                                </p>
                            </div>
                            </div>
                             <div class="clearer">&nbsp;</div>
                        </div>

                        <?php endforeach;?>

                <?php if($session->is_logged_in()):?>
                    <div class="question-post hidden" id = "bufferAnswer">
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
                            
                        </div>
                    </div>

                    <div class="col3-mid-body body "> 
                        <p class="_answer_body large">Answer Body</p>
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
                    
                    
                    
                    
                    
                                        <?php endif;?>
            </div>
            
            <div id="answer" class="myform">
                <form id="answerButton" action="">
                    <button type="submit" id="openAnswerForm" class="center"><?php if ($session->is_logged_in() && ($question->user_id == $session->user_id)){echo 'Дополнить вопрос';}else echo 'Я знаю ответ';  ?></button>
                </form>
                <form id="answerForm" action="" class="hidden">
                    <input type="hidden" id="question_id" value="<?php echo $_GET['question_id']?>"></input>
                    <label><?php if ($session->is_logged_in() && ($question->user_id == $session->user_id)){echo 'Дополнение к вопросу:';}else echo 'Ответ:'; ?></label>
                    <textarea id = "answerText" cols="10" rows="5"></textarea>
                    <div class="clearer">&nbsp;</div>

                    <button type="submit" id="submitAnswer"><?php if ($session->is_logged_in() && ($question->user_id == $session->user_id)){echo 'Дополнить';}else echo 'Ответить'; ?></button>
                    <button type="submit" id="cancelAnswer">Отменить</button>
                </form>
                <div class="clear"></div>
            </div>

        </div>    
        <div class="clearer">&nbsp;</div>

    </div>

    <div class="right sidebar" id="sidebar-2">
             
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_similar_questions.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        