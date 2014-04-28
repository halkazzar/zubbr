<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/images/organizations/avatars/" . $_POST['delete'] . ".jpg");
        Organization::delete_by_alias($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $organ = Organization::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['organization_id']) ? $org = Organization::find_by_id($_POST['organization_id']) : $org = new Organization();
        $org->long_name                 = $_POST['long_name'];
        $org->short_name                = $_POST['short_name'];
        $org->alias                     = $_POST['alias'];
        $org->short_description         = $_POST['short_description'];
        $org->long_description          = $_POST['long_description'];
        $org->picture_extension         = '.jpg';
        $org->save();

        $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/organizations/avatars/";
        $avatar = new upload($_FILES['file_upload']);
        if ($avatar->uploaded){
            $avatar->dir_auto_create         = false;
            $avatar->dir_auto_chmod          = false;
            $avatar->file_overwrite          = true;
            $avatar->allowed                 = array('image/*');
            $avatar->image_resize            = true;
            $avatar->image_ratio_y           = true;
            $avatar->image_x                 = 185;
            $avatar->file_new_name_body      = $org->alias;
            $avatar->image_convert           = 'jpg';
            $avatar->jpeg_quality            = 80;

            $avatar->Process($dir_pics);
            if ($avatar->processed){
                //echo "Все ОК";
            }
           // else echo $avatar->error;
            $avatar->clean();
        }
        //else echo $avatar->error;        
    $_SESSION['msg'] = 'Сохранено';
    }

    $organizations = Organization::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="organization" action="/admin/page/organizations/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if (!empty($organizations)):?>
            <?php foreach ($organizations as $org):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $org->long_name;?></td>
                    <td class="action"><a href="/admin/page/organizations/<?php echo $org->organization_id?>/" class="edit">Редактировать</a><a href="#" class="delete_organization" id="<?php echo $org->alias?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>
                <?php endif;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование $organ->long_name "; else echo "Добавление нового университета"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="organization_id" value="<?php if (isset($edit)) echo $organ->organization_id;?>"/></p>
            <p><label>Официальное название:</label><input type="text" class="text-long" name="long_name" value="<?php if (isset($edit)) echo $organ->long_name;?>"/></p>
            <p><label>Короткое название:</label><input type="text" class="text-long" name="short_name" value="<?php if (isset($edit)) echo $organ->short_name;?>"/></p>
            <p><label>Короткое название (англ):</label><input type="text" class="text-long" name="alias" value="<?php if (isset($edit)) echo $organ->alias;?>"/></p>
            <p><label>Аватарка (185x100, jpg, < 2MB)</label><input name="file_upload" type="file"/></p>
            <?php if (isset($edit)):?>
                <img src="/images/organizations/avatars/<?php echo $organ->alias . $organ->picture_extension?>"/>
                <?php endif?>
            <p><label>Короткое описание:</label><textarea name="short_description" rows="10" cols="25"><?php if (isset($edit)) echo $organ->short_description;?></textarea></p>
            <p><label>Длинное описание:</label><textarea name="long_description" rows="10" cols="25"><?php if (isset($edit)) echo $organ->long_description;?></textarea></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->