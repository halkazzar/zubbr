<?
// /inc/finish_test.inc
// 
// this file is used to display test results included in /ajax/GoTest/index.php

//Finish test, set status closed
    if(!empty($_GET['record_id'])){
        if(ctype_digit($_GET['record_id'])){
            $test_id = Passed_test::find_by_id($_GET['record_id'])->test_id;
            $record_id = $_GET['record_id'];
        }    
    }
    else{   
        $new_passing = Passed_test::find_by_id($session->passed_test_record_id);
        $new_passing->status = 'done';
        $new_passing->save();
        $test_id = $new_passing->test_id;
        $record_id = $session->passed_test_record_id;    
    }
    
    
    
    $questions = Testquestion::list_all_questions($test_id);
    $answered_questions = Passed_test_data::find_by_record($record_id);
    $correct_total = 0;
    if(!empty($answered_questions)){
    foreach($answered_questions as $ans_que){
            if (Testanswer::find_by_id($ans_que->answer_id)->answer_right == 1){

                $correct_total++;
            }
        }

        //echo $correct_total .' из '. count($questions);
    }
    $question_count = count($questions);
    $not_answered = $question_count - count($answered_questions);

    $output = "<ul class='nice-list'>";
    if(!empty($questions)){
        $i=1;
        foreach($questions as $question){
        $pass = Passed_test_data::find_by_record_question($record_id, $question->question_id);
        $output .= '<li><span class="quiet">'.$i++.'.</span> <b>'.stripslashes($question->question_body)."</b>";

            $j = 1;
            //Open unsorted list for answers
            $output .= "<ul>";
            $answers = Testanswer::find_by_question_id($question->question_id);
            if(!empty($answers)){
                foreach($answers as $ans){
                    $ans_status = '';

                    if(!empty($pass)){
                        if($ans->answer_id == $pass->answer_id){
                            if($ans->answer_right == 1){
                                $ans_status = 'tsuccess';    
                            }
                            else{
                                $ans_status = 'terror';
                            }    
                        }

                    }
                    
                    $output .= '<div  class="'.$ans_status.'"><li><span class="quiet">'.$j++.'.</span> ';   
                    $output .= stripslashes($ans->answer_body)."</li></div>";    
                }
                $output .= "</ul>";
            }
            
                
        
                
        }
        $output .= "</ul>";
    }

	echo "<blockquote><p>Из $question_count вопросов, Вы <u>правильно</u> ответили на <b>$correct_total</b>. Не отвеченных вопросов: $not_answered</p></blockquote>";
	
	if($session->is_logged_in()):
        echo $output;
    ?>
    <h3>Поделись оценкой!</h3>
    <div 
                class="addthis_toolbox addthis_default_style" 
                addthis:url='http://www.zubbr.kz/test/passing/<?php echo $record_id?>/'
                addthis:title="<?php echo "Тесты на Зуббр.кз"?>"
                addthis:description="<?php echo "Смотрите, я набрал $correct_total баллов по тесту «" . Test::find_by_id($test_id)->test_title .'»';?>">
                <a class="addthis_button_facebook"></a>
                <a class="addthis_button_twitter"></a>
                <a class="addthis_button_vk"></a>
                <a class="addthis_button_mymailru"></a>

            </div>
            <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=daur88"></script>
            <!-- AddThis Button END -->
    
    <?php
    else:
        echo 'Зарегистрируйтесь для получения полного отчета';
    endif;

?>