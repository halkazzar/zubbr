<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    

    if(!empty($_GET['login'])):
        $user = User::find_by_login($_GET['login']);
        if(!empty($user)):
            $relations = Relation::find_by_user($user->usr_id);
            if(!empty($user->first_name) && !empty($user->last_name)){
            $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Пипл ' .$pipe. ' '. $user->login . ' ('.$user->first_name.' '.$user->last_name .')';
            $meta_keywords = $user->first_name. ', ' . $user->last_name . ', люди, профайл, пипл, резюме';
            $meta_description = 'Профайл пользователя ' . $user->login . '('.$user->first_name.' '.$user->last_name.') на проекте Зуббр.кз';
            }
            else{
            $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Пипл ' .$pipe. ' '. $user->login;
            $meta_keywords = 'люди, профайл, пипл, резюме, пользователи Зуббр.кз';
            $meta_description = 'Профайл пользователя ' . $user->login .' на проекте Зуббр.кз';
            }
            
            //Navigation block
            $sub_navigation = "users";
            if ($session->is_logged_in() && $session->user_id == $user->usr_id){
                $current_tab = "mycv";

            }
            else {
                $current_tab = "current";
                $current_nav_link = $user->login;

            }

            require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
            require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
            require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     
        ?>

        <div class="main" id="main-two-columns">
            <div class="left" id="main-left">
                <div class="post">
                    <div class="col2-left-body left">
                        <div class="col3-mid-body-profile-page"> 
                            <h2><span><?php echo $user->first_name . ' ' . $user->last_name?></span></h2>
                            <h3 class="cv"><?php echo $user->login?></h3>
                        </div>

                        <?php
                            if($user->is_email_activated()):
                                $show_email = false;
                                $opt = Option::find_by_title('show_email'); 
                                if(!empty($opt)){
                                    $optmap = Optionmap::find_by_user_id_option_id($opt->option_id, $user->usr_id);
                                    if(!empty($optmap)){
                                        if ($optmap->value == 'enabled') {
                                            $show_email = true;    
                                        }
                                    }    
                                }
                             
                        ?>
                        
                        <?php if($show_email == true):?>
                        <div class="col3-avatar-profile-page left"> 
                            <div class="largest text-right georgia nomargin">email</div>    
                        </div>
                        <?php endif;?>
                        
                        <div class="col3-mid-body-profile-page"> 
                            <span class="largest nomargin" id="reg_email"><?php echo $user->email?></span>
                        </div>
                        <div class="clearer">&nbsp;</div>
                        <?php endif;?>
                        
                        
                        <div class="col3-avatar-profile-page left"> 
                            <div class="largest text-right georgia nomargin">вместе с нами с</div>    
                        </div>
                        <div class="col3-mid-body-profile-page"> 
                            <div class="largest nomargin"><?php echo date_long_ago($user->date_of_join)?></div>
                        </div>
                        <div class="clearer">&nbsp;</div>

                        <div class="col3-avatar-profile-page left"> 
                            <div class="largest text-right georgia">пребывает в</div>    
                        </div>
                        <div class="col3-mid-body-profile-page largest"> 
                            <span class="reg_location" id="reg_location_display"><?php  if (!empty($user->location_id) && isset($user->location_id)) echo Location::find_by_id($user->location_id)->location_name; else echo 'Не указано :( '?></span>
                        </div>

                        <div class="clearer">&nbsp;</div>
                    </div>
                    <div class="col2-right-avatar right">
                        <div class="avatar_big right">
                            <img  id="avatar_big" src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $user->usr_id . '_big.jpg')) {echo $user->usr_id . '_big.jpg';} else echo "no_avatar.png"?>" alt="Фото пользователя <?php echo $user->login?>" class="right bordered" /> 
                        </div>
                    </div>
                    <div class="clearer"></div>

                    <?php if(!empty($relations)) foreach($relations as $relation):
                                if($relation->role != 'abitur'):
                                    $date_of_enroll = date_translate($relation->date_of_enroll);
                                    $date_of_graduation = date_translate($relation->date_of_graduation);
                                ?>

                                <div class="col3-avatar-profile-page left"> 
                                    <div class="largest text-right georgia">
                                        <span><?php echo $date_of_enroll['month_word'] .' ' .$date_of_enroll['year']?>
                                            — 
                                        <?php echo $date_of_graduation['month_word'] .' ' .$date_of_graduation['year']?></span>
                                    </div>    
                                </div>

                                <div class="col3-mid-body-profile-page"> 
                                    <h3>
                                        <?php 
                                        $uni_for_longname = University::find_by_id($relation->university_id); 
                                        echo $uni_for_longname->long_name;
                                        if (!empty(Scholarship::find_by_id($relation->scholarship_id)->title)) {
                                            echo ' — ' . Scholarship::find_by_id($relation->scholarship_id)->title;
                                        } ?>
                                        <br /> 
                                        <span class="cv">
                                            <?php if(!empty(Degree::find_by_id($relation->degree_id)->title)) echo Degree::find_by_id($relation->degree_id)->title; ?>
                                            <?php if (!empty(Studyarea::find_by_id($relation->studyarea_id)->studyarea_id)) echo Studyarea::find_by_id($relation->studyarea_id)->title;?>
                                        </span>
                                    </h3>
                                    <div class="clearer">&nbsp;</div>
                                </div>

                                <div class="clearer">&nbsp;</div>
                                <?php endif;?>
                            <?php endforeach;?>
                </div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="right sidebar" id="sidebar-2">
                <?php // require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_alumni.inc";?>
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news.inc";?>          
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>

        <?php
            endif;
        endif;
?>