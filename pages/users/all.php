<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Пипл ' .$pipe. ' Весь пипл';
    $meta_keywords = 'кто сдал ент, пользователи зубра, зуббр, зуббр.кз, зубр.кз, zubr.kz, zubbr.kz';
    $meta_description = 'Все пользователи Зуббр.кз';
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";


    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "users";
    $current_tab = "all users";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

    $total_users = User::count_all();


    if (isset($_GET['page'])) {
        $current_page = $_GET['page'];    
    }
    else {
        $current_page = 1;
    }
    $per_page = 30;
    $temp = $per_page;
    $pagination = new Pagination($current_page, $per_page, $total_users);

    $users = User::find_all_users($per_page, $pagination->offset());
?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="post">
            <?php if(!empty($users)):?>
                <div class="section">
                    <div>
                        <div class="section-title">
                            <div class="left">Весь пипл</div>
                            <div class="right">Всего: <?php echo $total_users?></div>
                            <div class="clearer">&nbsp;</div>
                        </div>
                        <div class="col3 left">
                            <div class="section-content">
                                <ul class="nice-list">
                                    <?php
                                        for($i=0; $i<10; $i++):
                                        if(!empty($users[$i])):
                                        ?>
                                        <li>
                                            <div class="col3-avatar-top left"> 
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $users[$i]->usr_id . '.jpg')) {echo $users[$i]->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                                            </div>
                                            <div class="col3-mid-body2 left">
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "largest"><?php echo $users[$i]->login?></a>
                                            </div>
                                            <div class="right"></div>
                                            <div class="clearer">&nbsp;</div>
                                        </li>
                                        <?php endif; endfor;?>
                                </ul>
                            </div>
                        </div>
                        <div class="col3 col3-mid left">
                            <div class="section-content">
                                <ul class="nice-list">
                                    <?php
                                        for($i=10; $i<20; $i++):
                                        if(!empty($users[$i])):?>
                                        <li>
                                            <div class="col3-avatar-top left"> 
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $users[$i]->login . '.jpg')) {echo $users[$i]->login . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                                            </div>
                                            <div class="col3-mid-body2 left">
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "largest"><?php echo $users[$i]->login?></a>
                                            </div>
                                            <div class="right"></div>
                                            <div class="clearer">&nbsp;</div>
                                        </li>
                                        <?php endif; endfor;?> 
                                </ul>
                            </div>
                        </div>
                        <div class="col3 right">
                            <div class="section-content">
                                <ul class="nice-list">
                                    <?php
                                        for($i=20; $i<30; $i++):
                                        if(!empty($users[$i])):?>
                                        <li>
                                            <div class="col3-avatar-top left"> 
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "_avatar"><img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $users[$i]->login . '.jpg')) {echo $users[$i]->login . '.jpg';} else echo "no_avatar.png"?>" alt="" class="bordered" /></a> 
                                            </div>
                                            <div class="col3-mid-body2 left">
                                                <a href="/users/<?php echo $users[$i]->login?>/" class = "largest"><?php echo $users[$i]->login?></a>
                                            </div>
                                            <div class="right"></div>
                                            <div class="clearer">&nbsp;</div>
                                        </li>
                                        <?php endif; endfor;?> 
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearer">&nbsp;
                </div>  
                <?php $temp--; endif; ?>         
            <?php
                if($pagination->total_pages() > 1) :  ?>
                <div class="post-meta archive-pagination">

                    <?php    if($pagination->has_previous_page()) { 
                            echo "<a href=\"/users/all/";
                            echo "?page=";
                            echo $pagination->previous_page();
                            echo "\">&laquo; Назад</a> "; 
                        }
                        
                        echo "<a href=\"/users/all/";
                            echo "?page=1";
                            echo "\">Первая</a> ";
                            
                        for($i=1; $i <= $pagination->total_pages(); $i++) {
                            if(($i >= $current_page - 5) && ($i <= $current_page + 5)){
                            if($i == $current_page) {
                                echo " <span class=\"selected\">{$i}</span> ";
                            } else {
                                echo "<a href=\"/users/all/";
                                echo "?page=";
                                echo $i;
                                echo "\">{$i}</a> ";  
                            }    
                            }
                            
                        }

                        echo "<a href=\"/users/all/";
                            echo "?page=".$pagination->total_pages();
                            echo "\">Последняя</a> ";
                        
                        if($pagination->has_next_page()) { 
                            echo "<a href=\"/users/all/"; 
                            echo "?page=";
                            echo $pagination->next_page();
                            echo "\">Вперед &raquo;</a> ";
                    } ?>
                </div>
                <?php endif;?> 
        </div>    
    </div> 
    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        