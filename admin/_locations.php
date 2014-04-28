<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    if (isset($_POST['delete_country'])){
        Location::delete_by_id($_POST['countries']);
    }
  
    if (isset($_POST['delete_region'])){
        Location::delete_by_id($_POST['regions']);
    }
    
    if (isset($_POST['delete_city'])){
        Location::delete_by_id($_POST['cities']);
    }
    
  
    if (isset($_POST['edit_country']))  $edit    = $_POST['countries'];
    if (isset($_POST['edit_region']))   $edit    = $_POST['regions'];
    if (isset($_POST['edit_city']))     $edit    = $_POST['cities'];
    
    if (isset($edit)) $location = Location::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['location_id']) ? $location = Location::find_by_id($_POST['location_id']) : $location = new Location();
        $location->location_name             = $_POST['location_name'];
        $location->location_type             = $_POST['location_type'];     
        $location->parent_id                 = $_POST['parent_id'];     

        $location->save();
        $_SESSION['msg'] = 'Сохранено';
    }
?>

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="locations" action="/admin/page/locations/" enctype="multipart/form-data" method="Post">
        <h3>Имеющиеся в базе</h3>
        <fieldset>
            <p><label>Страна:</label>
                <select id="countries" name="countries">
                    <?php $countries = Location::find_locations('country', '0');?>
                    <?php foreach ($countries as $country):?>
                        <option value="<?php echo $country->location_id?>"><?php echo $country->location_name?></option>
                        <?php endforeach;?>
                </select>
                <input class ="right delete" type="submit" name="delete_country" value="Удалить" /> 
                <input class ="right edit" type="submit" name="edit_country" value="Редактировать" /> 
            </p> 
            <p><label>Регион:</label>
                <select id="regions" name="regions"> 
                </select>
                <input class ="right delete" type="submit" name="delete_region" value="Удалить" /> 
                <input class ="right edit" type="submit" name="edit_region" value="Редактировать" />
            </p>
            <p><label>Город:</label>
                <select id="cities" name="cities"> 
                </select>
            <input class ="right delete" type="submit" name="delete_city" value="Удалить" /> 
            <input class ="right edit" type="submit" name="edit_city" value="Редактировать" />                        
            </p>
        </fieldset>

        <h3><?php if (isset($edit)) echo "Редактирование расположения ". Location::find_by_id($edit)->location_name; else echo "Добавление нового расположения"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="location_id" value="<?php if (isset($edit)) echo $location->location_id;?>"/></p>
            <p><label>Название:</label><input type="text" class="text-medium" name="location_name" value="<?php if (isset($edit)) echo $location->location_name;?>"/></p>
            <p><label>Тип:</label>
                <select name="location_type">
                    <option value="city" <?php if(isset($edit)) if ($location->location_type=='city') echo "selected='selected'"?>><?php echo 'Город'?></option>
                    <option value="region" <?php if(isset($edit)) if ($location->location_type=='region') echo "selected='selected'"?>><?php echo 'Регион'?></option>
                    <option value="country" <?php if(isset($edit)) if ($location->location_type=='country') echo "selected='selected'"?>><?php echo 'Страна'?></option>
                </select></p>
            <p><label>Находится в:</label>
                <select name="parent_id">
                    <?php
                        $locations = Location::find_countries_and_regions();
                        foreach ($locations as $location):
                    ?>
                        <option value="<?php echo $location->location_id?>" <?php if(isset($edit)) if ($location->location_id == Location::find_by_id($edit)->parent_id) echo "selected='selected'"?>><?php echo $location->location_name?></option>
                    <?php endforeach;?>
                </select></p>
            <input type="submit" name="save" value="Сохранить в базе"/>
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
                </div>
                <!-- // #main -->