<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';

    //Check whether the user is logged in or not, if not, disallow adding to DB
    if (!$session->is_logged_in()){
        $result["logged_id"]=false;
        echo json_encode($result);    
    }
    else {
        //Subscription aka Following the question
        if(!empty($_GET['follow']) && $_GET['follow'] == 'on'){
            $sub = subscription::find_by_object('question', $_GET['question_id'], $session->user_id);
            if(empty($sub)){
                $sub = new subscription();
                $sub->user_id = $session->user_id;
                $sub->subscription_category = 'question';
                $sub->category_object_id = $_GET['question_id'];
                $sub->save();
            }
        }
        elseif(!empty($_GET['follow']) && $_GET['follow'] == 'off'){
            $sub = subscription::find_by_object('question', $_GET['question_id'], $session->user_id);
            if(!empty($sub)){
                Subscription::delete_by_id(array_shift($sub)->subscription_id);
            }
        }

        //Thumb up and down actions
        elseif(!empty($_GET['thumb']) && $_GET['thumb'] == 'on'){
            if($session->is_logged_in()){
                $vote = new vote;
                $vote->user_id = $session->user_id;
                $vote->answer_id = $_GET['answer_id'];
                $vote->save();
            }
            
            
        }
        elseif(!empty($_GET['thumb']) && $_GET['thumb'] == 'off'){ 
            if($session->is_logged_in()){
                $vote = Vote::find_by_all($_GET['answer_id'], $session->user_id);
                if(!empty($vote)){
                    Vote::delete_by_id(array_shift($vote)->vote_id);
                }
            }
        }
        
        //Answer itself
        if(!empty($_GET['answer_text'])){
        $answer = new Answer();
        $answer->answer_body = $_GET['answer_text'];
        $answer->question_id = $_GET['question_id'];
        $answer->user_id = $session->user_id;
        $answer->published_date = date($dateformat);
        $answer->save();

        $author = User::find_by_id($answer->user_id);

        //Preparing the result to be shown in questions div
        $result["answer_body"]=stripslashes($answer->answer_body);
        $result["login"]=$author->login;
        $result["published_date"]=date_long_ago($answer->published_date);

        //sending it to javascript which will display it
        echo json_encode($result);
        }        
    }

?>