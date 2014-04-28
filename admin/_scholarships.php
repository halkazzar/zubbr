<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Scholarship::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $scholarship = Scholarship::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['scholarship_id']) ? $sch = Scholarship::find_by_id($_POST['scholarship_id']) : $sch = new Scholarship();
        $sch->title                 = $_POST['title'];
        $sch->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    if(isset($_POST['merge_save'])){
        if(!empty($_POST['scholarship_id_from']) && !empty($_POST['scholarship_id_to'])){
            if($_POST['scholarship_id_from'] != -1 && $_POST['scholarship_id_to'] != -1){
                if($_POST['scholarship_id_from'] != $_POST['scholarship_id_to']){
                    if(ctype_digit($_POST['scholarship_id_from']) && ctype_digit($_POST['scholarship_id_to'])){
                        log_action('entering to merge');
                        $from = $_POST['scholarship_id_from'];    
                        $to = $_POST['scholarship_id_to'];
                        
                        $relations = Relation::find_by_scholarship($from);
                        if(!empty($relations)){
                            foreach($relations as $rel){
                            $rel->scholarship_id = $to;
                            $rel->save();
                        }
                        }
                        Scholarship::delete_by_id($from);    
                    }
                }    
            }    
        }
        $_SESSION['msg'] = 'Стипендии успешно объединены';    
    }
    
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
    <form name="scholarship" action="/admin/page/scholarships/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Объединить стипендии</h3>
        <fieldset>
            <p><label>Что:</label>
                <select name="scholarship_id_from">
                <option value="-1"></option>
                     <?php 
                    foreach($scholarships as $sch):?>
                        <option value="<?php echo $sch->scholarship_id?>"><?php echo $sch->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p><label>С чем:</label>
                <select name="scholarship_id_to">
                <option value="-1"></option>
                     <?php 
                    foreach($scholarships as $sch):?>
                        <option value="<?php echo $sch->scholarship_id?>"><?php echo $sch->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <input type="submit" name="merge_save" value="Объединить" />
        </fieldset>
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($scholarships)) foreach ($scholarships as $sch):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $sch->title;?></td>
                    <td class="action"><a href="/admin/page/scholarships/<?php echo $sch->scholarship_id?>/" class="edit">Редактировать</a><a href="#" class="delete_scholarship" id="<?php echo $sch->scholarship_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование $scholarship->title "; else echo "Добавление новой программы образования (стипеднии)"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="scholarship_id" value="<?php if (isset($edit)) echo $scholarship->scholarship_id;?>"/></p>
            <p><label>Официальное название:</label><input type="text" class="text-long" name="title" value="<?php if (isset($edit)) echo $scholarship->title;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->