<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Degree::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $degree = Degree::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['degree_id']) ? $deg = Degree::find_by_id($_POST['degree_id']) : $deg = new Degree();
        $deg->title                 = $_POST['title'];
        $deg->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    if (isset($_GET['merge'])) $merge = $_GET['merge'];
    if (isset($merge)) $deg_merge = Degree::find_by_id($merge);
    
    if(isset($_POST['merge_save'])){
        if(!empty($_POST['degree_id_from']) && !empty($_POST['degree_id_to'])){
            if($_POST['degree_id_from'] != -1 && $_POST['degree_id_to'] != -1){
                if($_POST['degree_id_from'] != $_POST['degree_id_to']){
                    if(ctype_digit($_POST['degree_id_from']) && ctype_digit($_POST['degree_id_to'])){
                        $degree_from = $_POST['degree_id_from'];    
                        $degree_to = $_POST['degree_id_to'];
                        
                        $relations = Relation::find_by_degree($degree_from);
                        if(!empty($relations)){
                            foreach($relations as $rel){
                            $rel->degree_id = $degree_to;
                            $rel->save();
                        }
                        }
                        Degree::delete_by_id($degree_from);    
                    }
                }    
            }    
        }    
    $_SESSION['msg'] = 'Квалификации объединены успешно';
    }
    
    
    $degrees = Degree::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="degree" action="/admin/page/degrees/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Объединить степени</h3>
        <fieldset>
            <p><label>Что:</label>
                <select name="degree_id_from">
                <option value="-1"></option>
                     <?php 
                    foreach($degrees as $deg):?>
                        <option value="<?php echo $deg->degree_id?>"><?php echo $deg->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p><label>С чем:</label>
                <select name="degree_id_to">
                <option value="-1"></option>
                     <?php 
                    foreach($degrees as $deg):?>
                        <option value="<?php echo $deg->degree_id?>"><?php echo $deg->title?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <input type="submit" name="merge_save" value="Объединить" />
        </fieldset>          
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($degrees)) foreach ($degrees as $deg):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $deg->title;?></td>
                    <td class="action"><a href="/admin/page/degrees/<?php echo $deg->degree_id?>/" class="edit">Редактировать</a><a href="#" class="delete_degree" id="<?php echo $deg->degree_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование $degree->title "; else echo "Добавление новой академической степени (квалификации)"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="degree_id" value="<?php if (isset($edit)) echo $degree->degree_id;?>"/></p>
            <p><label>Официальное название:</label><input type="text" class="text-long" name="title" value="<?php if (isset($edit)) echo $degree->title;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->