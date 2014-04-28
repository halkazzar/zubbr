<?php if (isset($error_code)):?>
<div id="messagebox" class="hidden">
    <div class="center text-center"><?php echo_message($error_code); unset($error_code); ?></div>
</div>    
<?php endif;?>
<noscript>
<div id="messagebox">
    <div class="center text-center"><div class="error">Включите поддержку JavaScript</div></div>
</div>
</noscript>

<div id="navigation">
    <div id="main-nav">
        <ul class="tabbed">
            <li <?if ($sub_navigation=='main') echo ' class="current-tab"';?>><a href="/"><?php echo NAV_MAIN?></a></li>
            <li <?if ($sub_navigation=='tests') echo ' class="current-tab"';?>><a href="/test/">Тесты</a></li>
            <li <?if ($sub_navigation=='universities') echo ' class="current-tab"';?>><a href="/uni/">Вузы</a></li>
            <li <?if ($sub_navigation=='grants') echo ' class="current-tab"';?>><a href="/grants/">Гранты</a></li>
            <li <?if ($sub_navigation=='users') echo ' class="current-tab"';?>><a href="/users/">Пипл</a></li>
            <li <?if ($sub_navigation=='qa') echo ' class="current-tab"';?>><a href="/questions/">Вопрос/Ответ</a></li>
            
            
            
            <li <?if ($sub_navigation=='help') echo ' class="current-tab"';?>><a href="/help/about/">О проекте</a></li>
        </ul>
        <div class="clearer">&nbsp;</div>
    </div>
    <?php if (isset($sub_navigation)){
            if ($sub_navigation=="registration"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='registration') echo ' class="current-tab"';?>><a href="/users/registration/">Регистрация</a></li>
                    <?php if($current_tab=='remindpass'):?>
                    <li class="current-tab"><a href="#">Восстановление пароля</a></li>
                    <?php endif;?>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="tests"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='in russian') echo ' class="current-tab"';?>><a href="/test/">ЕНТ (рус)</a></li>
                    <li <?if ($current_tab=='in kazakh') echo ' class="current-tab"';?>><a href="/test/kk/">ЕНТ (каз)</a></li>
                    <li <?if ($current_tab=='stats') echo ' class="current-tab"';?>><a href="/test/stats/">Статистика</a></li>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="universities"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    
                    <li <?if ($current_tab=='start') echo ' class="current-tab"';?>><a href="/uni/">Центр Управления</a></li>
                    <li <?if ($current_tab=='all') echo ' class="current-tab"';?>><a href="/uni/all/">Все ВУЗы</a></li>
                    <?if ($current_tab=='current'):?> <li class="current-tab"><a href="/uni/<?php echo $current_nav_link?>/"><?php echo $current_nav?></a></li><?php endif;?>
                    <?if ($current_tab=='search'):?> <li class="current-tab"><a href="#">Результаты поиска</a></li><?php endif;?>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="qa"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='ask') echo ' class="current-tab"';?>><a href="/questions/ask/">Задать вопрос</a></li>
                    <li <?if ($current_tab=='unanswered') echo ' class="current-tab"';?>><a href="/questions/unanswered/">Неотвеченные вопросы</a></li>
                    <li <?if ($current_tab=='all questions') echo ' class="current-tab"';?>><a href="/questions/">Все вопросы</a></li>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="users"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='hall of fame') echo ' class="current-tab"';?>><a href="/users/">Зал славы</a></li>
                    <li <?if ($current_tab=='all users') echo ' class="current-tab"';?>><a href="/users/all/">Весь пипл</a></li>
                    <?php if ($session->is_logged_in()):?>
                    <li <?if ($current_tab=='mycv') echo ' class="current-tab"';?>><a href="/users/<?php echo User::find_by_id($session->user_id)->login?>/">Мой CV</a></li>
                    <?php endif;?>
                    <?php if(isset($current_nav_link)):?>
                    <li <?if ($current_tab=='current') echo ' class="current-tab"';?>><a href="/users/<?php echo $current_nav_link?>/"><?php echo $current_nav_link?></a></li>
                    <?php endif;?>
                    <?php if($session->is_logged_in()):?>
                    <li <?if ($current_tab=='settings') echo ' class="current-tab"';?>><a href="/users/settings/">Мои настройки</a></li>
                    <?php endif;?>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="help"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='about') echo ' class="current-tab"';?>><a href="/help/about/">О проекте</a></li>
                    <li <?if ($current_tab=='rules') echo ' class="current-tab"';?>><a href="/help/rules/">Правила сайта</a></li>
                    <li <?if ($current_tab=='promo') echo ' class="current-tab"';?>><a href="/help/promo/">Содтрудничество</a></li>
                    <li <?if ($current_tab=='ads') echo ' class="current-tab"';?>><a href="/help/ads/">Реклама</a></li>
                    <li <?if ($current_tab=='contacts') echo ' class="current-tab"';?>><a href="/help/contacts/">Контакты</a></li>   
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="grants"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='all') echo ' class="current-tab"';?>><a href="/grants/">Все гранты</a></li>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
            elseif($sub_navigation=="main"){
            ?>
            <div id="sub-nav">
                <ul class="tabbed">
                    <li <?if ($current_tab=='abitur') echo ' class="current-tab"';?>><a href="/abitur">Абитуриент</a></li>
                    <li <?if ($current_tab=='student') echo ' class="current-tab"';?>><a href="/student">Студент</a></li>
                    <li <?if ($current_tab=='alumni') echo ' class="current-tab"';?>><a href="/alumni">Выпускник</a></li>
                    <?php if(isset($current_nav_link)):?>
                    <li <?if ($current_tab=='tag') echo ' class="current-tab"';?>><a href="/alumni">Теги</a></li>
                    <?php endif?>
                </ul>
                <div class="clearer">&nbsp;</div>
            </div>
            <?php
            }
        }
    ?>
			</div>
		</div> 
		<!-- Header Ended-->
