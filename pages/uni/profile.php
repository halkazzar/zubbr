<?php
    // /zubr.kz/universities/index.php
    // 
    // Universities if no UID show list, if UID passed shows profile of university
    //
    // Created by Dd on 24/04/2010

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if (isset($_GET['alias'])) {
        $alias = $_GET['alias'];
    }
    else $alias = 'energo';

    if (isset($_GET['paid_id'])){
        if (!$session->is_alias_in_session($alias, 'paid')){
            $session->update_view($alias, 'paid');
            $stat = Stats_UniversityView::find_by_id($_GET['paid_id']);
            $stat->views++;
            $stat->save();
        }    
    }
    elseif (isset($_GET['free_id'])){
        if (!$session->is_alias_in_session($alias, 'free')){
            $session->update_view($alias, 'free');
            $stat = Stats_UniversityView::find_by_id($_GET['free_id']);
            $stat->views++;
            $stat->save();
        }    
    }    
    else {
        if (!$session->is_alias_in_session($alias, 'none')){
            $session->update_view($alias, 'none');
            $stat = Stats_UniversityView::find_all_promoted('none', University::find_by_alias($alias)->university_id);
            if (!empty ($stat)){
            $stat->views++;
            $stat->save();    
            }
        }
    }
    
    
                
            
            
    $university = University::find_by_alias($alias);
    if(!empty($university)):
    $university_slides = UniversitySlide::find_by_university_id($university->university_id);

    $include_in_head = "<link rel='stylesheet' type='text/css' href='/javascripts/slider/css/slider.css' media='screen' />";
    $include_in_head .= "<link rel='stylesheet' type='text/css' href='/stylesheets/form.css' media='screen' />";
    $include_in_head .= "<link rel='stylesheet' type='text/css' href='/javascripts/tabs/jquery-ui-1.8.7.custom.css' media='screen' />";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/tabs/jquery-ui-1.8.7.custom.min.js' ></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/slider/js/jquery.easing.1.2.js' ></script>"; 
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/slider/js/jquery.anythingslider.js'></script>";
    $include_in_head .= "
    <script type='text/javascript'>

    function formatText(index, panel) {
    return index + '';
    };

    $(function () {

    $('.anythingSlider').anythingSlider({
    easing: 'easeInOutExpo',        // Anything other than 'linear' or 'swing' requires the easing plugin
    autoPlay: true,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not.
    delay: 6000,                    // How long between slide transitions in AutoPlay mode
    startStopped: false,            // If autoPlay is on, this can force it to start stopped
    animationTime: 1200,             // How long the slide transition takes
    hashTags: false,                 // Should links change the hashtag in the URL?
    buildNavigation: false,          // If true, builds and list of anchor links to link to each slide
    pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
    startText: '',                // Start text
    stopText: '',               // Stop text
    navigationFormatter: formatText // Details at the top of the file on this use (advanced use)
    });

    });
    </script>
    ";

    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/university.js' charset='utf-8'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/_ask.js' charset='utf-8'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/__questions.js' charset='utf-8'></script>";
    $include_in_head .= "\n <script type='text/javascript' src='/javascripts/_alumni.js' charset='utf-8'></script>";

    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' ВУЗы ' .$pipe. ' ' . $university->short_name;
    $meta_keywords = $university->short_name . ', ' . $university->long_name . ', ' . $university->alias . ', вузы рк, универ, военная кафедра, стоимость обучения';
    $meta_description = strip_tags($university->long_description);
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";



    //Navigation block
    $sub_navigation = "universities";
    $current_tab = 'current';
    $current_nav_link = $university->alias;
    $current_nav      = $university->short_name;

    $specialities = Speciality::find_by_university_id($university->university_id); 
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     

?>

<div class="main" id="main-two-columns">
    <div class="left" id="main-left">

        <div class="anythingSlider">
                <div class="wrapper">
                    <ul>
                        <?php 
                        if (!empty($university_slides)):
                        foreach ($university_slides as $slide) :?>
                            <li>
                                <img src="/images/universities/slides/<?php echo $slide->university_id.'_'.$slide->slides_id. $slide->picture_extension?>" alt='<?php echo $university->short_name . ' - ' . $slide->label?>'></img>
                                <div class="imagetoolsshade">
                                    <h2><?php echo $slide->label?></h2>
                                    
                                </div>
                            </li>    
                            <?php endforeach; endif;?>
                    </ul>        
                </div>
            </div>
     
        <div class="clearer">&nbsp;</div>
        <div style="margin-top: 325px">
        <?php 
        // Setting required varriable
        $current_university_id = $university->university_id;
        require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/_share.inc";?>             



        <div class="section network-section">
            <div class="section-title">
                <div class="left">Описание</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">
                <div class="post">
                    <?php
                        $patern='/(\r\n)|(<p>)/';
                        $matches = preg_split($patern, $university->long_description); 
                        $little_description = $matches[0];
                    ?>
                    <div>
                    <?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/images/universities/badges/" . $university->alias . ".jpg")):?>
                    <img class="left" src="/images/universities/badges/<?php echo $university->alias?>.jpg" width="100" height="100" alt="Герб <?php echo $university->short_name?>"/>
                    <?php endif;?>
                    
                    <h6><?php echo $university->long_name?></h6>
                    <p>
                    <?php echo $little_description;?>
                    </p>
                    </div>
                    
                    <div class="clearer">&nbsp;</div>
                    
                    <div class="post-body content">
                    <?php for($i = 1; $i < count($matches); $i++): //$i = 0, because first paragraph is already echoed as little_desc?>
                        <p><?php echo $matches[$i]?></p>
                    <?php endfor;?>
                        <p><a href="<?php echo $university->link?>" target="blank"><?php echo $university->link?></a></p>
                    </div>
                </div>
            </div>
        
        <div class="right"><a href="#" class="more toggle _expander expander">Развернуть</a></div>
        <div class="clearer">&nbsp;</div>
        </div>    
        <div class="section network-section">
            <div class="section-title">
                <div class="left">Факты</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content">
                <table class="data-table"> 
                    <tr> 
                        <th>Год основания</th> 
                        <th>Форма управления</th> 
                        <th>Местоположение</th> 
                        <th>Рейтинг</th> 
                        <th>Военная кафедра</th> 
                    </tr> 
                    <tr class="even"> 
                        <td><?php echo $university->date_of_creation ?></td> 
                        <td><?php if ($university->management_form == 'public') echo "Государственная"; else echo "Частная" ?></td> 
                        <td><?php echo Location::find_by_id($university->location_id)->location_name ?></td> 
                        
                        <td></td>  
                        <td><?php if ($university->military_training) echo "Есть"; else echo "Нет" ?></td>
                    </tr> 
                    <tr> 
                        <th>Стоимость в год</th> 
                        <th>Количество студентов</th> 
                        <th>Количество общежитий</th> 
                        <th>Количество спорткомплексов</th> 
                        <th>Количество библиотек</th> 

                    </tr> 
                    <tr class="even"> 
                        <td><?php echo $university->cost_of_year ?>, USD</td> 
                        <td><?php echo $university->number_of_studs?></td> 
                        <td><?php echo $university->number_of_dorms ?></td> 
                        <td><?php echo $university->number_of_sport ?></td> 
                        <td><?php echo $university->number_of_library ?></td> 
                    </tr>  
                </table>
            </div>
        </div>

        <?php if (!empty($specialities)):?>
        <div class="section network-section">
            <div class="section-title toggle">
                <div class="left" >Специальности</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="section-content content">
                <div class="post">
                    <div class="post-body">
                        <?php foreach ($specialities as $spec):?>
                            <?php echo Degree::find_by_id($spec->degree_id)->title?>  
                            <?php echo Studyarea::find_by_id($spec->studyarea_id)->title?>  <br />
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif?>
        <div class="content-separator"></div>

        <?php
            //Setting up nessessary variables used in "_ask.inc" block;  
            $question_mode = 'uni';
            $current_object_id = $university->university_id;
            $current_category = 'university'; 
        ?>

        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_ask.inc";?>

        </div>
        <div class="clearer">&nbsp;</div>

    </div>



    <div class="right sidebar" id="sidebar-2">
        <?php
        //Setting up nessessary variables used in "_ask.inc" block;  
        $relation_university_id = $university->university_id;
        ?>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/uni_alumni.inc";?>
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/uni_student.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/uni_abitur.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news.php";?>          
    </div> 
    <div class="clearer">&nbsp;</div>

        </div>
        
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
<?php
    else:
    redirect_to('/notfound');
    endif;

?>        