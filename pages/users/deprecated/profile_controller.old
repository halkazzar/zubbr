<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

?>
<div class="post">
    <form name="profile" id="profile" action="/users/<?php echo $user->login?>/" enctype="multipart/form-data" method="post">
        <div class="col2-left-body left">
            <span class="hidden" id="maxcount"><?php echo count($relations)?></span>
            <div class="col3-mid-body-profile-page"> 
                <h2><span <?php if ($session->is_logged_in() && $session->user_id == $user->usr_id) echo "class='edit first_name'"?> id="reg_firstname"><?php echo $user->first_name?></span>
                <span <?php if ($session->is_logged_in() && $session->user_id == $user->usr_id) echo "class='edit last_name'"?> id="reg_lastname"><?php echo $user->last_name?></span></h2>
            </div>

            <div class="col3-mid-body-profile-page"> 
                <h3 class="cv"><?php echo $user->login?></h3> 
            </div>

            <div class="col3-mid-body-profile-page"> 
                <div class="largest">Проверенный чел</div>
            </div>
            <div>&nbsp;</div>
            <div class="col3-avatar-profile-page left"> 
                <div class="largest text-right georgia nomargin">email</div>    
            </div>

            <div class="col3-mid-body-profile-page"> 
                <span class="<?php if ($session->is_logged_in() && $session->user_id == $user->usr_id) echo "edit"?> largest nomargin" id="reg_email"><?php echo $user->email?></span>
            </div>
            <div class="clearer">&nbsp;</div>

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


            <div class="col3-mid-body-profile-page"> 
                <div class="largest"><span class="reg_location" id="reg_location_display" title="Щелкните для изменения..."><?php  if (!empty($user->location_id) && isset($user->location_id)) echo Location::find_by_id($user->location_id)->location_name; else echo 'Город'?></span>
                    <span class="<?php if ($session->is_logged_in() && $session->user_id == $user->usr_id) echo 'edit'?> reg_location hidden" id="reg_location_value"><?php  echo $user->location_id?></span></div>
            </div>

            <div class="clearer">&nbsp;</div>
        </div>
        <div class="col2-right-avatar right">
            <div id="file-uploader-demo1" >        
            </div>
        
            <script type="text/javascript">        
                function createUploader(){            
                    var uploader = new qq.FileUploader({
                        element: document.getElementById('file-uploader-demo1'),
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


            <div class="avatar_big right">
                <img  id="avatar_big" src="/images/users/avatars/<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/users/avatars/" . $user->login . '_big.jpg')) {echo $user->login . '_big.jpg';} else echo "no_avatar.png"?>" alt="" class="right bordered"></a> 
            </div>
        </div>
        <div class="clearer"></div>



        <?php if(!empty($relations)) foreach($relations as $relation):
                    $date_of_enroll = date_translate($relation->date_of_enroll);
                    $date_of_graduation = date_translate($relation->date_of_graduation);
                ?>
                <?php if ($session->is_logged_in() && $session->user_id == $user->usr_id): ?>
                    <span class="reg_relation hidden" id="reg_relation">
                        <?php echo $relation->university_relation_id?>
                    </span>
                    <?php endif;?>
                <div class="col3-avatar-profile-page left"> 
                    <div class="largest text-right georgia">
                        <span class="reg_month"                     id="reg_monthofenroll_display" title="Щелкните для изменения..."><?php echo $date_of_enroll['month_word']?></span>
                        <span class="reg_monthofenroll hidden"      id="reg_monthofenroll_value"><?php echo $date_of_enroll['month']?></span>
                        <span class="reg_year"                      id="reg_yearofenroll" title="Щелкните для изменения..."><?php echo $date_of_enroll['year']?></span>
                        — 
                        <span class="reg_month"                     id="reg_monthofgraduation_display" title="Щелкните для изменения..."><?php echo $date_of_graduation['month_word']?></span>
                        <span class="reg_monthofgraduation hidden"  id="reg_monthofgraduation_value"><?php echo $date_of_graduation['month']?></span>
                        <span class="reg_year"                      id="reg_yearofgraduation" title="Щелкните для изменения..."><?php echo $date_of_graduation['year']?></span>
                    </div>    
                </div>

                <div class="col3-mid-body-profile-page"> 
                    <h3 class="left">
                    <?php $long_name = University::find_by_id($relation->university_id)->long_name;?> 
                       <input class="reg_university suggest" id="reg_university" title="Щелкните для изменения..." type="text" autocomplete="off" value="<?php if (!empty($long_name)) echo $long_name; else echo "Вуз"?>" size="<?php if (!empty($long_name)) echo mb_strlen(trim($long_name)); else echo 3?>"></input>
                        — 
                        <span class="reg_scholarship"          id="reg_scholarship_display" title="Щелкните для изменения..."><?php if (!empty(Scholarship::find_by_id($relation->scholarship_id)->title)) echo Scholarship::find_by_id($relation->scholarship_id)->title; else echo "Грант"?></span>
                        <span class="reg_scholarship hidden"   id="reg_scholarship_value"><?php echo $relation->scholarship_id?></span>
                    </h3>
                    <br />
                    <h3 class="cv left">
                        <span class="reg_degree"               id="reg_degree_display" title="Щелкните для изменения..."><?php if(!empty(Degree::find_by_id($relation->degree_id)->title)) echo Degree::find_by_id($relation->degree_id)->title; else echo "Степень"?></span>
                        <span class="reg_degree hidden"        id="reg_degree_value"><?php echo $relation->degree_id?></span>

                        <input class="reg_studyarea cv suggest " id="reg_studyarea" title="Щелкните для изменения..." type="text" autocomplete="off" value="<?php if (!empty(Studyarea::find_by_id($relation->studyarea_id)->studyarea_id)) echo Studyarea::find_by_id($relation->studyarea_id)->title; else echo 'Специальность'?>"></input>
                        
                    </h3>
                    <div class="rem_uni georgia right">
               <a href="#" class="delete_university">Удалить вуз</a>
            </div>
                </div>
                
                <div class="clearer">&nbsp;</div>
                <?php endforeach;?>
        <div class="additional_part_main hidden">
            <span class="reg_relation hidden" id="reg_relation">
                -1
            </span>
            <div class="col3-avatar-profile-page left"> 
                <div class="largest text-right georgia">
                    <span class="reg_month"                     id="reg_monthofenroll_display" title="Щелкните для изменения...">Сентябрь</span>
                    <span class="reg_monthofenroll hidden"      id="reg_monthofenroll_value" >9</span>
                    <span class="reg_year"                      id="reg_yearofenroll" title="Щелкните для изменения...">2000</span>
                    — 
                    <span class="reg_month"                     id="reg_monthofgraduation_display" title="Щелкните для изменения...">Май</span>
                    <span class="reg_monthofgraduation hidden"  id="reg_monthofgraduation_value">5</span>
                    <span class="reg_year"                      id="reg_yearofgraduation" title="Щелкните для изменения...">2005</span>
                </div>    
            </div>

            <div class="col3-mid-body-profile-page"> 
                <h3 class="left">
                
                <input class="reg_university suggest" id="reg_university" title="Щелкните для изменения..." type="text" autocomplete="off" value="Вуз"></input>
                — 
                    <span class="reg_scholarship"          id="reg_scholarship_display" title="Щелкните для изменения...">Грант</span>
                    <span class="reg_scholarship hidden"   id="reg_scholarship_value">0</span>
                </h3>
                <br/>
                <h3 class="cv left">
                    <span class="reg_degree"               id="reg_degree_display" title="Щелкните для изменения...">Степень</span>
                    <span class="reg_degree hidden"        id="reg_degree_value">0</span>
                    
                    <input class="reg_studyarea cv suggest" id="reg_studyarea" title="Щелкните для изменения..." type="text" autocomplete="off" value="Специальность"></input>
                    </h3>
                    <div class="rem_uni georgia right">
               <a href="#" class="delete_university hidden">Удалить вуз</a>
            </div>
            </div>
            <div class="clearer">&nbsp;</div>            
        </div>
        <div id="container"></div>
        <?php if ($session->is_logged_in() && $session->user_id == $user->usr_id):?>
            <div class="additional_part add_uni georgia">
                <h3 class="center text-center "><a href="#" id="addMoreUni">Добавить вуз</a></h3>
            </div>
            <?php endif;?>
        <div id="upload">
        </div>
        <div class="clearer">&nbsp;</div> 
    </form>
</div>
