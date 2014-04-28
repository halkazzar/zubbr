<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Studyarea::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $studyarea = Studyarea::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['studyarea_id']) ? $std = Studyarea::find_by_id($_POST['studyarea_id']) : $std = new studyarea();
        $std->title                 = $_POST['title'];
        $std->save();
        $_SESSION['msg'] = 'Сохранено';

    }

    if(isset($_POST['merge_save'])){
        if(!empty($_POST['id_from']) && !empty($_POST['id_to'])){
            if($_POST['id_from'] != -1 && $_POST['id_to'] != -1){
                if($_POST['id_from'] != $_POST['id_to']){
                    if(ctype_digit($_POST['id_from']) && ctype_digit($_POST['id_to'])){
                        $from = $_POST['id_from'];    
                        $to = $_POST['id_to'];
                        
                        $relations = Relation::find_by_studyarea($from);
                        if(!empty($relations)){
                            foreach($relations as $rel){
                            $rel->studyarea_id = $to;
                            $rel->save();
                        }
                        }
                        Studyarea::delete_by_id($from);    
                    }
                }    
            }    
        }
        $_SESSION['msg'] = 'Предметные области успешно объединены';    
    }
    
    $studyareas = Studyarea::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="studyarea" action="/admin/page/studyareas/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Объединить предметные области</h3>
        <fieldset>
            <p><label>Что:</label>
                <select name="id_from">
                <option value="-1"></option>
                     <?php 
                    foreach($studyareas as $std):?>
                        <option value="<?php echo $std->studyarea_id?>"><?php echo $std->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p><label>С чем:</label>
                <select name="id_to">
                <option value="-1"></option>
                     <?php 
                    foreach($studyareas as $std):?>
                        <option value="<?php echo $std->studyarea_id?>"><?php echo $std->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <input type="submit" name="merge_save" value="Объединить" />
        </fieldset>
       <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($studyareas)) foreach ($studyareas as $std):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $std->title;?></td>
                    <td class="action"><a href="/admin/page/studyareas/<?php echo $std->studyarea_id?>/" class="edit">Редактировать</a><a href="#" class="delete_studyarea" id="<?php echo $std->studyarea_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование $studyarea->title "; else echo "Добавление новой предметной области"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="studyarea_id" value="<?php if (isset($edit)) echo $studyarea->studyarea_id;?>"/></p>
            <p><label>Официальное название:</label><input type="text" class="text-long" name="title" value="<?php if (isset($edit)) echo $studyarea->title;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->