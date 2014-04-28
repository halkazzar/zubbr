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

    if($session->is_logged_in()){
        $records = Passed_test::find_by_user($session->user_id);
    }
    elseif(!empty($session->passed_test_record_id)){
        $records = Passed_test::find_by_id($session->passed_test_record_id);
        $tmp = array();
        $tmp[] = $records;
        $records = $tmp;
    }
    
    if (!empty($records)):
    foreach($records as $record): 
    $test = Test::find_by_id($record->test_id);
    ?>
    <div class="post">
        <div class="archive-post-date">
            <img src="/images/test.png" alt=""/>
        </div>

        <div class="archive-post-title">
            <h3 id='test<?php echo $test->test_id?>'>
                <a href="/test/stats/details/<?php echo $record->passed_test_record_id?>/">
                <?php echo $test->test_title?></a></h3>

            <div class="post-date"> 
                <?php 
                
                if ($record->status == 'done') {
                    echo 'Завершен: ';
                }
                else {
                    echo 'Был начат: ';
                } 
                
                $arr = date_translate($record->date); 
                echo $arr['day'] . ' '. $arr['month_word2'];
                
                ?> 

                <!-- <span class="tag"> 
                            <?php
                                $tagmaps = Tagmap::test_get_tags($test->test_id);
                                if (!empty($tagmaps)):
                                    foreach ($tagmaps as $tagmap):
                                    ?>

                                    <a href="/tag/<?php echo $tagmap->tag_id?>/"><?php echo Tag::find_by_id($tagmap->tag_id)->tag_name?></a>

                                    <?php endforeach; else:?>
                                Без тегов
                                <?php endif;?>
                        </span> -->
                <span>
                    Результат: <?php
                    $questions = Testquestion::list_all_questions($test->test_id);
                    $answered_questions = Passed_test_data::find_by_record($record->passed_test_record_id);
                    if(!empty($answered_questions)){
                        $correct_total = 0;
                        foreach($answered_questions as $ans_que){
                            //echo Testanswer::find_by_id($ans_que->answer_id)->answer_right;
                            if (Testanswer::find_by_id($ans_que->answer_id)->answer_right == 1){
                                
                                $correct_total++;
                            }
                        }
                        
                        echo $correct_total .' из '. count($questions);
                    }
                     
                    ?>
                </span>
            
            </div>
        </div>

        <div class="clearer">&nbsp;</div>
    </div>

    <div class="archive-separator"></div>

    <?php endforeach;
          else:
    ?>
    <div class="post">
    <h3>Чтобы посмотреть свои результаты, войдите в систему</h3>
    </div>
    <?php endif;?>
