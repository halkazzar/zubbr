<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        //$author = User::find_by_id(Answer::find_by_id($_POST['delete'])->user_id);
//        $mail = new PhpmailerLite();
//        $mail->AddAddress($author->email, $author->login);
//        $mail->Body = 'asdads';
//        $mail->Send();
        
        Answer::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $answer_edit = Answer::find_by_id($edit);    
    
    
    if (isset($_POST['save'])){
        !empty($_POST['answer_id']) ? $ans = Answer::find_by_id($_POST['answer_id']) : $ans = new answer();
        //if field is disabled, $_POST value is not set
        $ans->user_id                     = $_POST['user_id'];
        $ans->question_id                     = $_POST['question_id'];
        $ans->answer_body               = $_POST['answer_body'];
        $ans->published_date              = $_POST['published_date'];

        $ans->save();
        $_SESSION['msg'] = 'Сохранено'; 
    }

    $users     = User::find_all();
    $answers = Answer::find_all();
    $locations = Location::find_cities();
    $universities = University::find_all();
    $scholarships = Scholarship::find_all();

    $odd_row = 0; 
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="answers" action="/admin/page/answers/" enctype="multipart/form-data" method="Post">
        <h3>Поиск</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>

        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if (!empty($answers)) foreach ($answers as $answer):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo n_words(htmlspecialchars($answer->answer_body), 10);?></td>
                            <td><?php 
                                      echo n_words(htmlspecialchars(Question::find_by_id($answer->question_id)->question_body), 10); ?></td>
                            <td><?php echo substr($answer->published_date, 0, 10);?></td>
                            <td class="action">
                                <a href="/admin/page/send_answer/<?php echo $answer->answer_id?>/" class="view">Разослать</a>
                                <a href="/admin/page/answers/<?php echo $answer->answer_id?>/" class="edit">Ред.</a>
                                <a href="#" class="delete_answer" id="<?php echo $answer->answer_id?>">Удалить</a>
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>
        </div>
        <h3><?php if (isset($edit)) echo "Редактирование ответа на вопрос " . Question::find_by_id($answer_edit->quesiton_id)->question_body; else echo "Добавление нового ответа"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="answer_id" value="<?php if (isset($edit)) echo $answer_edit->answer_id;?>"/></p>
            <p><input type="hidden" class="text-long" name="question_id" value="<?php if (isset($edit)) echo $answer_edit->question_id;?>"/></p>
            <p><label>Текст ответа:</label><textarea name="answer_body"><?php if (isset($edit)) echo $answer_edit->answer_body;?></textarea></p>
            
            <p><label>Автор:</label>
                <select name="user_id">
                    <?php foreach($users as $user):?>
                        <option value="<?php echo $user->usr_id?>" <?php if(isset($edit)) if ($answer_edit->user_id==$user->usr_id) echo "selected='selected'"?>><?php echo $user->login?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Дата публикаций:</label><input type="text" class="text-long" name="published_date" value="<?php if (isset($edit)) echo $answer_edit->published_date;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->