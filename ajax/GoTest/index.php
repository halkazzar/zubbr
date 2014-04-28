<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    if(!empty($_GET['test_id'])){
        if(ctype_digit($_GET['test_id'])){
            $test_id = $_GET['test_id'];    
        }
    }
    if(!empty($_GET['question_id'])){
        if(ctype_digit($_GET['question_id'])){
            $question_id = $_GET['question_id'];    
        }
    }
    else{
        $question_id = 0;
    }
    if(!empty($_GET['answer_id'])){
        if(ctype_digit($_GET['answer_id'])){
            $answer_id = $_GET['answer_id'];    
        }
    }
    
    //INPUT CURRENT QUESTION AND ANSWER RESULTS

    if($question_id==0)
    //User just started test, make a record in tbl_passed_tests
    {
        $new_passing = new Passed_test;
        if($session->is_logged_in()){
            $new_passing->user_id = $session->user_id;
        }
        else{
            $new_passing->user_id = 0;
        }
        $new_passing->test_id = $test_id;
        $new_passing->status = 'open';
        $new_passing->date = date($dateformat);
        $new_passing->save();
        
        //Saving users passed_test_rec_ids into SESSION
        $session->save_test_record($new_passing->passed_test_record_id);
        //log_action($new_passing->passed_test_record_id, $session->passed_test_record_id);
    }
    else 
    // User passing the test
    {
        //Check if this question was answered before during current test session
        $answer = Passed_test_data::find_by_record_question($session->passed_test_record_id, $question_id);
        if(!empty($answer_id)){
            if($answer_id != 0){
                if(!empty($answer)){
                    $answer->answer_id = $answer_id;
                }
                else{
                    $answer = new Passed_test_data;
                    $answer->answer_id = $answer_id;
                    $answer->question_id = $question_id;
                    $answer->passed_test_record_id = $session->passed_test_record_id;
                }
                $answer->save();
            }
        }
    }


    if ($_GET['direction']=='finish')
    {    //FINISH TEST

        include ("../../include/finish_test.inc");
    }
    else
    {    //GENERATE AND OUTPUT NEXT QUESTION
        if ($_GET['direction']=='next'){
            $more_less_sign = ">"; 
            $sort = "ASC"; 
            $and = "AND question_id$more_less_sign'$question_id'"; 
            $limit = "0,1";
        }
        elseif ($_GET['direction']=='prev'){
            $more_less_sign = "<";
            $sort = "DESC"; 
            $and = "AND question_id$more_less_sign'$question_id'"; 
            $limit = "0,1";
        }
        else {
            $more_less_sign = ""; 
            $sort = "ASC"; 
            $and = ""; 
            $limit = ($_GET['direction']-1).",1";
        }

        //Get new question
        $new_question = Testquestion::get_new_question($test_id, $and, $sort, $limit);
        

        // If there are qusetions for test
        if(!empty($new_question)){
        //$all_questions = Testquestion::list_all_questions($test_id);
//        $k = 1;
//        foreach($all_questions as $que){
//            if ($que->question_id != $new_question->question_id){
//                $k++;
//            }
//        }
//        $total_questions = count($all_questions);
        //echo '<p class="large"> Вопрос '. $k. ' из '.$total_questions.':</p>';
        
        echo '<p class="larger">'.stripslashes($new_question->question_body).'</p>';
        
        //Check if this question was answered during this test sesion before
            //if user has answered this quesion already, put color selection
           
            
            $already_answer = Passed_test_data::find_by_record_question($session->passed_test_record_id, $new_question->question_id);
            
            if(!empty($already_answer)){
            //If it was answered put checked radio button
            $prev_cur_q_answered_id = $already_answer->answer_id;
            }
            else{
                $prev_cur_q_answered_id = -1; //The question wasn't answered before    
            }
            
            $answers = Testanswer::find_by_question_id($new_question->question_id);
            if(!empty($answers)){
            echo "<form id='select_answer' name='select_answer'>";?>
            <table class="test-answers">
                <?php
                $i = 1;
                foreach($answers as $answer):?>
                <tr>
                    <td class="radio">
                    <input type='radio' name='question' id="question<?php echo $i?>" value="<?php echo $answer->answer_id?>" <?php if ($prev_cur_q_answered_id==$answer->answer_id) echo " checked"?>/>
                    </td>
                    <td class="answer-body">
                    <label for="question<?php echo $i?>"><p class="larger nomargin nopadding"><span class="quiet"><?php echo $i++?></span>. <?php echo stripslashes($answer->answer_body)?></p></label>
                        
                    
                    </td>
                </tr>
                <?php
                endforeach;
                ?>
            </table>
            <?php
                echo "</form>";    
            }    
            
        }
        
        


        //Previous question buttons
        $previous_question = Testquestion::get_previous_question($test_id, $new_question->question_id);
        if(!empty($previous_question)){
        echo "<a class='rbutton' onClick='CheckGoTest($test_id, $new_question->question_id,\"prev\",questions)'><span>пред</span></a>";
}
        else {
            echo "<a class='dbutton'><span>пред</span></a>";
        }    
       
       //NEXT question button 
       $next_question = Testquestion::get_next_question($test_id, $new_question->question_id);
       if(!empty($next_question)){
        echo "<a class='rbutton' onClick='CheckGoTest($test_id, $new_question->question_id,\"next\",questions)'><span>след</span></a>";    
       }
       else{
       echo "<a class='dbutton'><span>след</span></a>";    
       }


        //Quesion numbers PAGINATOR3000
        echo '<div class="paginator" style="position:relative; top:-8px; float:left; margin-left:10px; margin-right:10px;" id="paginator_example"> </div>';
        
        //FINISH button
        echo "<a class='rbutton' value='Завершить' onClick='CheckGoTest($test_id, $new_question->question_id,\"finish\",questions)'><span>завершить</span></a>";




    }

?>
