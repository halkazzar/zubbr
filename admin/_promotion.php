<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Stats_UniversityView::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $stat = Stats_UniversityView::find_by_id($edit);    

    if (isset($_POST['save'])){
        
        if (!empty($_POST['stats_university_views_id'])){
            $std = Stats_UniversityView::find_by_id($_POST['stats_university_views_id']);    
        } else {
            $std = new Stats_UniversityView();    
        }
        
        if (!empty($_POST['university_id'])) $std->university_id   = $_POST['university_id'];
        if (!empty($_POST['promotion_type'])) $std->promotion_type = $_POST['promotion_type'];
        if (!empty($_POST['date_of_start'])) $std->date_of_start   = $_POST['date_of_start'];
        if (!empty($_POST['date_of_end'])) $std->date_of_end       = $_POST['date_of_end'];
        
        $std->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    $stats = Stats_UniversityView::find_all();
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
    <form name="promotion" action="/admin/page/promotion/" enctype="multipart/form-data" method="Post">
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($stats)) foreach ($stats as $std):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo University::find_by_id($std->university_id)->short_name;?></td>
                    <td><?php echo $std->promotion_type;?></td>
                    <td><?php echo $std->date_of_start;?></td>
                    <td><?php echo $std->date_of_end;?></td>
                    <td><?php echo $std->views;?></td>
                    <td class="action"><a href="/admin/page/promotion/<?php echo $std->stats_university_views_id?>/" class="edit">Редактировать</a><a href="#" class="delete_promotion" id="<?php echo $std->stats_university_views_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        <h3><?php if (isset($edit)) echo "Редактирование записи $stat->stats_university_views_id "; else echo "Промоутить вуз"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="stats_university_views_id" value="<?php if (isset($edit)) echo $stat->stats_university_views_id;?>"/></p>
            <p><label>Университет:</label>
            <select name="university_id" <?php if(isset($edit)) echo "disabled='disabled'"?>>
                <?php foreach($universities as $university):?>
                    <option value="<?php echo $university->university_id?>" <?php if(isset($edit)) if ($stat->university_id==$university->university_id) echo "selected='selected'"?>><?php echo $university->short_name?></option>
                    <?php endforeach;?>
            </select>
            </p>
            <p><label>Вид промоушна (выберите "Простой сбор статистики" для простого подсчета посещений страницы вуза):</label>
                <select name="promotion_type" <?php if(isset($edit)) echo "disabled='disabled'"?>>
                    <option value="paid" <?php if(isset($edit)) if ($stat->promotion_type=='paid') echo "selected='selected'"?>>Promoted</option>
                    <option value="free"   <?php if(isset($edit)) if ($stat->promotion_type=='free') echo "selected='selected'"?>>Вуз Недели</option>
                    <option value="none"   <?php if(isset($edit)) if ($stat->promotion_type=='none') echo "selected='selected'"?>>Простой сбор статистики</option>
                </select>
            </p>
            <p><label>Начало промоушна (включительно):</label><input type="text" class="text-long" name="date_of_start" value="<?php if (isset($edit)) echo $stat->date_of_start;?>" <?php if (isset($edit)) echo 'disabled="diabled"'?>/></p>
            <p><label>Конец промоушна (включительно):</label><input type="text" class="text-long" name="date_of_end" value="<?php if (isset($edit)) echo $stat->date_of_end;?>"/></p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->