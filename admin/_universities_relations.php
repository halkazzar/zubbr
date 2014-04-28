<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Relation::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $relation = Relation::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['university_relation_id']) ? $rel  = Relation::find_by_id($_POST['university_relation_id']) : $rel  = new Relation();
        $rel->university_id                 = $_POST['university_id'];
        $rel->user_id                       = $_POST['user_id'];
        $rel->role                          = $_POST['role'];
        $rel->graduate_year                 = $_POST['graduate_year'];
        $rel->save();
        $_SESSION['msg'] = 'Сохранено';

    }

    $relations = Relation::find_all();
    $users = User::find_all();
    $universities = University::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="degree" action="/admin/page/universities_relations/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Поиск</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>        
        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if(!empty($relations)) foreach ($relations as $rel):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo $rel->university_relation_id;?></td>
                            <td><?php echo User::find_by_id($rel->user_id)->login;?></td>
                            <td><?php echo University::find_by_id($rel->university_id)->short_name;?></td>
                            <td><?php echo $rel->role?></td>
                            <td><?php echo $rel->graduate_year?></td>
                            <td><?php echo Scholarship::find_by_id($rel->scholarship_id)->title?></td>
                            <td class="action"><a href="/admin/page/universities_relations/<?php echo $rel->university_relation_id?>/" class="edit">Редактировать</a><a href="#" class="delete_university_relation" id="<?php echo $rel->university_relation_id?>">Удалить</a></td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>
        </div>

        <h3><?php if (isset($edit)) echo "Редактирование $relation->university_relation_id "; else echo "Добавление нового отношения"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="university_relation_id" value="<?php if (isset($edit)) echo $relation->university_relation_id;?>"/></p>
            <p><label>Пользователь:</label>
                <select name="user_id">
                    <?php foreach($users as $user):?>
                        <option value="<?php echo $user->usr_id?>" <?php if(isset($edit)) if ($relation->user_id==$user->usr_id) echo "selected='selected'"?>><?php echo $user->login?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Университет:</label>
                <select name="university_id">
                    <?php foreach($universities as $university):?>
                        <option value="<?php echo $university->university_id?>" <?php if(isset($edit)) if ($relation->university_id==$university->university_id) echo "selected='selected'"?>><?php echo $university->short_name?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Связь:</label>
                <select name="role">
                    <option value="alumni" <?php if(isset($edit)) if ($relation->role=='alumni') echo "selected='selected'"?>><?php echo 'Выпускник'?></option>
                    <option value="student" <?php if(isset($edit)) if ($relation->role=='student') echo "selected='selected'"?>><?php echo 'Студент'?></option>
                    <option value="abitur" <?php if(isset($edit)) if ($relation->role=='abitur') echo "selected='selected'"?>><?php echo 'Желает здесь учиться'?></option>
                </select>
            </p>
            <p><label>Год окончания (только для студентов и выпускников):</label><input type="text" class="text-medium" name="graduate_year" value="<?php if (isset($edit)) echo $relation->graduate_year;?>"/></p>
            <input type="submit" name="save" value="Отправить" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->