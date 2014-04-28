<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $answer_edit = Answer::find_by_id($edit);    

    $question_id = Question::find_by_id($answer_edit->question_id)->question_id;

    $subscriptions_question = Subscription::find_by_object('question', $question_id);
    $subscriptions_tag = array();
    
    //Получаем все теги вопроса
    $question_tags_map = Tagmap::question_get_tags($question_id);
    if(!empty($question_tags_map)){
        foreach($question_tags_map as $tagmap){
            
            //Если группа не равна 0, значит есть синонимы
            if(Tag::find_by_id($tagmap->tag_id)->group_id != 0){
                
                //Получаем все СИНОНИМИЧНЫЕ теги для тега
                $syn_tags = Tag::find_by_group_id(Tag::find_by_id($tagmap->tag_id)->group_id);
                }
                else{
                    
                //Группа = 0, СИНОНИМИЧНЫХ тегов нет
                $syn_tags = Tag::find_by_id($tagmap->tag_id);    
                }
                
                if(!empty($syn_tags)){
                    foreach($syn_tags as $syn_tag){

                        //Получаем все подписки на синонимичный тег
                        $subs_on_syn_tag = Subscription::find_by_object('tag', $syn_tag->tag_id);
                        if(!empty($subs_on_syn_tag)){
                            foreach($subs_on_syn_tag as $sub_on_syn_tag){

                                //Была ли такая подписка уже
                                if(!in_array($sub_on_syn_tag, $subscriptions_tag)){
                                    $subscriptions_tag[] = $sub_on_syn_tag;
                                }
                            }
                        }
                    }
                }
            
        }
    }
    $subscriptions = array_merge($subscriptions_question, $subscriptions_tag);
    
    $odd_row = 0;
    $index = 0;


    if (isset($_POST['send'])){

        for ($i = 0; $i < $_POST['max']; $i++){
            if (isset($_POST['check_' . $i])){
                $mail = new PHPMailerLite();
                $mail->AddAddress(User::find_by_login($_POST['user_' . $i])->email, $_POST['user_' . $i]);
                $mail->CharSet = 'utf-8';
                $mail->ContentType = 'text/html';
                $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');

                $mail->Subject = 'Зуббр.кз - Есть ответ ';
                $mail->Body  = 'Привет, '. $_POST['user_' . $i] .' ты спрашивал <br />';
                $mail->Body .=  Question::find_by_id($answer_edit->question_id)->question_body . '<br />';
                $mail->Body .= '<a href = "http://zubbr.kz/questions/'.Question::find_by_id($answer_edit->question_id)->question_id.'/">Идем смотреть</a> <br />';
                $mail->Send();
                if($mail->Send()){
                    $answer_edit->sent_date = date($dateformat);
                    $answer_edit->save();    
                };
            }    
        }
        $_SESSION['msg'] = 'Ответ разослан';
    } 
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="send_answer" action="" enctype="multipart/form-data" method="Post">
        <h3><?php echo htmlspecialchars($answer_edit->answer_body);?></h3>
        <h3>Поиск получателей</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>

        <h3>Подписавшиеся получить ответ</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if(!empty($subscriptions)) foreach ($subscriptions as $sub):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><input type="checkbox" name="<?php echo 'check_' . $index?>" checked="checked"></input></td>
                            <td><input type="hidden" name="<?php echo 'user_' . $index?>" value="<?php echo User::find_by_id($sub->user_id)->login?>"><?php echo User::find_by_id($sub->user_id)->login;?></td>
                            <td><?php echo $sub->subscription_category?></td>
                        </tr>                        
                        <?php $odd_row++; $index++; endforeach;?>
                <input type="hidden" name="max" value="<?php echo $index?>"></input>                       
            </table>
        </div>
        <input type="submit" name="send" value="Разослать" />     
    </form>
        </div>
                <!-- // #main -->