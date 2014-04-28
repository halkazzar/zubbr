<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Speciality::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $spec = Speciality::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['university_speciality_id']) ? $spc = Speciality::find_by_id($_POST['university_speciality_id']) : $spc = new Speciality();
        $spc->university_id            = $_POST['university_id'];
        $spc->degree_id                = $_POST['degree_id'];
        $spc->studyarea_id             = $_POST['studyarea_id'];
        $spc->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    $specialities = Speciality::find_all();
    $universities = University::find_all();
    $degrees      = Degree::find_all();
    $studyareas   = Studyarea::find_all();

    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="speciality" action="/admin/page/specialities/" enctype="multipart/form-data" class="jNice" method="Post">
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if (!empty($specialities)) foreach ($specialities as $spc):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo University::find_by_id($spc->university_id)->short_name?></td>
                    <td><?php echo Degree::find_by_id($spc->degree_id)->title?></td>
                    <td><?php echo Studyarea::find_by_id($spc->studyarea_id)->title?></td>
                    <td class="action"><a href="/admin/page/specialities/<?php echo $spc->university_speciality_id?>/" class="edit">Редактировать</a><a href="#" class="delete_speciality" id="<?php echo $spc->university_speciality_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование $spec->university_speciality_id "; else echo "Добавление новой специальности"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="university_speciality_id" value="<?php if (isset($edit)) echo $spec->university_speciality_id;?>"/></p>
            <p><label>Университет:</label>
            <select name="university_id">
                <?php foreach($universities as $university):?>
                    <option value="<?php echo $university->university_id?>" <?php if(isset($edit)) if ($spec->university_id==$university->university_id) echo "selected='selected'"?>><?php echo $university->short_name?></option>
                    <?php endforeach;?>
            </select>
            </p>
            <p><label>Квалификация:</label>
            <select name="degree_id">
                <?php foreach($degrees as $degree):?>
                    <option value="<?php echo $degree->degree_id?>" <?php if(isset($edit)) if ($spec->degree_id==$degree->degree_id) echo "selected='selected'"?>><?php echo $degree->title?></option>
                    <?php endforeach;?>
            </select>
            </p>
            <p><label>Предметная область:</label>
            <select name="studyarea_id">
                <?php foreach($studyareas as $studyarea):?>
                    <option value="<?php echo $studyarea->studyarea_id?>" <?php if(isset($edit)) if ($spec->studyarea_id==$studyarea->studyarea_id) echo "selected='selected'"?>><?php echo $studyarea->title?></option>
                    <?php endforeach;?>
            </select>
            </p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->