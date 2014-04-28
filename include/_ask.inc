<?php
    /**
    * Zubr
    * Question asking block
    * To use it properly 
    *   '$current_object_id' 
    *   '$current_category' variables are required
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */
    
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
$questions = Question::find_by_object($current_category, $current_object_id);   
?>  

 
    
<div class="section network-section">
    <div class="section-title">
        <div class="left">Последние вопросы</div>
        <div class="right"><a href="/questions/cat/<?php echo $current_category?>/<?php echo $current_object_id?>/">Все вопросы</a></div>
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="section-content">
        <div class="col3-avatar_ask left avatar"> 
            <a href="#"><img src="/images/users/avatars/<?php if ($session->is_logged_in() && (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . User::find_by_id($session->user_id)->usr_id . '.jpg'))) {echo User::find_by_id($session->user_id)->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered center" /></a> 
            
        </div> 
        <div class="col3-mid-body body"> 
            <div id="ask" class="myform">
                <form name="ask" action="">
                    <input type="hidden" id="category" value="<?php if (isset($current_category)) echo $current_category;?>"></input>
                    <input type="hidden" id="object_id" value="<?php if (isset($current_object_id)) echo $current_object_id;?>"></input>
                    <label>У меня есть вопрос:</label><br />
                    <textarea name="question" id="questionText" rows="10" cols="10"></textarea>
                    
                    <button type="submit" id="submitQuestion" class="right">Задать вопрос</button>
                    <div class="clear"></div>
                    <div class="add hidden">
                    <label>Теги к вопросу:</label><br />
                    <input name="tags" id="tagsText" value="<?php echo strtolower($university->alias)?>"></input>
                    <div class="clear"></div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        <div class="clearer">&nbsp;</div>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/__questions.inc";?> 
    </div>
</div>