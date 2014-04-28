<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $question_edit = Question::find_by_id($edit);    

    $relations = Relation::find_all_distinct();
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

                $mail->Subject = 'Зуббр.кз - Есть вопрос';
                $mail->Body  = 'Привет, '. $_POST['user_' . $i] .' посмотри пожалуйста, кажется это вопрос по твоей части';
                
                if($question_edit->question_category == 'university'){
                $uni_name = University::find_by_id($question_edit->category_object_id)->short_name; 
                $mail->Body  .= '<br /><br />Вопрос по: ' . $uni_name . '<br /> ' ;
            }
                $mail->Body .= '<br /><br />Вопрос: '. $question_edit->question_body ;
                
                
                
                $mail->Body .= '<br /><br />Возможно, Ты знаешь ответ и сможешь помочь. Чтобы ознакомиться с вопросом, перейди по ссылке <a href = "http://www.zubbr.kz/questions/'.$question_edit->question_id.'/">http://www.zubbr.kz/questions/'.$question_edit->question_id.'/</a>';
                $mail->Body .= '<br />(если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
                $mail->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';
                if($mail->Send()){
                    $question_edit->sent_date = date($dateformat);
                    $question_edit->save();    
                };
            }    
        }
        $_SESSION['msg'] = 'Вопрос разослан';
    } 
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="send_question" action="" enctype="multipart/form-data" method="Post">
        <h3><?php echo htmlspecialchars($question_edit->question_body);?></h3>
        <h3>Поиск получателей</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>

        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if(!empty($relations)) foreach ($relations as $rel):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><input type="checkbox" name="<?php echo 'check_' . $index?>"
                            <?php 
                            if ($question_edit->question_category == 'university'){
                                if (($question_edit->category_object_id == $rel->university_id)&&($rel->role = 'alumni')) {//Pre selecting Alumnis
                                echo 'checked = "checked"';}; 
                                }
                            elseif ($question_edit->question_category == 'location'){
                                if ($question_edit->category_object_id == University::find_by_id($rel->university_id)->location_id){
                                echo 'checked = "checked"';}
                                elseif ($question_edit->category_object_id == User::find_by_id($rel->user_id)){
                                echo 'checked = "checked"';};     
                                }
                            elseif ($question_edit->question_category == 'scholarship'){
                                if ($question_edit->category_object_id == $rel->scholarship_id){
                                echo 'checked = "checked"';}; 
                                }
                            ?>
                            ></input></td>
                            
                            
                            <td><input type="hidden" name="<?php echo 'user_' . $index?>" value="<?php echo User::find_by_id($rel->user_id)->login?>"><?php echo User::find_by_id($rel->user_id)->login;?></td>
                            <td><?php echo University::find_by_id($rel->university_id)->short_name;?></td>
                            <td><?php echo $rel->role?></td>
                            <td><?php echo $rel->graduate_year?></td>
                            <td><?php echo Scholarship::find_by_id($rel->scholarship_id)->title?></td>
                        </tr>                        
                        <?php $odd_row++; $index++; endforeach;?>
            <input type="hidden" name="max" value="<?php echo $index?>"></input>                       
            </table>
        </div>
        <input type="submit" name="send" value="Разослать" />     
    </form>
        </div>
                <!-- // #main -->