<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    //if (isset($_POST['delete'])) {
//        unlink($_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/" . $_POST['delete'] . ".jpg");
//        User::delete_by_login($_POST['delete']);
//        
        //We should remove everything which are linked to this user
//        $user_relations = Relation::find_by_user($_POST['delete']);
//        foreach ($user_relations as $rel) Relation::delete_by_id($rel->relation_id);
//        
//        $subscriptions_to_del = Subscription::find_by_user($_POST['delete']);
//        foreach ($subscriptions_to_del as $sub) Subscription::delete_by_id($sub->subscription_id);
//        
//        $questions_to_del = Question::find_by_user($_POST['delete']);
//        foreach ($questions_to_del as $que) Question::delete_by_id($que->question_id);
//        
//        $answers_to_del = Answer::find_by_user($_POST['delete']);
//        foreach ($answers_to_del as $ans) Answer::delete_by_id($ans->question_id);
//        
        //TODO Delete COMPLAINT
        //TODO Delete USER ACHIEVEMENTS
//    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $user_edit = User::find_by_login($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['usr_id']) ? $usr = User::find_by_id($_POST['usr_id']) : $usr = new User();
        //if field is disabled, $_POST value is not set
        $usr->login                     = $_POST['login'];
        if (isset($_POST['password']))
            $usr->password                  = md5($_POST['password']);        
        $usr->email                     = $_POST['email'];
        $usr->last_visit                = $_POST['last_visit'];
        $usr->system_role               = $_POST['system_role'];
        $usr->first_name                = $_POST['first_name'];
        $usr->last_name                 = $_POST['last_name'];
        $usr->location_id               = $_POST['location_id'];
        $usr->date_of_birth             = $_POST['date_of_birth'];
        $usr->date_of_join              = $_POST['date_of_join'];
        $usr->picture_extension         = '.jpg';

        $usr->save();

        $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/users/avatars/";
        $avatar = new upload($_FILES['file_upload']);
        if ($avatar->uploaded){
            $avatar->dir_auto_create         = false;
            $avatar->dir_auto_chmod          = false;
            $avatar->file_overwrite          = true;
            $avatar->allowed                 = array('image/*');
            $avatar->image_resize            = true;
            $avatar->image_ratio_y           = true;
            $avatar->image_x                 = 50;
            $avatar->file_new_name_body      = $usr->login;
            $avatar->image_convert           = 'jpg';
            $avatar->jpeg_quality            = 80;

            $avatar->Process($dir_pics);
            if ($avatar->processed){
                //echo "Все ОК";
            }
            //else echo $avatar->error;
            $avatar->clean();
        }
        //else echo $avatar->error;
        $_SESSION['msg'] = 'Сохранено';        
    }


    $locations = Location::find_cities();
    $users = User::find_all();
    $odd_row = 0; 
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="user" action="/admin/page/users/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Поиск</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>

        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
            <table cellpadding="0" cellspacing="0" id="result_table">
                <?php if (!empty($users)) foreach ($users as $user):?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo $user->login;?></td>
                            <td><?php echo $user->email;?></td>
                            <td><?php echo $user->system_role;?></td>
                            <td class="action">
                            <a href="/admin/page/users/<?php echo $user->login?>/" class="edit">Редактировать</a>
                            <!--<a href="#" class="delete_user" id="<?php //echo $user->login?>">Удалить</a> -->
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>
        </div>
        <h3><?php if (isset($edit)) echo "Редактирование $user_edit->login "; else echo "Добавление нового пользователя"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="usr_id" value="<?php if (isset($edit)) echo $user_edit->usr_id;?>"/></p>
            <p><label>Имя пользователя:</label><input type="text" class="text-long" name="login" value="<?php if (isset($edit)) echo $user_edit->login;?>"/></p>
            <p><label>Пароль:</label><input type="text" class="text-long" name="password" value="<?php if (isset($edit)) echo $user_edit->password;?>" <?php if (isset($edit)) echo 'disabled="diabled"'?>/></p>
            <p><label>E-mail:</label><input type="text" class="text-long" name="email" value="<?php if (isset($edit)) echo $user_edit->email;?>"/></p>
            <p><label>Имя:</label><input type="text" class="text-long" name="first_name" value="<?php if (isset($edit)) echo $user_edit->first_name;?>"/></p>
            <p><label>Фамилия:</label><input type="text" class="text-long" name="last_name" value="<?php if (isset($edit)) echo $user_edit->last_name;?>"/></p>
            <p><label>Дата рождения:</label><input type="text" class="text-long" name="date_of_birth" value="<?php if (isset($edit)) echo $user_edit->date_of_birth;?>"/></p>
            <p><label>Системная роль:</label>
                <select name="system_role">
                    <option value="visiter" <?php if(isset($edit)) if ($user_edit->system_role=='visiter') echo "selected='selected'"?>>Посетитель</option>
                    <option value="admin"   <?php if(isset($edit)) if ($user_edit->system_role=='admin') echo "selected='selected'"?>>Администратор</option>
                    <option value="moder"   <?php if(isset($edit)) if ($user_edit->system_role=='moder') echo "selected='selected'"?>>Модератор</option>
                </select>
            </p>
            <p><label>Аватарка (50x50, jpg, < 2MB)</label><input name="file_upload" type="file"/></p>
            <?php if (isset($edit)):?>
                <img src="/images/users/avatars/<?php echo $user_edit->login . $user_edit->picture_extension?>"/>
                <?php endif?>
            <p><label>Расположение:</label>
                <select name="location_id">
                    <?php foreach($locations as $location):?>
                        <option value="<?php echo $location->location_id?>" <?php if(isset($edit)) if ($user_edit->location_id==$location->location_id) echo "selected='selected'"?>><?php echo $location->location_name?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->