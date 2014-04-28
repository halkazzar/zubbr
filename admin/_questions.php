<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        $author = User::find_by_id(Question::find_by_id($_POST['delete'])->user_id);
        $mail = new PhpmailerLite();
        $mail->CharSet = 'utf-8';
        $mail->ContentType = 'text/html';
        $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');

        $mail->AddAddress($author->email, $author->login);
        $mail->Subject = 'Зуббр.кз – Ваш вопрос не прошел модерацию';
        $mail->Body  = 'Привет! Ты задал вопрос портале Зуббр.кз <br />';
        $mail->Body  .= Question::find_by_id($_POST['delete'])->question_body;
        $mail->Body .=' <br />К сожалению, вопрос не прошел модерацию и был удален, поскольку не соответствует <a href="http://www.zubbr.kz/help/rules/">Правилам сайта</a>.
Попробуй перефразировать вопрос и задать его снова, или напиши нам info@zubbr.kz';
       // $mail->Body .= 'Причина: ' . $_POST['reason'];
        $mail->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';
        $mail->Send();

        Question::delete_by_id($_POST['delete']);

        //При удалении вопроса, сначала удалим теги из вопроса
        $tagmaps = Tagmap::question_get_tags($_POST['delete']);
        foreach($tagmaps as $map){
            Tagmap::delete_by_id($map->tagmap_id);
        }
        

        //echoing to jscript
        echo 'Deleted';
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $question_edit = Question::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['question_id']) ? $que = Question::find_by_id($_POST['question_id']) : $que = new Question();
        //if field is disabled, $_POST value is not set
        $que->user_id                     = $_POST['user_id'];
        $que->question_body               = $_POST['question_body'];
        $que->question_category           = $_POST['question_category'];
        $que->category_object_id          = $_POST['category_object_id'];
        $que->published_date              = $_POST['published_date'];

        $que->save();
        
        
        //Now saving tags
        //1. Remove existing
        $tagmaps = Tagmap::question_get_tags($que->question_id);
        foreach($tagmaps as $map){
            Tagmap::delete_by_id($map->tagmap_id);
        }
        
        //2. Saving
        $tags_string = $_POST['tag_names'];
        $tag_names = tag_str_to_array($tags_string);
        
        foreach($tag_names as $tag_name){
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
                $new_map->object_id = $que->question_id;
                $new_map->tag_id = $tag->tag_id;
                $new_map->save();
            }
        }
        $_SESSION['msg'] = 'Сохранено';
    }

    $users     = User::find_all();
    $questions = Question::find_all();
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
    <form name="questions" action="/admin/page/questions/" enctype="multipart/form-data" method="Post">
        <h3>Поиск</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>

        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if (!empty($questions)) foreach ($questions as $question):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo n_words(htmlspecialchars($question->question_body), 10);?></td>
                            <td><?php if ($question->question_category == 'university') echo University::find_by_id($question->category_object_id)->short_name;
                                    if ($question->question_category == 'location') echo Location::find_by_id($question->category_object_id)->location_name;
                                    if ($question->question_category == 'scholarship') echo Scholarship::find_by_id($question->category_object_id)->title;
                            ?></td>
                            <td><?php echo substr($question->published_date, 0, 10);?></td>
                            <td><?php if($question->sent_date == '0000-00-00 00:00:00') echo 'NEW!'; else echo $question->sent_date;?></td>
                            <td class="action">
                                <a href="/admin/page/send_question/<?php echo $question->question_id?>/" class="view">Разослать</a>
                                <a href="/admin/page/questions/<?php echo $question->question_id?>/" class="edit">Редактировать</a>
                                <a href="#" class="delete_question" id="<?php echo $question->question_id?>">Удалить</a>
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>
        </div>
        <h3><?php if (isset($edit)) echo "Редактирование $question_edit->question_id "; else echo "Добавление нового вопроса"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="question_id" value="<?php if (isset($edit)) echo $question_edit->question_id;?>"/></p>
            <p><label>Текст вопроса:</label><textarea name="question_body"><?php if (isset($edit)) echo $question_edit->question_body;?></textarea></p>
            <p><label>Категория:</label>
                <select name="question_category" id="question_category">
                    <option value="university"      <?php if(isset($edit)) if ($question_edit->question_category=='university')     echo "selected='selected'"?>>Вопрос по вузам</option>
                    <option value="scholarship"     <?php if(isset($edit)) if ($question_edit->question_category=='scholarship')    echo "selected='selected'"?>>Вопрос по стипендиям (программам обучения)</option>
                    <option value="location"        <?php if(isset($edit)) if ($question_edit->question_category=='location')       echo "selected='selected'"?>>Вопрос по местности (расположению)</option>
                </select>
            </p>
            <p><label>Объект:</label>
                <select name="category_object_id" id="category_object_id">
                    <?php if(isset($edit)) if ($question_edit->question_category=='university')
                                foreach ($universities as $university):?>
                                <option value="<?php echo $university->university_id?>" <?php if ($university->university_id == $question_edit->category_object_id) echo "selected='selected'"?>><?php echo $university->short_name?></option>
                                <?php endforeach;?>

                    <?php if(isset($edit)) if ($question_edit->question_category=='location')
                                foreach ($locations as $location):?>
                                <option value="<?php echo $location->location_id?>" <?php if ($location->location_id == $question_edit->category_object_id) echo "selected='selected'"?>><?php echo $location->location_name?></option>
                                <?php endforeach;?>

                    <?php if(isset($edit)) if ($question_edit->question_category=='scholarship')
                                foreach ($scholarships as $scholarship):?>
                                <option value="<?php echo $scholarship->scholarship_id?>" <?php if ($scholarship->scholarship_id == $question_edit->category_object_id) echo "selected='selected'"?>><?php echo $scholarship->title?></option>
                                <?php endforeach;?>
                </select>
            </p>
            <p><label>Автор:</label>
                <select name="user_id">
                    <?php foreach($users as $user):?>
                        <option value="<?php echo $user->usr_id?>" <?php if(isset($edit)) if ($question_edit->user_id==$user->usr_id) echo "selected='selected'"?>><?php echo $user->login?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Теги:</label>
                <?php 
                            if(isset($edit)){
                                $tags='';
                                $tagmap = Tagmap::question_get_tags($question_edit->question_id);
                                foreach($tagmap as $map) {
                                    $tags[] = tag::find_by_id($map->tag_id)->tag_name;
                                }

                                $tags_string = implode(', ', $tags);
                                
                            }
                        ?>
                <input id="tag_names" class="text-long" name="tag_names" value="<?php if(isset($edit)) {echo $tags_string;}?>">
                </input>
            </p>
            <p><label>Дата публикаций:</label><input type="text" class="text-long" name="published_date" value="<?php if (isset($edit)) echo $question_edit->published_date;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->