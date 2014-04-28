<?php
    //
    // INIT
    //
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    //GET
    if (isset($_GET['email_activated'])){
        $error_code = -3;
    }



    //
    // POST
    //
    if (isset($_POST['delete_relation'])){
        $relation_to_del = str_ireplace('rel_', '', $_POST['delete_relation']);

        if(Relation::find_by_id($relation_to_del)->user_id == $session->user_id){ //This relation belongs to the user
            Relation::delete_by_id($relation_to_del);        
        } 
    }
    if (isset($_POST['delete_sub'])){
        $sub_to_del = str_ireplace('sub_', '', $_POST['delete_sub']);

        if(Subscription::find_by_id($sub_to_del)->user_id == $session->user_id){ //This subscription belongs to the user
            Subscription::delete_by_id($sub_to_del);        
        } 
    }

    if (isset($_POST['basic_info_save'])){
        $current_user = User::find_by_id($session->user_id);

        //Basic info
        $current_user->first_name = trim($_POST['first_name']);
        $current_user->last_name = trim($_POST['last_name']);
        $location = Location::find_by_name(trim($_POST['location_name']), 'city');
        if(!empty($location)){
            $current_user->location_id = $location->location_id;    
        }
        else{
            $new_location = new Location();
            $new_location->location_name = trim($_POST['location_name']);
            $new_location->save();
            $current_user->location_id = $new_location->location_id;
        }

        $email_pattern = '/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/';
        $email_address = trim($_POST['email']);
        $is_correct = preg_match($email_pattern, $email_address);


        //Changing email adress
        if((User::is_email_free($email_address) || (User::find_by_email($email_address)->usr_id == $session->user_id)) && ($is_correct)){
            if(User::is_email_free($email_address)){
                $current_user->email = $email_address;
                $current_user->deactivate_email();

            }
        }

        $current_user->save();
        log_action('PROFILE UPDATE', 'basic info', '1', $current_user->login);

        //Let's delete all subscribed tags before we add a new one
        $subs_to_del = Subscription::find_by_object('tag', -1, $session->user_id);
        foreach($subs_to_del as $sub){
            Subscription::delete_by_id($sub->subscription_id);
        }

        //2. Saving
        $tags_string = $_POST['tags'];
        $tags = tag_str_to_array($tags_string);

        foreach($tags as $tag_name){
            if(!empty($tag_name) && $tag_name != '' && $tag_name != ' '){
                $tag = Tag::find_by_exact_name(trim($tag_name));
                if(empty($tag)){
                    $tag = new Tag;
                    $tag->tag_name = $tag_name;
                    $tag->is_default = 0;
                    $tag->group_id = 0;
                    $tag->save();
                }
                $subscription = new Subscription;
                $subscription->subscription_category = 'tag';
                $subscription->user_id = $session->user_id;
                $subscription->category_object_id = $tag->tag_id;
                $subscription->save();

                //$new_map = new Tagmap;
                //$new_map->object_type = 'question';
                //$new_map->object_id = $que->question_id;
                //$new_map->tag_id = $tag->tag_id;
                //$new_map->save();

                $tags_id[] = $tag->tag_id;
                $tags_name[] = $tag->tag_name;
            }    
        }


        //Show email option
        $opt = Option::find_by_title('show_email');
        if(!empty($opt)){
            $cur_option = Optionmap::find_by_user_id_option_id($opt->option_id, $session->user_id);
            if(empty($cur_option)){
                //If no optionmap is found, then this option is new OR user never changed it,
                //so there are no records in the DB.
                $cur_option = new Optionmap;
                $cur_option->user_id = $session->user_id;
                $cur_option->option_id = $opt->option_id;    
            }
            
            if(isset($_POST['show_email'])){
                $cur_option->value = 'enabled';
            }
            else{
                $cur_option->value = 'disabled';
            }
            $cur_option->save();
        }

        $error_code = -2; //Changes saved
    }

    if(isset($_POST['change_password_save'])){
        $current_user = User::find_by_id($session->user_id);

        if(isset($_POST['password']) && isset($_POST['password2'])){
            if ($_POST['password'] == $_POST['password2']){
                $current_user->password = md5($_POST['password']);
                $current_user->save();
                log_action('PROFILE UPDATE', 'password', '1', $current_user->login);
                $error_code = -2; //Changes saved    
            }
            else{
                $error_code = 3; //Password mismatch
            }
        }
    }

    if(isset($_POST['university_save'])){
        $current_user = User::find_by_id($session->user_id);
        //print_r($_POST);
        //Let's remove what we have to

        if(!empty($_POST['remove'])){
            foreach($_POST['remove'] as $rel_to_del_id){
                //Checking if the found relation belongs to the user
                if(Relation::find_by_id($rel_to_del_id)->user_id == $session->user_id){
                    Relation::delete_by_id($rel_to_del_id);
                }
            }
            redirect_to($_SERVER['PHP_SELF']);    
        }

        //New we can add rels
        if(is_numeric(trim($_POST['total']))) {
            $total = trim($_POST['total']);

        }
        for($i = 1; $i <= $total; $i++){
            if(isset($_POST['relation'.$i])){    
                if(is_numeric(trim($_POST['relation'.$i]))) $relation_id = trim($_POST['relation'.$i]);

                if($relation_id != -1){   //updating a realtion record
                    $relation = Relation::find_by_id($relation_id);

                    //Checking if the found relation belongs to the user
                    if ($relation->user_id != $session->user_id){
                        redirect_to($_SERVER['PHP_SELF']);
                    }
                }else{                    //creating new relation record
                    $relation = new Relation;
                    $relation->user_id = $session->user_id;
                }


                //Univ
                $university = University::find_by_long_name(trim($_POST['university'.$i]));
                if(!empty($university)){
                    $relation->university_id = $university->university_id;    
                }
                else{
                    $new_university = new University();
                    $new_university->long_name = trim($_POST['university'.$i]);
                    $new_university->status = 'draft';
                    $new_university->save();
                    $relation->university_id = $new_university->university_id;
                }    

                //Degree
                $relation->degree_id = $_POST['degree'.$i];

                //Studyarea
                $studyarea = Studyarea::find_by_title(trim($_POST['studyarea'.$i]));
                if(!empty($studyarea)){
                    $relation->studyarea_id = $studyarea->studyarea_id;    
                }
                else{
                    $new_studyarea = new studyarea();
                    $new_studyarea->title = trim($_POST['studyarea'.$i]);
                    $new_studyarea->save();
                    $relation->studyarea_id = $new_studyarea->studyarea_id;
                }

                //scholarship
                $scholarship = Scholarship::find_by_title(trim($_POST['scholarship'.$i]));
                if(!empty($scholarship)){
                    $relation->scholarship_id = $scholarship->scholarship_id;    
                }
                else{
                    $new_scholarship = new scholarship();
                    $new_scholarship->title = trim($_POST['scholarship'.$i]);
                    $new_scholarship->save();
                    $relation->scholarship_id = $new_scholarship->scholarship_id;
                }

                //Addition fields

                $relation->date_of_enroll = date($dateformat, mktime(0,0,0,$_POST['monthstart'.$i], 1, $_POST['yearstart'.$i]));
                $relation->date_of_graduation = date($dateformat, mktime(0,0,0,$_POST['monthfinish'.$i], 1, $_POST['yearfinish'.$i]));

                if ($relation->date_of_graduation < getdate()) {
                    $relation->role = 'alumni';    
                }
                elseif ($relation->date_of_graduation > getdate()) {
                    $relation->role = 'student';
                }
                log_action('PROFILE UPDATE', 'RELATION', '1', $current_user->login);

                if($relation->save()){
                    $error_code = -2; //Changes saved     
                }
            }
        }       
    }
    //
    // AJAX
    //
    if (isset($_GET['ajax'])):
        if(isset($_GET['email'])){
            if(User::is_email_free($_GET['email'])){
                $result['email_status'] = true;
            }
            else{
                if(User::find_by_email($_GET['email'])->usr_id == $session->user_id){
                    $result['email_status'] = true;
                }
                else $result['email_status'] = false;
            }
        }
        elseif(isset($_GET['confirm'])){
            if($session->is_logged_in()){
                $user = User::find_by_id($session->user_id);
                $user->new_email_activation();
                $hash = $user->email_activated;

                $mail = new PHPMailerLite();
                $mail->AddAddress($user->email);
                $mail->CharSet = 'utf-8';
                $mail->ContentType = 'text/html';
                $mail->SetFrom('noreply@zubbr.kz', 'Зуббр.кз }:)');

                $mail->Subject = 'Зуббр.кз – подтверждение адреса электронной почты';

                //  if(!empty($user->first_name)){
                //                    $mail->Body  = 'Привет, ' . $user->first_name . '. <br />';    
                //                } else {
                //                    $mail->Body  = 'Привет, ' . $user->login . '. <br />';
                //                }
                $mail->Body = 'Привет!  Для работы с порталом Тебе необходимо подтвердить электронный адрес. Для этого перейди по ссылке ';
                $mail->Body .= '<a href = "http://zubbr.kz/users/emailactivate/'.$user->usr_id.'/'. $hash .'/">'. 'http://zubbr.kz/users/emailactivate/'.$user->usr_id.'/'. $hash .'/'. '</a>';
                $mail->Body .= '<br />(если ссылка не открывается, скопируй ее и вставь в адресную строку браузера)';
                $mail->Body .= '<br /><br />После подтверждения Ты сможешь получать уведомления о заданных Тобой вопросах и ответах на них.';
                $mail->Body .= '<br />Настроить уведомления можно в Твоем аккаунте <a href="http://www.zubbr.kz/users/settings/">http://www.zubbr.kz/users/settings/</a> в блоке «Подписка на вопросы».';
                $mail->Body .= '<hr>Жанат Абылкасым, идейный вдохновитель Зуббр.кз }:)';
                if ($mail->Send()){
                    $result['email_sent'] = true;
                }
            }    
        }
        echo json_encode($result);    
        elseif($session->is_logged_in()):
        //$error_code = -2;
        //
        // DISPLAY
        // template: include top part
        $include_in_head = "<link rel='stylesheet' type='text/css' href='/stylesheets/fileuploader.css' media='screen' />";
        $include_in_head .= "<link rel='stylesheet' type='text/css' href='/javascripts/tabs/jquery-ui-1.8.7.custom.css' media='screen' />";
        $include_in_head.= "\n <script type='text/javascript' src='/javascripts/settings_index.js' ></script>";
        $include_in_head.= "\n <script type='text/javascript' src='/javascripts/tabs/jquery-ui-1.8.7.custom.min.js' ></script>";
        $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
        $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
        $include_in_head.= "\n <script type='text/javascript' src='/javascripts/fileuploader.js' ></script>";


        $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Мои настройки ';
        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";

        //Navigation block
        $sub_navigation = "users";
        $current_tab = "settings";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";

        $user = User::find_by_id($session->user_id);
        $relations = Relation::find_my_unis($session->user_id);
        $subs_on_tags = Subscription::find_by_object('tag', -1, $session->user_id);

    ?>

    <div class="main" id="main-two-columns_2">
        <div class="left" id="main-left">
            <div class="post">
                <div class="post-title">
                    <h2>Общая информация</h2>
                    <div class="settings_form">
                        <form name="basic_info" id="basic_info" action="<?php $_SERVER['PHP_SELF']?>" method="post">   
                            <div>
                                <div class="left">
                                    <label>Имя</label>
                                    <input class="medium first_name" name="first_name" type="text" value="<?php echo $user->first_name?>"/>  
                                </div>
                                <div class="left">
                                    <label>Фамилия</label>
                                    <input class="medium last_name" name="last_name" type="text" value="<?php echo $user->last_name?>"/>  
                                </div>
                                <div class="clearer">&nbsp;</div>
                            </div>                
                            <div class="left">
                                <label>Из</label>
                                <input class="medium location" autocomplete="off" name="location_name" type="text" value="<?php echo Location::find_by_id($user->location_id)->location_name?>"/>  

                            </div>
                            <div class="left">
                                <label>E-mail<span class="emailWarning small"></span></label>
                                <input class="medium email" name="email" type="text" value="<?php echo $user->email?>"/>  
                                <div class="emailCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                                <div class="emailWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>
                                <?php if(!$user->is_email_activated()):?>
                                    <label>Адрес не подтвержден.&nbsp;<a id="confirm_email" href="#">Подтвердить</a></label>
                                    <?php endif?>
                            <label for="show_email"><input type="checkbox" id="show_email" name = "show_email" class="checkbox" 
                                    <?php 
                                        if($user->is_email_activated()){  //Email should have been activated before showing an email address
                                            $opt = Option::find_by_title('show_email');

                                            if(!empty($opt)){
                                                $optmap = Optionmap::find_by_user_id_option_id($opt->option_id, $session->user_id);

                                                if(!empty($optmap)){
                                                    if ($optmap->value == 'enabled') echo 'checked = "checked"';
                                                }    
                                            }    
                                        }
                                        else{
                                            echo 'disabled = "disabled"';
                                        }
                                    ?>/>Показывать E-mail    
                            </label>
                        </div> 

                        <label>Интересующие теги
                        </label>
                        <?php 
                            $tags='';
                            foreach($subs_on_tags as $sub) {
                                $tags[] = Tag::find_by_id($sub->category_object_id)->tag_name;
                            }
                            $tags_string = implode(', ', $tags);
                        ?>
                        <input class="verylong tags" name="tags" id="tags" value="<?php echo $tags_string?>" />
                        <div class="clear">&nbsp;</div>

                        <button type="submit" name="basic_info_save" >Сохранить</button>
                        <div class="spacer"></div>
                    </form> 
                </div>
            </div>
            <div class="content-separator">&nbsp;</div>
            <div class="post-title">
                <script type="text/javascript">
                    function createUploader(){            
                        var uploader = new qq.FileUploader({
                            element: document.getElementById('file-uploader'),
                            action: '/users/load_avatar.php',
                            multiple: false,
                            allowedExtensions: ['jpg', 'gif', 'jpeg', 'png'],
                            debug: false,
                            params: {
                                login: '<?php echo $user->login?>'
                            }
                        });           
                    }
                    window.onload = createUploader;
                </script>
                <h2>Аватар</h2>
                <div class="settings_form">
                    <form name="avatar" id="avatar" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">   
                        <div class="avatar left">
                            <label>&nbsp;</label>
                            <img src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $user->usr_id . '.jpg')) {echo $user->usr_id . '.jpg';} else echo "no_avatar.png"?>" alt="Ваша аватарка" class="left bordered"> 
                        </div>
                        <div class="left">
                            <label>&nbsp;</label>
                            <label>Вы можете загрузить аватарку здесь</label>
                            <div id="file-uploader">
                            </div>
                        </div>
                        <div class="clearer">&nbsp;</div>
                        <div class="spacer"></div>
                    </form> 
                </div>
            </div>
            <div class="content-separator">&nbsp;</div>
            <div class="post-title">
                <h2>Изменить пароль</h2>
                <div  class="settings_form">
                    <form name="change_password" id="change_password" action="<?php $_SERVER['PHP_SELF']?>" method="post">   
                        <label>Новый пароль
                            <span class="passwordWarning small"></span>
                        </label>
                        <input class="long password" name="password" type="password" />  
                        <div class="passwordCorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                        <div class="passwordWrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>

                        <label>Повторите пароль
                            <span class="password2Warning small"></span>
                        </label>
                        <input class="long password2" name="password2" type="password" />  
                        <div class="password2CorrectDiv box" style="display:none;"><img src="/images/correct.gif" alt=""/> </div>
                        <div class="password2WrongDiv box" style="display:none;"><img src="/images/wrong.gif" alt=""/> </div>


                        <button type="submit" name="change_password_save" >Изменить пароль</button>
                        <div class="spacer"></div>
                    </form> 
                </div>
            </div>
        </div>
    </div>


    <div class="right sidebar" id="sidebar-2">
        <div class="post">
            <div class="post-title">
                <h2>Информация о ВУЗах</h2>
                <div class="settings_form">
                    <form name="uni" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                        <input type="hidden" name="total" id="total" value="<?php if(!empty($relations)) echo (count($relations)); else echo '0';?>"/>
                        <div id="remove">

                        </div>
                        <div id="tabs">
                            <ul class="hidden">
                                <?php
                                    $i = 1;
                                    if(!empty($relations)):
                                    foreach ($relations as $relation):
                                    ?>
                                    <li><a href="#tabs-<?php echo $i?>"><?php if(!empty(University::find_by_id($relation->university_id)->short_name)) echo University::find_by_id($relation->university_id)->short_name; else {$text = explode(' ', University::find_by_id($relation->university_id)->long_name, 2); echo $text[0];}?></a> <span class="ui-icon ui-icon-close">Удалить запись о вузе</span></li>
                                    <?php
                                        $i++;
                                        endforeach;
                                        endif;
                                ?>
                            </ul>
                            <?php
                                $i = 1;
                                if(!empty($relations)):
                                foreach ($relations as $relation):
                                    $date_enroll_array = date_translate($relation->date_of_enroll);
                                    $date_graduate_array = date_translate($relation->date_of_graduation);
                                ?>
                                <div id="tabs-<?php echo $i?>">
                                    <input name="relation<?php echo $i?>" type="hidden" value="<?php echo $relation->university_relation_id?>"/>
                                    <label>Название ВУЗа</label>
                                    <input class="long university" autocomplete="off" name="university<?php echo $i?>" type="text" value="<?php echo University::find_by_id($relation->university_id)->long_name?>"/>  
                                    <div>
                                        <div class="left">
                                            <label>Период обучения</label>
                                            <select name="monthstart<?php echo $i?>" class="short">
                                                <option value="1"  <?php if ($date_enroll_array['month'] == 1) echo 'selected = "selected"'?> >Январь</option>
                                                <option value="2"  <?php if ($date_enroll_array['month'] == 2) echo 'selected = "selected"'?> >Февраль</option>
                                                <option value="3"  <?php if ($date_enroll_array['month'] == 3) echo 'selected = "selected"'?> >Март</option>
                                                <option value="4"  <?php if ($date_enroll_array['month'] == 4) echo 'selected = "selected"'?> >Апрель</option>
                                                <option value="5"  <?php if ($date_enroll_array['month'] == 5) echo 'selected = "selected"'?> >Май</option>
                                                <option value="6"  <?php if ($date_enroll_array['month'] == 6) echo 'selected = "selected"'?> >Июнь</option>
                                                <option value="7"  <?php if ($date_enroll_array['month'] == 7) echo 'selected = "selected"'?> >Июль</option>
                                                <option value="8"  <?php if ($date_enroll_array['month'] == 8) echo 'selected = "selected"'?> >Август</option>
                                                <option value="9"  <?php if ($date_enroll_array['month'] == 9) echo 'selected = "selected"'?> >Сентябрь</option>
                                                <option value="10" <?php if ($date_enroll_array['month'] == 10) echo 'selected = "selected"'?> >Октябрь</option>
                                                <option value="11" <?php if ($date_enroll_array['month'] == 11) echo 'selected = "selected"'?> >Ноябрь</option>
                                                <option value="12" <?php if ($date_enroll_array['month'] == 12) echo 'selected = "selected"'?> >Декабрь</option>
                                            </select>
                                            <select name="yearstart<?php echo $i?>">
                                                <?php for ($j = 1995; $j < 2016; $j++):?>
                                                    <option value="<?php echo $j?>" <?php if ($date_enroll_array['year'] == $j) echo 'selected = "selected"'?>  ><?php echo $j?></option>
                                                    <?php endfor;?>
                                            </select>
                                            &nbsp;—&nbsp;
                                            <select name="monthfinish<?php echo $i?>">
                                                <option value="1"  <?php if ($date_graduate_array['month'] == 1) echo 'selected = "selected"'?> >Январь</option>
                                                <option value="2"  <?php if ($date_graduate_array['month'] == 2) echo 'selected = "selected"'?> >Февраль</option>
                                                <option value="3"  <?php if ($date_graduate_array['month'] == 3) echo 'selected = "selected"'?> >Март</option>
                                                <option value="4"  <?php if ($date_graduate_array['month'] == 4) echo 'selected = "selected"'?> >Апрель</option>
                                                <option value="5"  <?php if ($date_graduate_array['month'] == 5) echo 'selected = "selected"'?> >Май</option>
                                                <option value="6"  <?php if ($date_graduate_array['month'] == 6) echo 'selected = "selected"'?> >Июнь</option>
                                                <option value="7"  <?php if ($date_graduate_array['month'] == 7) echo 'selected = "selected"'?> >Июль</option>
                                                <option value="8"  <?php if ($date_graduate_array['month'] == 8) echo 'selected = "selected"'?> >Август</option>
                                                <option value="9"  <?php if ($date_graduate_array['month'] == 9) echo 'selected = "selected"'?> >Сентябрь</option>
                                                <option value="10" <?php if ($date_graduate_array['month'] == 10) echo 'selected = "selected"'?> >Октябрь</option>
                                                <option value="11" <?php if ($date_graduate_array['month'] == 11) echo 'selected = "selected"'?> >Ноябрь</option>
                                                <option value="12" <?php if ($date_graduate_array['month'] == 12) echo 'selected = "selected"'?> >Декабрь</option>
                                            </select>
                                            <select name="yearfinish<?php echo $i?>">
                                                <?php for ($j = 1995; $j < 2016; $j++):?>
                                                    <option value="<?php echo $j?>" <?php if ($date_graduate_array['year'] == $j) echo 'selected = "selected"'?> ><?php echo $j?></option>
                                                    <?php endfor;?>
                                            </select>
                                        </div>
                                        <div class="right">
                                            <label>Степень</label>
                                            <select name="degree<?php echo $i?>">
                                                <?php
                                                    $degrees = Degree::find_all();
                                                    foreach ($degrees as $degree):
                                                    ?>
                                                    <option value="<?php echo $degree->degree_id?>" <?php if ($relation->degree_id == $degree->degree_id) echo 'selected = "selected"'?>><?php echo $degree->title?></option>
                                                    <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="clearer">&nbsp;</div>
                                    </div>
                                    <label>Специальность</label>
                                    <input class="long spec" name="studyarea<?php echo $i?>" autocomplete="off" type="text"  value="<?php echo Studyarea::find_by_id($relation->studyarea_id)->title?>"/>  
                                    <label>Образовательная программа / Грант</label>
                                    <input class="long scholarship" name="scholarship<?php echo $i?>" autocomplete="off" type="text" value="<?php echo Scholarship::find_by_id($relation->scholarship_id)->title?>"/>  
                                </div>
                                <?php
                                    $i++;
                                    endforeach;
                                    endif;
                            ?>
                        </div>

                        <button id="add_tab">Новая запись</button>
                        <button type="submit" name="university_save" >Сохранить</button>
                    </form>
                    <div id="buffer" class="hidden">
                        <input name='relation' type="hidden" value="-1"/>
                        <label>Название ВУЗа</label>
                        <input class="long university" autocomplete="off" name="university" type="text" value=""/>  
                        <div>
                            <div class="left">
                                <label>Период обучения</label>
                                <select name="monthstart" class="short">
                                    <option value="1">Январь</option>
                                    <option value="2">Февраль</option>
                                    <option value="3">Март</option>
                                    <option value="4">Апрель</option>
                                    <option value="5">Май</option>
                                    <option value="6">Июнь</option>
                                    <option value="7">Июль</option>
                                    <option value="8">Август</option>
                                    <option value="9">Сентябрь</option>
                                    <option value="10">Октябрь</option>
                                    <option value="11">Ноябрь</option>
                                    <option value="12">Декабрь</option>
                                </select>
                                <select name="yearstart">
                                    <?php for ($j = 1995; $j < 2016; $j++):?>
                                        <option value="<?php echo $j?>"><?php echo $j?></option>
                                        <?php endfor;?>
                                </select>
                                &nbsp;—&nbsp;
                                <select name="monthfinish<?php echo $i?>">
                                    <option value="1">Январь</option>
                                    <option value="2">Февраль</option>
                                    <option value="3">Март</option>
                                    <option value="4">Апрель</option>
                                    <option value="5">Май</option>
                                    <option value="6">Июнь</option>
                                    <option value="7">Июль</option>
                                    <option value="8">Август</option>
                                    <option value="9">Сентябрь</option>
                                    <option value="10">Октябрь</option>
                                    <option value="11">Ноябрь</option>
                                    <option value="12">Декабрь</option>
                                </select>
                                <select name="yearfinish">
                                    <?php for ($j = 1995; $j < 2016; $j++):?>
                                        <option value="<?php echo $j?>"><?php echo $j?></option>
                                        <?php endfor;?>
                                </select>
                            </div>
                            <div class="right">
                                <label>Степень</label>
                                <select name="degree">
                                    <?php
                                        $degrees = Degree::find_all();
                                        foreach ($degrees as $degree):
                                        ?>
                                        <option value="<?php echo $degree->degree_id?>"><?php echo $degree->title?></option>
                                        <?php endforeach;?>
                                </select>
                            </div>
                            <div class="clearer">&nbsp;</div>
                        </div>
                        <label>Специальность</label>
                        <input class="long spec" name="studyarea" autocomplete="off" type="text" value=""/>  
                        <label>Образовательная программа / Грант</label>
                        <input class="long scholarship" name="scholarship" autocomplete="off" type="text" value=""/>  
                    </div> 
                </div>
            </div>
            <div class="content-separator">&nbsp;</div>
            <div class="post-title">
                <h2>Я хочу учиться</h2>
                <div class="settings_form">
                    <?php
                        $my_relations = Relation::find_by_user_and_role('abitur', $session->user_id);
                        if(!empty($my_relations)):
                            foreach($my_relations as $rel):
                            ?>

                            <a href="#" class="delete delete_relation" id="rel_<?php echo $rel->university_relation_id?>"></a><a class="left large" href="/uni/<?php echo University::find_by_id($rel->university_id)->alias?>/"><?php echo University::find_by_id($rel->university_id)->long_name?></a>
                            <div class="clearer">&nbsp;</div>                 
                            <?php endforeach; endif;?>
                </div>
            </div>
            <div class="content-separator">&nbsp;</div>
            <div class="post-title">
                <h2>Подписка на ответы</h2>
                <div class="settings_form">
                    <?php
                        $my_subscriptions = Subscription::find_by_object('question', -1, $session->user_id);
                        if(!empty($my_subscriptions)):
                            foreach($my_subscriptions as $sub):
                            ?>

                            <a href="#" class="delete delete_sub" id="sub_<?php echo $sub->subscription_id?>"></a><a class="left large" style="width: 400px;" href="/questions/<?php echo Question::find_by_id($sub->category_object_id)->question_id?>/"><?php echo Question::find_by_id($sub->category_object_id)->question_body?></a>
                            <div class="clearer">&nbsp;</div>                 
                            <?php endforeach; endif;?>
                </div>
            </div>
        </div>    


    </div>

    <div class="clearer">&nbsp;</div>
    </div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc"; else: redirect_to('/'); endif;?>
