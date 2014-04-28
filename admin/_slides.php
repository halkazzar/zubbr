<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    if (isset($_POST['delete'])) {
        $arg=explode('_',$_POST['delete']);
        
        unlink($_SERVER['DOCUMENT_ROOT'] . "/images/universities/slides/" . $_POST['delete'] . ".jpg");
        UniversitySlide::delete_by_id($arg[1]);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $slide = UniversitySlide::find_by_id($edit);    
    
    if (isset($_POST['save'])){
        !empty($_POST['slide_id']) ? $slide = UniversitySlide::find_by_id($_POST['slide_id']) : $slide = new UniversitySlide();
        $slide->university_id             = $_POST['university_id'];
        $slide->label                     = $_POST['label'];     
        $slide->link                      = $_POST['link'];     
        $slide->picture_extension         = '.jpg';
        $slide->save();
        
        if (empty($slide->slides_id)){
        global $database;
        $id = $database->insert_id();
        }
        else $id = $_POST['slide_id'];
        
        $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/universities/slides/";
        $handle = new upload($_FILES['file_upload']);
        if ($handle->uploaded){
            $handle->dir_auto_create         = false;
            $handle->dir_auto_chmod          = false;
            $handle->file_overwrite          = true;
            $handle->allowed                 = array('image/*');
            $handle->image_resize            = true;
            $handle->image_ratio_crop        = 'L';
            //$handle->image_ratio_x           = false;
            $handle->image_y                 = 314;
            $handle->image_x                 = 680;
            $handle->file_new_name_body      = $slide->university_id . '_' . $id;
            $handle->image_convert           = 'jpg';
            $handle->jpeg_quality            = 80;

            $handle->Process($dir_pics);
            if ($handle->processed){
                echo "Все ОК";
            }
             else echo $avatar->error;
            $handle->clean();
        }
        else echo $avatar->error;
        $_SESSION['msg'] = 'Сохранено';        
    }


    $universities = University::find_all();
    $slides = UniversitySlide::find_all();
    $odd_row = 0;    
?>

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="slide" action="/admin/page/slides/" enctype="multipart/form-data" class="jNice" method="Post">
<div id="tabs">
<ul>
        <li><a href="#tabs-1"><?php if (isset($edit)) echo "Редактирование слайда $edit"; else echo "Добавление нового слайда"?></a></li>
        <li><a href="#tabs-2">Имеющиеся в базе</a></li>
    </ul>
<div id="tabs-1">    
    <h3><?php if (isset($edit)) echo "Редактирование слайда $edit"; else echo "Добавление нового слайда"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="slide_id" value="<?php if (isset($edit)) echo $slide->slides_id;?>"/></p>
            <p><label>Университет:</label>
                <select name="university_id">
                    <?php foreach($universities as $univer):?>
                        <option value="<?php echo $univer->university_id?>" <?php if(isset($edit)) if ($univer->university_id==$slide->university_id) echo "selected='selected'"?>><?php echo $univer->short_name?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Слайд (680x317, jpg, < 2MB)</label><input name="file_upload" type="file"/></p>
            <?php if (isset($edit)):?>
                <img src="/images/universities/slides/<?php echo $slide->university_id . '_' . $slide->slides_id . $slide->picture_extension?>"/>
                <?php endif?>
            <p><p><label>Подпись к слайду:</label><input type="text" class="text-long" name="label" value="<?php if (isset($edit)) echo $slide->label;?>"/></p>
            <p><p><label>Ссылка:</label><input type="text" class="text-long" name="link" value="<?php if (isset($edit)) echo $slide->link;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset>
    
    
    
</div>        
<div id="tabs-2">
<h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php foreach ($slides as $sli):?>
                <?php $uni = University::find_by_id($sli->university_id)?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $uni->short_name?></td>
                    <td><img src = "/images/universities/slides/<?php echo $sli->university_id . '_' . $sli->slides_id . $sli->picture_extension;?>" class="slide_preview"/></td>
                    <td class="action"><a href="/admin/page/slides/<?php echo $sli->slides_id?>/" class="edit">Редактировать</a><a href="#" class="delete_slide" id="<?php echo $sli->university_id . '_' . $sli->slides_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>         
</div>
</div>    
</form>
                </div>
                <!-- // #main -->