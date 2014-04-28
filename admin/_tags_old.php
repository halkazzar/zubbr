<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    
    function delete_tag($del_tag_id){
        //Deleting from tagmap (FK restriction otherwise will raise)
        $tagmap_to_del = Tagmap::find_by_tag_id($del_tag_id);
        foreach ($tagmap_to_del as $map) {
            Tagmap::delete_by_id($map->tagmap_id);
            log_action('tagmap deleted', $map->tagmap_id);
        }

        //Deleting tag itself
        $tag_to_del = Tag::find_by_id($del_tag_id);

        //Checking if the tag is default, if so, we should set another tag to be default
        //before we delete the tag
        if($tag_to_del->is_default == 1){
            $candidate_tags = Tag::find_by_group_id($tag_to_del->group_id);

            //There is only one tag in a group, and it is default
            if(!empty($candidate_tags)){
                if (count($candidate_tags) > 1){   //NOT > 0 cause, Find_by_group_id returns the current tag too
                foreach($candidate_tags as $cand){
                    if ($cand->is_default == 0){
                        $cand->is_default = 1;
                        $cand->save();
                        break;
                    }
                }
            }
            Tag::delete_by_id($tag_to_del->tag_id);
            }
        }
        else{ //The tag is not default, so we can delete it immediately
            Tag::delete_by_id($tag_to_del->tag_id);
        }
    return 0; 
    }
    
    
    
    
    if (isset($_GET['filter_group']) && isset($_GET['ajax'])): 

        if ($_GET['filter_group'] == -1){
            $tags = tag::find_all();
        }
        elseif($_GET['filter_group'] == -2){
            //$tags = tag::find_all();
        }
        elseif($_GET['filter_group'] == -3){
            //$tags = tag::find_all();
        }
        else{
            $tags = tag::find_by_group_id($_GET['filter_group']);    
        } 
        $odd_row = 0;
    ?>


    <table cellpadding="0" cellspacing="0" id="filter_table">
        <tr>
            <th>ID</th>
            <th>Тег</th>
            <th>Группа</th>
            <th>Главный в группе</th>
            <th>Частота</th>
            <th class="action">Действия</th>
        </tr>
        <?php if (!empty($tags)) foreach ($tags as $tag):
                    $tag_map = tagmap::find_by_tag_id($tag->tag_id);
                    if(!empty($tag_map)){
                        $count = count($tag_map);    
                    }
                    else{
                        $count = 0;
                    }
                    
                ?>


                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $tag->tag_id?></td>
                    <td><?php echo $tag->tag_name?></td>
                    <td><?php echo $tag->group_id?></td>
                    <td><?php echo $tag->is_default?></td>
                    <td><?php echo $count?></td>

                    <td class="action">
                        <a href="/admin/page/tags/<?php echo $tag->tag_id?>/" class="edit">Редактировать</a>
                        <a href="#" class="delete_tag" id="<?php echo $tag->tag_id?>">Удалить</a>
                    </td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
    </table>

    <?php endif;?>


<?php
    if (isset($_POST['merge'])){
        $from = $_POST['tag_from'];
        $to = $_POST['tag_to'][0];       //Array is used, but we need only one element
        
        foreach($from as $from_name){
            $tagmaps = Tagmap::find_by_tag_id(Tag::find_by_exact_name($from_name)->tag_id);
            foreach($tagmaps as $map){

                //Checking if there is a tagmap with TO_tag already
                $another_map = Tagmap::find_by_object_type_object_id_tag_id($map->object_type, $map->object_id, Tag::find_by_exact_name($to)->tag_id);
                
                if(empty($another_map)){ //Nope, yet, so can change the tag
                $map->tag_id = Tag::find_by_exact_name($to)->tag_id;
                $map->save();    
                }
            }
        }
        
        //Deleting FROM tags
        foreach($from as $from_name){
            $tag_from = Tag::find_by_exact_name($from_name);
            if(!empty($tag_from)){
                delete_tag($tag_from->tag_id);
            }
        }
    }

    if (isset($_POST['delete_tag'])) {

        delete_tag($_POST['delete_tag']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $tag_edit = tag::find_by_id($_GET['edit']);    




    if (isset($_POST['save'])){
        !empty($_POST['tag_id']) ? $t = tag::find_by_id($_POST['tag_id']) : $t = new Tag;
        //if field is disabled, $_POST value is not set
        $t->tag_name     = $_POST['tag_name'];

        if(isset($_POST['is_default'])){
            $t->is_default  = 1;
        }
        else{
            $t->is_default  = 0;
        }

        if($_POST['group_id'] == -1){
            $t->group_id = Tag::get_last_group() + 1;     
        }
        else{
            $t->group_id  = $_POST['group_id'];
        }
        $t->save();
    }


    $tags = tag::find_all();
    $groups = tag::find_groups();
    $odd_row = 0;

    if (!isset($_GET['ajax'])): 
    ?>  

    <div id="main">
        <form name="tags" action="/admin/page/tags/" enctype="multipart/form-data" method="Post">
            <div id="tabs">
            <ul>
                <li><a href="#tabs-1"><?php if (isset($edit)) echo "Редактирование группы тегов "; else echo "Добавление новой группы тегов"?></a></li>
                <li><a href="#tabs-2">Имеющиеся в базе</a></li>
            </ul>
            <div id="tabs-1">
            <h3><?php if (isset($edit)) echo "Редактирование группы тегов "; else echo "Добавление новой группы тегов"?></h3>
            <fieldset>
                <p><input type="hidden" class="text-long" name="tag_id" value="<?php if (isset($edit)) echo $tag_edit->tag_id;?>"/></p>

                <p><label>Тег:</label>
                    <input type="text" class="text-long" name="tag_name" id="tag_name" value="<?php if (isset($edit)) echo $tag_edit->tag_name;?>"> 
                </p>
                <p><label>Группа:</label>
                    <select id="group_id" name="group_id" class="text-long">
                        <option value="-1">Новая группа</option>
                        <?php foreach($groups as $g):
                                $temp_tag = tag::find_by_group_id($g->group_id);
                            ?>
                            <option value="<?php echo $g->group_id?>" <?php if (isset($edit)) { if($tag_edit->group_id == $g->group_id) echo 'selected = "selected"';}?>>Группа тегов <?php echo $g->group_id?> (<?php echo $temp_tag[0]->tag_name .' '. $temp_tag[1]->tag_name .' '. $temp_tag[2]->tag_name .'...'?>)</option>
                            <?php endforeach;?> 
                    </select>
                </p>

                <p>
                    <input type="checkbox" name="is_default" id="is_default" value="1" <?php if (isset($edit)) if ($tag_edit->is_default){echo 'checked="checked"';}?>>
                    <label>Является главным в группе:</label>
                </p>


                <input type="submit" name="save" value="Сохранить" />
                <input type="reset" value="Очистить поля" />
            </fieldset>
        </form>
    </div>        
    <div id="tabs-2">        
        <h3>Слияние тегов</h3>
        <form name="tags_merge" action="/admin/page/tags/" enctype="multipart/form-data" method="Post">
            <fieldset>
                <label>Найти теги:</label>
                <select id="tag_from" name="tag_from">
                </select>
                <label>Заменить на:</label>
                <select id="tag_to" name="tag_to">
                </select>
                <input type="submit" name="merge" value="Сохранить" />
            </fieldset>
        </form> 

        <h3>Имеющиеся в базе</h3>
        <fieldset>
            <p><label>Отобразить группу тегов:</label>
            <select id="tag_group" name="tag_group">
                <option value="-1" selected="selected">Все теги</option>
                <option value="-2">Популярные</option>
                <option value="-3">НЕ популярные</option>
                <?php 
                    foreach($groups as $g):
                        $temp_tag = tag::find_by_group_id($g->group_id);
                    ?>
                    <option value="<?php echo $g->group_id?>">Группа тегов <?php echo $g->group_id?> (<?php echo $temp_tag[0]->tag_name .' '. $temp_tag[1]->tag_name .' '. $temp_tag[2]->tag_name .'...'?>)</option>
                    <?php endforeach;?>
            </select>
        </fieldset>
        <div id="filter_results">
            <table cellpadding="0" cellspacing="0" id="filter_table">
                <tr>
                    <th>ID</th>
                    <th>Тег</th>
                    <th>Группа</th>
                    <th>Главный в группе</th>
                    <th>Частота</th>
                    <th class="action">Действия</th>
                </tr>

                <?php if (!empty($tags)) foreach ($tags as $tag):
                            $tag_map = tagmap::find_by_tag_id($tag->tag_id);
                            if(!empty($tag_map)){
                                $count = count($tag_map);
                            }
                            else{
                                $count = 0;
                            }
                            
                        ?>
                        <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                            <td><?php echo $tag->tag_id?></td>
                            <td><?php echo $tag->tag_name?></td>
                            <td><?php echo $tag->group_id?></td>
                            <td><?php echo $tag->is_default?></td>
                            <td><?php echo $count?></td>
                            <td class="action">
                                <a href="/admin/page/tags/<?php echo $tag->tag_id?>/" class="edit">Редактировать</a>
                                <a href="#" class="delete_tag" id="<?php echo $tag->tag_id?>">Удалить</a>
                            </td>
                        </tr>                        
                        <?php $odd_row++; endforeach;?>                       
            </table>    
        </div> 

    </div>
    </div>

    </div>
    <!-- // #main -->
    <?php endif;?>