<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Jobposting::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $job = Jobposting::find_by_id($edit);    

    if (isset($_POST['save'])){

        if (!empty($_POST['jobposting_id'])){
            $post = Jobposting::find_by_id($_POST['jobposting_id']);    
        } else {
            $post = new Jobposting();    
        }

        if (!empty($_POST['title']))            $post->title           = $_POST['title'];
        if (!empty($_POST['body']))             $post->body            = $_POST['body'];
        if (!empty($_POST['date_of_publish']))  $post->date_of_publish = $_POST['date_of_publish'];

        $post->author_id       = $session->user_id;

        $post->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    $jobpostings = Jobposting::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="Jobposting" action="/admin/page/jobpostings/" enctype="multipart/form-data" method="Post">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1"><?php if (isset($edit)) echo "Редактирование статьи $article->news_id "; else echo "Добавить новость"?></a></li>
                <li><a href="#tabs-2">Имеющиеся в базе</a></li>
            </ul>
            <div id="tabs-1">
                <h3><?php if (isset($edit)) echo "Редактирование объявления $post->jobposting_id "; else echo "Добавить объявление"?></h3>
                <fieldset>
                    <p><input type="hidden" class="text-long" name="jobposting_id" value="<?php if (isset($edit)) echo $job->jobposting_id;?>"/></p>
                    <p><label>Заголовок объявления:</label><input type="text" class="text-long" name="title" value="<?php if (isset($edit)) echo $job->title;?>"/></p>
                    <p><label>Основная часть:</label><textarea rows="1" cols="1" name="body" id="body"><?php if (isset($edit)) echo htmlspecialchars($job->body);?></textarea>
                    <p><label>Дата публикации:</label><input type="text" class="text-long" name="date_of_publish" value="<?php if (isset($edit)) echo $job->date_of_publish; else echo date($dateformat)?>"/></p>
                    <input type="submit" name="save" value="Сохранить в базе" />
                    <input type="reset" value="Очистить поля" />
                </fieldset>
            </div>
            <div id="tabs-2">
                <h3>Имеющиеся в базе</h3>
                <table cellpadding="0" cellspacing="0">
                    <?php if(!empty($jobpostings)) foreach ($jobpostings as $post):?>
                            <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                                <td><?php echo $post->title?></td>
                                <td><?php echo shorten_string($post->body, 10)?></td>
                                <td><?php echo User::find_by_id($post->author_id)->login;?></td>
                                <td><?php echo $post->date_of_publish;?></td>
                                <td class="action"><a href="/admin/page/jobpostings/<?php echo $post->jobposting_id?>/" class="edit">Редактировать</a><a href="#" class="delete_jobposting" id="<?php echo $post->jobposting_id?>">Удалить</a></td>
                            </tr>                        
                            <?php $odd_row++; endforeach;?>                       
                </table>
            </div>
        </div>    


    </form>
</div>
                <!-- // #main -->
                
                