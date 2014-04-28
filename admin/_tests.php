<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Test::delete_by_id($_POST['delete']);
        log_action($session->user_id, 'Запрос на удаление теста ' . $_POST['delete'] . ': OK');
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $edited_test = Test::find_by_id($edit);    

    if (isset($_POST['save'])){
        
        if (!empty($_POST['test_id'])){
            $test = Test::find_by_id($_POST['test_id']);    
        } else {
            $test = new Test();    
        }
        
        if (!empty($_POST['test_title']))            $test->test_title      = $_POST['test_title'];
        if (!empty($_POST['test_desc']))             $test->test_desc       = $_POST['test_desc'];
        if (!empty($_POST['test_access']))           $test->test_access     = $_POST['test_access'];
        if (!empty($_POST['test_time_type']))        $test->test_time_type  = $_POST['test_time_type'];
        if (!empty($_POST['lang_id']))               $test->lang_id         = $_POST['lang_id'];
        if (!empty($_POST['test_status']))           $test->test_status     = $_POST['test_status'];
        
        $test->usr_id       = $session->user_id;
        
        $test->save();

        
        //Now saving tags
        //1. Remove existing
        $tagmaps = Tagmap::test_get_tags($test->test_id);
        if(!empty($tagmaps)){
            foreach($tagmaps as $map){
            Tagmap::delete_by_id($map->tagmap_id);
        }
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
                $new_map->object_type = 'test';
                $new_map->object_id = $test->test_id;
                $new_map->tag_id = $tag->tag_id;
                $new_map->save();
            }
        }
        $_SESSION['msg'] = 'Сохранено';
    }

    $tests = Test::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="tests" action="/admin/page/tests/" enctype="multipart/form-data" method="Post">
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($tests)) foreach ($tests as $test):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $test->test_title?></td>
                    <td><?php echo $test->test_status?></td>
                    <td><?php echo $test->test_access?></td>
                    <td><?php if ($test->lang_id == 1) echo 'Kazakh'; elseif ($test->lang_id == 0) echo 'Russian';?></td>
                    <td class="action"><a href="/admin/page/tests/<?php echo $test->test_id?>/" class="edit">Редактировать</a><a href="#" class="delete_test" id="<?php echo $test->test_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование теста по $test->test_title "; else echo "Добавить тест"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="test_id" value="<?php if (isset($edit)) echo $edited_test->test_id;?>"/></p>
            <p><label>Название теста:</label><input type="text" class="text-long" name="test_title" value="<?php if (isset($edit)) echo $edited_test->test_title;?>"/></p>
            <p><label>Описание теста:</label><textarea rows="1" cols="1" name="test_desc" ><?php if (isset($edit)) echo $edited_test->test_desc;?></textarea>
            <p><label>Уровень доступа:</label><select name='test_access'>
                                <option value="private" <?php if (isset($edit) && $edited_test->test_access == 'private') echo 'selected = "selected"';?>>Закрытый(Private)</option>
                                <option value="public" <?php if (isset($edit) && $edited_test->test_access == 'public') echo 'selected = "selected"';?>>Открытый(Public)</option>
                                </select></p>
            <p><label>Время на тест:</label><select name='test_time_type'>
                                <option value="test" <?php if (isset($edit) && $edited_test->test_time_type == 'test') echo 'selected = "selected"';?>>На тест</option>
                                <option value="question" <?php if (isset($edit) && $edited_test->test_time_type == 'question') echo 'selected = "selected"';?>>На вопрос</option>
                                </select></p>
            <p><label>Язык теста:</label><select name='lang_id'>
                                <option value="1" <?php if (isset($edit) && $edited_test->lang_id == 1) echo 'selected = "selected"';?>>Казахский</option>
                                <option value="0" <?php if (isset($edit) && $edited_test->lang_id == 0) echo 'selected = "selected"';?>>Русский</option>
                                </select></p>
            <p><label>Состояние теста на сайте:</label><select name='test_status'>
                                <option value="offline" <?php if (isset($edit) && $edited_test->test_status == 'offline') echo 'selected = "selected"';?>>Черновой вариант</option>
                                <option value="online"  <?php if (isset($edit) && $edited_test->test_status == 'online')  echo 'selected = "selected"';?>>Опубликовать на сайте</option>
                                <option value="onhold"  <?php if (isset($edit) && $edited_test->test_status == 'onhold')  echo 'selected = "selected"';?>>On hold (скажите заменить текстовку)</option>
                                </select></p>
            <p><label>Теги:</label>
                        <?php 
                            if(isset($edit)){
                                $tags='';
                                $tagmap = Tagmap::test_get_tags($edited_test->test_id);
                                foreach($tagmap as $map) {
                                    $tags[] = tag::find_by_id($map->tag_id)->tag_name;
                                }

                                $tags_string = implode(', ', $tags);
                                
                            }
                        ?>
                        <input id="tag_names" class="text-long" name="tag_names" value="<?php echo $tags_string?>">
                        </input>
                    </p>
            
            
             <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->
                
                