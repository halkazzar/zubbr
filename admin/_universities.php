<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/images/universities/avatars/" . $_POST['delete'] . ".jpg");
        University::delete_by_alias($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $univer = University::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['university_id']) ? $uni = University::find_by_id($_POST['university_id']) : $uni = new University();
        $uni->long_name                 = $_POST['long_name'];
        $uni->link                      = $_POST['link'];
        $uni->short_name                = $_POST['short_name'];
        $uni->alias                     = strtolower($_POST['alias']) ;
        $uni->date_of_creation          = $_POST['date_of_creation'];
        $uni->cost_of_year              = $_POST['cost_of_year'];
        $uni->number_of_studs           = $_POST['number_of_studs'];
        $uni->number_of_dorms           = $_POST['number_of_dorms'];
        $uni->number_of_sport           = $_POST['number_of_sport'];
        $uni->number_of_library         = $_POST['number_of_library'];
        $uni->number_of_terms           = $_POST['number_of_terms'];
        $uni->location_id               = $_POST['location_id'];
        $uni->management_form           = $_POST['management_form'];
        $uni->military_training         = $_POST['military_training'];
        $uni->short_description         = $_POST['short_description'];
        $uni->long_description          = $_POST['long_description'];
        $uni->status                    = $_POST['status'];
        $uni->picture_extension         = '.jpg';
        $uni->save();

        $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/universities/avatars/";
        $avatar = new upload($_FILES['file_upload']);
        if ($avatar->uploaded){
            $avatar->dir_auto_create         = false;
            $avatar->dir_auto_chmod          = false;
            $avatar->file_overwrite          = true;
            $avatar->allowed                 = array('image/*');
            $avatar->image_resize            = true;
            $avatar->image_ratio_y           = true;
            $avatar->image_x                 = 185;
            $avatar->file_new_name_body      = strtolower($uni->alias);
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
        
        $dir_pics = $_SERVER['DOCUMENT_ROOT'] . "/images/universities/badges/";
        $gerb = new upload($_FILES['file_upload_gerb']);
        if ($gerb->uploaded){
            $gerb->dir_auto_create         = false;
            $gerb->dir_auto_chmod          = false;
            $gerb->file_overwrite          = true;
            $gerb->allowed                 = array('image/*');
            $gerb->image_resize            = true;
            $gerb->image_ratio_y           = true;
            $gerb->image_y                 = 100;
            $gerb->file_new_name_body      = strtolower($uni->alias);
            $gerb->image_convert           = 'jpg';
            $gerb->jpeg_quality            = 80;

            $gerb->Process($dir_pics);
            if ($gerb->processed){
                //echo "Все ОК";
            }
           // else echo $avatar->error;
            $gerb->clean();
        }  
        
        
        //
        
        //Now saving synonyms
        //1. Remove existing
        $synonyms = Synonym::find_by_university($uni->university_id);
        if(!empty($synonyms)){
            foreach($synonyms as $syn){
                Synonym::delete_by_id($syn->university_synonym_id);
            }
        }
        
        //2. Saving
        if(!empty($_POST['synonyms'])){
            $syn_string = $_POST['synonyms'];
            $syn_array = tag_str_to_array($syn_string);

            foreach($syn_array as $syn){
                if(!empty($syn) && $syn != '' && $syn != ' '){
                    $new_syn = new Synonym;
                    $new_syn->title = trim($syn);
                    $new_syn->university_id = $uni->university_id;
                    $new_syn->save();
                }
            }
        }
        $_SESSION['msg'] = 'Сохранено';
    }

    if (isset($_GET['merge'])) $merge = $_GET['merge'];
    if (isset($merge)) $univer_merge = University::find_by_id($merge);
    
    if(isset($_POST['merge_save'])){
        if(!empty($_POST['university_id_from']) && !empty($_POST['university_id_to'])){
            if($_POST['university_id_from'] != -1 && $_POST['university_id_to'] != -1){
                if($_POST['university_id_from'] != $_POST['university_id_to']){
                    if(ctype_digit($_POST['university_id_from']) && ctype_digit($_POST['university_id_to'])){
                        $uni_from = $_POST['university_id_from'];    
                        $uni_to = $_POST['university_id_to'];
                        
                        $relations = Relation::find_by_university($uni_from);
                        if(!empty($relations)){
                            foreach($relations as $rel){
                            $rel->university_id = $uni_to;
                            $rel->save();
                        }
                        }
                        
                        //Synonyms of uni_to_del should become synonyms of new_uni
                        $syn_of_uni_to_del = Synonym::find_by_university($uni_from);
                        if(!empty($syn_of_uni_to_del)){
                            foreach($syn_of_uni_to_del as $syn){
                                if(!empty($syn)){
                                $syn->university_id = $uni_to;
                                $syn->save();    
                                }
                            }
                        }
                        
                        
                        //Lets save some info about uni to be deleted into
                        //a Synonym, i.e. when merging kazakh-brit to KBTU
                        //kazakh-brit becomes a synonym for KBTU
                        $uni_to_del = University::find_by_id($uni_from);
                        
                        //if(!empty($uni_to_del->short_name)){
//                        $synonym = new Synonym;
//                        $synonym->university_id = $uni_to;
//                        $synonym->title = $uni_to_del->short_name;
//                        $synonym->save();
//                        unset($synonym);    
//                        }
                        
                        if(!empty($uni_to_del->long_name)){
                            $already_exits = Synonym::find_by_title($uni_to_del->long_name);
                            if(empty($already_exits)){
                            $synonym = new Synonym;
                        $synonym->university_id = $uni_to;
                        $synonym->title = $uni_to_del->long_name;
                        $synonym->save();
                        unset($synonym);    
                            }
                        }
                        
                        //if(!empty($uni_to_del->alias)){
//                        $synonym = new Synonym;
//                        $synonym->university_id = $uni_to;
//                        $synonym->title = $uni_to_del->alias;
//                        $synonym->save();
//                        unset($synonym);    
//                        }
                        
                        University::delete_by_id($uni_from);    
                    }
                }    
            }    
        }
        $_SESSION['msg'] = 'Вузы успешно объединены';    
    }
    
    $universities = University::find_all();
    $locations = Location::find_cities();
    //$locations = Location::find_locations('city',Location::find_by_name('казахстан', 'country')->location_id);
    $odd_row = 0;
?>  
<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="university" action="/admin/page/universities/" enctype="multipart/form-data" class="jNice" method="Post">
<div id="tabs">
<ul>
        <li><a href="#tabs-1"><?php if (isset($edit)) echo "Редактирование $univer->long_name "; else echo "Добавление нового университета"?></a></li>
        <li <?php if(isset($merge)) echo 'class="ui-tabs-selected"'?>><a href="#tabs-3">Объединить вузы</a></li>
        <li><a href="#tabs-2">Имеющиеся в базе</a></li>
</ul>
<div id="tabs-1">
        <h3><?php if (isset($edit)) echo "Редактирование $univer->long_name "; else echo "Добавление нового университета"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="university_id" value="<?php if (isset($edit)) echo $univer->university_id;?>"/></p>
            <p><label>Официальное название:</label><input type="text" class="text-long" name="long_name" value="<?php if (isset($edit)) echo $univer->long_name;?>"/></p>
            <p><label>Синонимы:</label>
                        <?php
                            if(isset($edit)){
                                $syns_array= array();
                                $syns = Synonym::find_by_university($univer->university_id);
                                if(!empty($syns)){
                                    foreach($syns as $syn) {
                                        $syns_array[] = $syn->title;
                                    }
                                }

                                $syns_string = implode(', ', $syns_array);

                            }else{
                                $syns_string = ""; 
                            }
                        ?>
                        <input id="synonyms" class="text-long" name="synonyms" value="<?php echo $syns_string?>">
                        </input>
                    </p>
            <p><label>Адрес в интернете:</label><input type="text" class="text-long" name="link" value="<?php if (isset($edit)) echo $univer->link;?>"/></p>
            <p><label>Короткое название:</label><input type="text" class="text-long" name="short_name" value="<?php if (isset($edit)) echo $univer->short_name;?>"/></p>
            <p><label>Alias на английском:</label><input type="text" class="text-long" name="alias" value="<?php if (isset($edit)) echo $univer->alias;?>"/></p>
            <p><label>Год образования:</label><input type="text" class="text-long" name="date_of_creation" value="<?php if (isset($edit)) echo $univer->date_of_creation;?>"/></p>
            <br />
            <p><label>Аватарка (185x100, jpg, < 2MB)</label><input name="file_upload" type="file"/></p>
            <?php if (isset($edit)):?>
                <img src="/images/universities/avatars/<?php echo $univer->alias . $univer->picture_extension?>"/>
                <?php endif?>
            <br />
            <p><label>Герб (100x100, jpg, < 2MB)</label><input name="file_upload_gerb" type="file"/></p>
            <?php if (isset($edit)):?>
                <img src="/images/universities/badges/<?php echo $univer->alias . $univer->picture_extension?>"/>
                <?php endif?>    
            <br />
            <p><label>Стоимость года обучения:</label><input type="text" class="text-long" name="cost_of_year" value="<?php if (isset($edit)) echo $univer->cost_of_year;?>"/></p>
            <p><label>Количество студентов:</label><input type="text" class="text-long" name="number_of_studs" value="<?php if (isset($edit)) echo $univer->number_of_studs;?>"/></p>
            <p><label>Количество общежитий:</label><input type="text" class="text-long" name="number_of_dorms" value="<?php if (isset($edit)) echo $univer->number_of_dorms;?>"/></p>
            <p><label>Количество спортивных залов:</label><input type="text" class="text-long" name="number_of_sport" value="<?php if (isset($edit)) echo $univer->number_of_sport;?>"/></p>
            <p><label>Количество библиотек:</label><input type="text" class="text-long" name="number_of_library" value="<?php if (isset($edit)) echo $univer->number_of_library;?>"/></p>
            <p><label>Количество учебных модулей:</label><input type="text" class="text-long" name="number_of_terms" value="<?php if (isset($edit)) echo $univer->number_of_terms;?>" /></p>
            <p><label>Расположение:</label>
                <select name="location_id">
                    <?php foreach($locations as $location):?>
                        <option value="<?php echo $location->location_id?>" <?php if(isset($edit)) if ($univer->location_id==$location->location_id) echo "selected='selected'"?>><?php echo $location->location_name?></option>
                        <?php endforeach;?>
                </select>
            </p>
            <p><label>Форма управления:</label>
                <select name="management_form">
                    <option value="public" <?php if(isset($edit)) if ($univer->management_form=='public') echo "selected='selected'"?>>Государственная</option>
                    <option value="private" <?php if(isset($edit)) if ($univer->management_form=='private') echo "selected='selected'"?>>Частная</option>
                </select>
            </p>
            <p><label>Военная кафедра:</label>
                <select name="military_training">
                    <option value="0" <?php if(isset($edit)) if ($univer->military_training=='0') echo "selected='selected'"?>>Нет</option>
                    <option value="1" <?php if(isset($edit)) if ($univer->military_training=='1') echo "selected='selected'"?>>Есть</option>
                </select>
            </p>
            <p><label>Короткое описание:</label><textarea name="short_description" rows="10" cols="25"><?php if (isset($edit)) echo $univer->short_description;?></textarea></p>
            <p><label>Длинное описание:</label><textarea name="long_description" rows="10" cols="25"><?php if (isset($edit)) echo $univer->long_description;?></textarea></p>
            <p><label>Состояние на сайте:</label>
                <select name="status">
                    <option value="published" <?php if(isset($edit)) if ($univer->status=='published') echo "selected='selected'"?>>Опубликовать (published)</option>
                    <option value="draft"     <?php if(isset($edit)) if ($univer->status=='draft') echo "selected='selected'"?>>НЕ публиковать (draft)</option>
                </select>
            </p>
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset>
</div>        
<div id="tabs-2">        
        <h3>Имеющиеся в базе</h3>
        <table cellpadding="0" cellspacing="0">
            <?php if (!empty($universities)):?>
            <?php foreach ($universities as $uni):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $uni->long_name;?></td>
                    <td class="action">
                        <?php if($uni->status == 'draft'):?>
                        <a href="/admin/page/universities/<?php echo $uni->university_id?>/merge/">Объединить</a><br />
                        <?php endif;?>
                        <a href="/admin/page/universities/<?php echo $uni->university_id?>/" class="edit">Редактировать</a><br />
                        <a href="#" class="delete_university" id="<?php echo $uni->alias?>">Удалить</a>
                    </td>
                </tr>                        
                <?php $odd_row++; endforeach;?>
                <?php endif;?>                       
        </table>
         
</div>
<div id="tabs-3">        
        <h3>Объединить вузы</h3>
        <fieldset>
            <p><label>Что:</label>
                <select name="university_id_from">
                <option value="-1"></option>
                    <?php 
                    $draft_unis = University::find_all(0, -1, 'draft');
                    foreach($draft_unis as $uni):?>
                        <option value="<?php echo $uni->university_id?>" <?php if(isset($merge)) if ($univer_merge->university_id==$uni->university_id) echo "selected='selected'"?>><?php echo $uni->long_name?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p><label>С чем:</label>
                <select name="university_id_to">
                <option value="-1"></option>
                     <?php 
                    foreach($universities as $uni):?>
                        <option value="<?php echo $uni->university_id?>"><?php echo $uni->long_name . " (" . $uni->status . ") "?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <input type="submit" name="merge_save" value="Объединить" />
        </fieldset>          
</div>
</div>    
</form>
                </div>
                <!-- // #main -->