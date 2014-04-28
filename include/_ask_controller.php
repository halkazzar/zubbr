<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
    if(isset($_GET['uni'])){
        $unis = University::search_by_short_name($_GET['uni']);
        
        if(!empty($unis)){
            $i = 0;
            foreach ($unis as $uni){
                $result[$i]['key']=$uni->short_name;    
                $result[$i]['id']=$uni->short_name;    
                $result[$i]['value']=$uni->short_name;
                $i++;    
            }
        }
        echo json_encode($result);
    }

    if(isset($_GET['tag'])){
        $tags = Tag::find_by_name($_GET['tag']);

        if(!empty($tags)){
            $i = 0;
            foreach ($tags as $tag){
                $result[$i]['key']=$tag->tag_name;    
                $result[$i]['id']=$tag->tag_name;    
                $result[$i]['value']=$tag->tag_name;
                $i++;    
            }
        echo json_encode($result);
        }
    }

    if(!empty($_GET['action'])){
    if (($_GET['action'] == 'save') && isset($_GET['ajax'])){
        //Check whether the user is logged in or not, if not, not to allow adding to DB
        if (!$session->is_logged_in()){
            $result["logged_id"]=false;
            echo json_encode($result);    
        }
        else {
            $question = new question();
            $question->question_body = $_GET['question_text'];
            
            if(!empty($_GET['category_text'])){
                $question->question_category = $_GET['category_text'];    
            }
            if(!empty($_GET['object_id_text'])){
                $question->category_object_id = $_GET['object_id_text'];
            }   
            
            $question->user_id = $session->user_id;
            $question->published_date = date($dateformat);
            $question->save();

            //Subscribing the user for his own question to make him able to receive
            //answers
            $new_sub = new subscription();
            $new_sub->user_id = $session->user_id;
            $new_sub->subscription_category = 'question';
            $new_sub->category_object_id = $question->question_id;
            $new_sub->save();


            //Sending email notification to us
            $mail = new PHPMailerLite();
            $mail->AddAddress('abylkassym@gmail.com');
            $mail->AddAddress('mephisto9000@gmail.com');
            $mail->AddAddress('nargis.aslanova@gmail.com');
            $mail->AddAddress('almabers@gmail.com');
            $mail->AddAddress('daur88@gmail.com');
            $mail->CharSet = 'utf-8';
            $mail->ContentType = 'text/html';
            $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');

            $mail->Subject = 'Зуббр.кз - Новый вопрос';

            $mail->Body = 'Вопрос задан: ' . date($dateformat) . "<br />";
            $mail->Body .= 'Автор вопроса: ' . user::find_by_id($session->user_id)->login . "<br />";

            if($question->question_category == 'university'){
                $uni_name = University::find_by_id($question->category_object_id)->short_name; 
                $mail->Body  .= 'Вопрос по: ' . $uni_name . '<br /> ' ;
            }
            $mail->Body .= 'Текст вопроса: ' . $question->question_body;
            if ($mail->Send()){
                $result['email_sent'] = true;
            }




            //2. Saving
            $tags_id = array();
            $tags_name = array();
            
            $tags_string = $_GET['tags'];
            $tags = tag_str_to_array($tags_string);
            
            foreach($tags as $tag_name){
                if(!empty($tag_name) && $tag_name != '' && $tag_name != ' '){
                    $tag = Tag::find_by_exact_name(trim($tag_name));
                    if(empty($tag)){
                        $tag = new Tag;
                        $tag->tag_name = trim($tag_name);
                        $tag->is_default = 0;
                        $tag->group_id = 0;
                        $tag->save();
                    }   
                    $new_map = new Tagmap;
                    $new_map->object_type = 'question';
                    $new_map->object_id = $question->question_id;
                    $new_map->tag_id = $tag->tag_id;
                    $new_map->save();
                    
                    $tags_id[] = $tag->tag_id;
                    $tags_name[] = $tag->tag_name;
                }    
            }

            $author = User::find_by_id($question->user_id);

            //Preparing the result to be shown in answers div
            $result["question_body"]=stripslashes($question->question_body) ;
            $result["login"]=$author->login;
            $result["published_date"]=date_long_ago($question->published_date);
            $result["question_id"]=$question->question_id;
            $result["tags"]=$tags_name;
            $result["tags_id"]=$tags_id;
            if(empty($_GET['category_text'])){
                $result["redirect_to_question"]=true;    
            }
            
            //sending it to javascript which will display it
            echo json_encode($result);        
        }
    }    
    }
    

?>