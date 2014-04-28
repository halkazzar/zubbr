<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_GET['filter_test']) && isset($_GET['ajax'])): 
    
    if ($_GET['filter_test'] !=-1)
    $questions = Testquestion::find_by_test_id($_GET['filter_test']); else $questions = Testquestion::find_all();
    $odd_row = 0;
    ?>
    
        
    <table cellpadding="0" cellspacing="0" id="filter_table">
        <?php $i = 1; if (!empty($questions)) foreach ($questions as $question):?>
                    <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $i?></td>
                    <td><?php echo $question->question_body?></td>
                    <td><?php echo Test::find_by_id($question->test_id)->test_title?></td>
                    <td class="action">
                        <a href="/admin/page/testquestions/<?php echo $question->question_id?>/" class="edit">Редактировать</a>
                        <a href="#" class="delete_testquestion" id="<?php echo $question->question_id?>">Удалить</a>
                    </td>
                </tr>                        
                <?php $odd_row++; $i++; endforeach;?>                       
    </table>
                
    <?php endif;?>
    
    
    <?php
    if (isset($_POST['delete_testanswer'])) {
        Testanswer::delete_by_id($_POST['delete_testanswer']);
    }

    if (isset($_POST['delete_testquestion'])) {
        //Cascade deleting ANSWERS first
        $answer_to_del = Testanswer::find_by_question_id($_POST['delete_testquestion']);
        foreach ($answer_to_del as $ans) Testanswer::delete_by_id($ans->answer_id);
        
        //Then the question itself        
        Testquestion::delete_by_id($_POST['delete_testquestion']);
        
    }

    //If user presses ADD NEW ANSWER, a div appears and it's immediately saved into DB
    if (isset($_GET['add_question']) && isset($_GET['ajax'])){
        $new = new Testanswer();
        if ($_GET['add_question'] != -1) {
            $new->question_id = $_GET['add_question'];    
        } else {
            $new_question = new Testquestion();
            $new_question->test_id = $_GET['test_id'];
            $new_question->question_type_id = 1;
            $new_question->save();
            
            $new->question_id = $new_question->question_id; //Attaching NEW QUESTION's ID to the ANSWERS question id field
            $result['question_id'] = $new_question->question_id;
        } 
        
        $new->answer_type_id = 1;
        $new->save();
        $result['answer_id'] = $new->answer_id;
        echo json_encode($result);    
    }
    
    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $question_edit = Testquestion::find_by_id($edit);    


    if (isset($_POST['save'])){
        !empty($_POST['question_id']) ? $que = Testquestion::find_by_id($_POST['question_id']) : $que = new Question();
        //if field is disabled, $_POST value is not set
        $content = str_replace('images/', '/images/', $_POST['question_body']);
        
        $que->question_body     = $content;
        $que->question_type_id  = $_POST['question_type_id'];
        $que->test_id           = $_POST['test_id'];

        $que->save();
        for($i = 1; $i <= $_POST['answers_count']; $i++){
            $answer = Testanswer::find_by_id($_POST['answer_id_' . $i]);
            $content_answer = str_replace('images/', '/images/', $_POST['answer_body_' . $i]);
        
            $answer->answer_body = $content_answer;    
            $answer->answer_type_id = 1;
            if(isset($_POST['check_' . $i])) $answer->answer_right = 1; else $answer->answer_right = 0; 
            $answer->save();
        }
        $_SESSION['msg'] = 'Сохранено';
    }


    $questions = Testquestion::find_all();
    $odd_row = 0;
    
    if (!isset($_GET['ajax'])): 
?>  

<div id="main">
 <?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="testquestions" action="/admin/page/testquestions/" enctype="multipart/form-data" method="Post">
        <h3>Имеющиеся в базе</h3>
        <fieldset>
            <p><label>Отобразить тестовые вопросы по:</label>
            <select id="test_id" name="test_id">
            <option value="-1">Все вопросы</option>
                <?php $tests   = Test::find_all();  ?>
                <?php foreach ($tests as $test):?>
                    
                    <option value="<?php echo $test->test_id?>"><?php if($test->lang_id == 1) $lang = 'Kazakh'; else $lang = 'Russian'; echo $test->test_title . ' (' . $lang .') '?></option>
                    <?php endforeach;?>
            </select>
        </fieldset>

        <div id="filter_results">
            <table cellpadding="0" cellspacing="0" id="filter_table">
                <?php $i = 1; if (!empty($questions)) foreach ($questions as $question):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo $i?></td>
                            <td><?php echo $question->question_body?></td>
                            <td><?php echo Test::find_by_id($question->test_id)->test_title?></td>
                            <td class="action">
                                <a href="/admin/page/testquestions/<?php echo $question->question_id?>/" class="edit">Редактировать</a>
                                <a href="#" class="delete_testquestion" id="<?php echo $question->question_id?>">Удалить</a>
                            </td>
                        </tr>                        
                        <?php $odd_row++; $i++; endforeach;?>                       
            </table>    
        </div>

        <h3><?php if (isset($edit)) echo "Редактирование $question_edit->question_body "; else echo "Добавление нового вопроса"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="question_id" value="<?php if (isset($edit)) echo $question_edit->question_id; else echo '-1'?>"/></p>
            <p><label>Тест:</label>
                <select name="test_id" id="test_id">
                    <?php foreach ($tests as $test):?>
                        <option value="<?php echo $test->test_id?>" <?php if ($test->test_id == $question_edit->test_id) echo 'selected = "selected"'?>><?php if($test->lang_id == 1) $lang = 'Kazakh'; else $lang = 'Russian'; echo $test->test_title . ' (' . $lang .') '?> </option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Тип вопроса:</label>
                <select name="question_type_id" id="question_type_id">
                    <?php 
                        $questiontypes = Testquestiontype::find_all();
                        foreach ($questiontypes as $type):?>
                        <option value="<?php echo $type->question_type_id?>" <?php if ($type->question_type_id == $question_edit->question_type_id) echo 'selected = "selected"'?>><?php echo $type->question_type_name?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Текст вопроса (прогоните текст через Блокнот, чтобы удалить все стили):</label>
                <textarea name="question_body" id="question_body"><?php if (isset($edit)) echo $question_edit->question_body;?>
                </textarea></p>

            <div id="answers">
                        
                    <p>
                        <input type="hidden" class="text-long" name="answers_count" id="answers_count" value="<?php if (isset($edit)) echo Testanswer::count_by_question_id($question_edit->question_id); else echo '0'?>"/>
                    </p>
                    <?php if (isset($edit)):
                        $answers = Testanswer::find_by_question_id($question_edit->question_id);
                    
                     $i = 0; foreach($answers as $answer): $i++; ?>
                        <p>
                            <input type="hidden" class="text-long" name="answer_id_<?php echo $i?>" value="<?php echo $answer->answer_id?>"/>
                            <label><span class="option_number"><?php echo $i?></span>-вариант ответа (прогоните текст через Блокнот, чтобы удалить все стили):</label>
                            <div class="left">
                                <textarea name="answer_body_<?php echo $i?>" id="answer_body_<?php echo $i?>"><?php echo $answer->answer_body;?>
                                </textarea>
                            </div>
                            <div class="right">
                                <input type="checkbox" name="check_<?php echo $i?>" <?php if ($answer->answer_right == 1) echo 'checked = "checked"'?>>Правильный ответ</input>
                                <br /><br /><br />
                                <input type="button" name="remove_answer_<?php echo $i?>" class="delete_testanswer" id="<?php echo $answer->answer_id?>" value="Удалить ответ" />
                            </div>
                            <div class="clear"></div>
                        </p>
                        <p>&nbsp;</p>    
                        <?php 
                            endforeach;
                        endif;?>    
            </div>
            <div id="buffer" class="hidden">
                <p>
                    <input type="hidden" class="text-long" name="answer_id" value=""/>
                    <label><span class="option_number"></span>-вариант ответа (прогоните текст через Блокнот, чтобы удалить все стили):</label>
                    <div class="left">
                        <textarea name="answer_body" id="answer_body"></textarea>
                    </div>
                    <div class="right">
                        <input type="checkbox" name="check">Правильный ответ</input>
                        <br /><br /><br />
                        <input type="button" name="remove_answer" class="delete_testanswer" id="-1" value="Удалить ответ" />
                    </div>
                    <div class="clear"></div>
                </p>
                <p>&nbsp;</p>
            </div>
            <input type="button" name="add_answer" value="Добавить новый ответ" />
            <input type="submit" name="save" value="Сохранить" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->
<?php endif;?>