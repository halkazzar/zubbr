<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        News::delete_by_id($_POST['delete']);
        
        //При удалении новостей, сначала удалим теги из новостей
        $tagmaps = Tagmap::news_get_tags($_POST['delete']);
        foreach($tagmaps as $map){
            Tagmap::delete_by_id($map->tagmap_id);
        }
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $art = News::find_by_id($edit);


    if (isset($_POST['save'])){

        if (!empty($_POST['news_id'])){
            $article = News::find_by_id($_POST['news_id']);    
        } else {
            $article = new News();    
        }

        if (!empty($_POST['title']))            $article->title           = $_POST['title'];
        if (!empty($_POST['description']))      $article->description     = $_POST['description'];
        if (!empty($_POST['avatar']))           $article->avatar          = $_POST['avatar'];
        if (!empty($_POST['body']))             $article->body            = $_POST['body'];
        if (!empty($_POST['date_of_publish']))  $article->date_of_publish = $_POST['date_of_publish'];

        $article->author_id       = $session->user_id;

        $article->save();

        
        
        //Now saving tags
        //1. Remove existing
        $tagmaps = Tagmap::news_get_tags($article->news_id);
        if(!empty($tagmaps)){
            foreach($tagmaps as $map){
            Tagmap::delete_by_id($map->tagmap_id);
        }
        }
        
        //2. Saving
        $tags_string = $_POST['tag_names'];
        $tag_names = tag_str_to_array($tags_string);
        
        foreach($tag_names as $tag_name){
            if(!empty($tag_name) && $tag_name != '' && $tag_name != ' '){            

                $tag = Tag::find_by_exact_name(trim($tag_name));
                if(empty($tag)){
                    $tag = new Tag;
                    $tag->tag_name = trim($tag_name);
                    $tag->is_default = 0;
                    $tag->group_id = 0;
                    $tag->save();
                }

                $new_map = new Tagmap;
                $new_map->object_type = 'news';
                $new_map->object_id = $article->news_id;
                $new_map->tag_id = $tag->tag_id;
                $new_map->save();
            }
        }
        
        
        //-------------
        
        //Now saving UNIVERSITIES
        //1. Remove existing
        $unimaps = News_uni::find_universities($article->news_id);
        if(!empty($unimaps)){
            foreach($unimaps as $map){
            News_uni::delete_by_id($map->news_uni_id);
        }
        }
        
        //2. Saving
        $unis_string = $_POST['uni_names'];
        $uni_names = tag_str_to_array($unis_string);
        
        foreach($uni_names as $uni_name){
            if(!empty($uni_name) && $uni_name != '' && $uni_name != ' '){            

                $uni = University::find_by_short_name(trim($uni_name));
                if(empty($uni)){
                    $_SESSION['err'] = 'Выбранные вузы не найдены в базе. Проверьте вузы, которые Вы выбрали.';    
                }
                // $uni should always been found in DB, cause admin seelcts it via
                // suggest list
                
                //if(empty($uni)){
//                    $uni = new University;
//                    $uni->tag_name = $tag_name;
//                    $tag->is_default = 0;
//                    $tag->group_id = 0;
//                    $tag->save();
//                }

                $new_unimap = new News_uni;
                $new_unimap->news_id = $article->news_id;
                $new_unimap->university_id = $uni->university_id;
                if($new_unimap->save()){
                $_SESSION['msg'] = 'Сохранено';    
                }
            }
        }
    }

    $news = News::find_all();
    $odd_row = 0;
?>  
<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="news" action="/admin/page/news/" enctype="multipart/form-data" method="Post">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1"><?php if (isset($edit)) echo "Редактирование статьи $art->news_id "; else echo "Добавить новость"?></a></li>
                <li><a href="#tabs-2">Имеющиеся в базе</a></li>
            </ul>
            <div id="tabs-1">
                <h3><?php if (isset($edit)) echo "Редактирование статьи $art->news_id "; else echo "Добавить новость"?></h3>
                <fieldset>
                    <p><input type="hidden" class="text-long" name="news_id" value="<?php if (isset($edit)) echo $art->news_id;?>"/></p>
                    <p><label>Заголовок новости:</label><input type="text" class="text-long" name="title" value='<?php if (isset($edit)) echo $art->title;?>'/></p>
                    <p><label>Фотка (ширина 152 px):</label><textarea rows="1" cols="1" name="avatar"><?php if (isset($edit)) echo $art->avatar;?></textarea>
                    <p><label>Введение:</label><textarea rows="1" cols="1" name="description" ><?php if (isset($edit)) echo $art->description;?></textarea>
                    <p><label>Основная часть (Введение не использовать):</label><textarea rows="10" cols="1" name="body" id="body"><?php if (isset($edit)) echo htmlspecialchars($art->body);?></textarea>
                    <p><label>Теги:</label>
                        <?php 
                            if(isset($edit)){
                                $tags='';
                                $tagmap = Tagmap::news_get_tags($art->news_id);
                                foreach($tagmap as $map) {
                                    $tags[] = tag::find_by_id($map->tag_id)->tag_name;
                                }

                                $tags_string = implode(', ', $tags);
                                
                            }
                        ?>
                        <input id="tag_names" class="text-long" name="tag_names" value="<?php echo $tags_string?>">
                        </input>
                    </p>
                    <p><label>ВУЗЫ:</label>
                        <?php 
                            if(isset($edit)){
                                $unis='';
                                $news_uni = News_uni::find_universities($art->news_id);
                                foreach($news_uni as $map) {
                                    $unis[] = University::find_by_id($map->university_id)->short_name;
                                }

                                $unis_string = implode(', ', $unis);
                                
                            }
                        ?>
                        <input id="uni_names" class="text-long" name="uni_names" value="<?php echo $unis_string?>">
                        </input>
                    </p>
                    <p><label>Дата публикации:</label><input type="text" class="text-long" name="date_of_publish" value="<?php if (isset($edit)) echo $art->date_of_publish; else echo date($dateformat)?>"/></p>
                    <input type="submit" name="save" value="Сохранить в базе" />
                    <input type="reset" value="Очистить поля" />
                </fieldset>
            </div>        
            <div id="tabs-2">        
                <h3>Имеющиеся в базе</h3>
                <table cellpadding="0" cellspacing="0">
                    <?php if(!empty($news)) foreach ($news as $article):?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><?php echo $article->title?></td>
                                <td><?php echo shorten_string($article->description, 10) .'...'?></td>
                                <td><?php echo User::find_by_id($article->author_id)->login;?></td>
                                <td><?php echo $article->date_of_publish;?></td>
                                <td class="action"><a href="/admin/page/news/<?php echo $article->news_id?>/" class="edit">Редактировать</a><a href="#" class="delete_article" id="<?php echo $article->news_id?>">Удалить</a></td>
                            </tr>                        
                            <?php $odd_row++; endforeach;?>                       
                </table>
            </div>
        </div>         
    </form>
</div>
                <!-- // #main -->
                
                