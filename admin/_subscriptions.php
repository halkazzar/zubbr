<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_POST['delete'])) {
        Subscription::delete_by_id($_POST['delete']);
    }

    if (isset($_GET['edit'])) $edit = $_GET['edit'];
    if (isset($edit)) $subscription = Subscription::find_by_id($edit);    

    if (isset($_POST['save'])){
        !empty($_POST['subscription_id']) ? $sub = Subscription::find_by_id($_POST['subscription_id']) : $sub = new subscription();
        $sub->title                 = $_POST['title'];
        $sub->save();
        $_SESSION['msg'] = 'Сохранено';
    }

    $subscriptions = Subscription::find_all();
    $questions = Question::find_all();
    $odd_row = 0;
?>  

<div id="main">
<?php if (isset($_SESSION['msg'])):?>
    <div class="success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
<?php endif;?>
<?php if (isset($_SESSION['err'])):?>
    <div class="error"><?php echo $_SESSION['err']; unset($_SESSION['err']); ?></div>
<?php endif;?>
    <form name="subscription" action="/admin/page/subscriptions/" enctype="multipart/form-data" method="Post">
        <h3>Поиск</h3>
        <fieldset>
            <p><label>Найти:</label><input type="text" class="text-long" id="search" value=""/></p>
        </fieldset>
        
        <h3>Имеющиеся в базе</h3>
        <div id="search_results">
        <table cellpadding="0" cellspacing="0">
            <?php if(!empty($subscriptions)) foreach ($subscriptions as $sub):?>
                <tr <?php if (is_odd($odd_row)) echo 'class = odd'?>>
                    <td><?php echo $sub->subscription_id;?></td>
                    <td><?php echo User::find_by_id($sub->user_id)->login;?></td>
                    <td><?php echo $sub->subscription_category;?></td>
                    <td><?php if ($sub->subscription_category == 'question') echo substr(Question::find_by_id($sub->category_object_id)->question_body, 0, 40);
                            ?></td>
                    <td class="action"><a href="/admin/page/subscriptions/<?php echo $sub->subscription_id?>/" class="edit">Редактировать</a><a href="#" class="delete_subscription" id="<?php echo $sub->subscription_id?>">Удалить</a></td>
                </tr>                        
                <?php $odd_row++; endforeach;?>                       
        </table>
        </div>
        <h3><?php if (isset($edit)) echo "Редактирование $subscription->subscription_id "; else echo "Добавление новой подписки"?></h3>
        <fieldset>
            <p><input type="hidden" class="text-long" name="subscription_id" value="<?php if (isset($edit)) echo $subscription->subscription_id;?>"/></p>
            <p><label>Пользователь:</label>
                <select name="user_id">
                    <?php $users = User::find_all();
                        if (!empty($users)):
                            foreach($users as $subscriber): ?>
                            <option value="<?php echo $subscriber->usr_id?>" <?php if(isset($edit)) if ($subscription->usr_id==$subscriber->usr_id) echo "selected='selected'"?>><?php echo $subscriber->login?></option>
                            <?php endforeach;
                            endif;?>
                </select>
            </p>
            <p><label>Подписка на:</label>
                <select name="subscription_category" id="subscription_category">
                    <option value="none"> </option>
                    <option value="question"   <?php if(isset($edit)) if ($subscription->subscription_category=='question') echo "selected='selected'"?>>Обновления по вопросу</option>
                </select>
            </p>
            <p><label>Объект:</label>
                <select name="category_object_id" id="category_object_id">
                 <?php if(isset($edit)) if ($subscription->subscription_category=='question')
                                foreach ($questions as $question):?>
                                <option value="<?php echo $question->question_id?>" <?php if ($question->question_id == $subscription->category_object_id) echo "selected='selected'"?>><?php echo substr($question->question_body, 0, 50)?></option>
                                <?php endforeach;?>
                 </select>
            </p>            
            
            <input type="submit" name="save" value="Сохранить в базе" />
            <input type="reset" value="Очистить поля" />
        </fieldset> 
    </form>
</div>
                <!-- // #main -->