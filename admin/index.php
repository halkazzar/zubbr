<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';

    if (!$session->is_logged_in() || User::find_by_id($session->user_id)->system_role != 'admin'):
        redirect_to("/");     
        else:

        require_once "top.inc";

        if (isset($_GET['page'])) $page = $_GET['page']; else $page = 'dashboard';


    ?>                                 
    <body>
        <div id="wrapper">    
            <? require_once "navigation.inc"; ?>
            <div id="containerHolder">
                <div id="container">
                    <?php require_once 'sidebar.inc'?>                
                    <?php 
                        switch ($page) {
                            case 'users':
                                require_once "_users.php";
                                break;

                            case 'universities':
                                require_once "_universities.php"; 
                                break;

                            case 'slides':
                                require_once "_slides.php"; 
                                break;

                            case 'tests':
                                require_once "_tests.php"; ;
                                break;
                            case 'testquestions':
                                require_once "_testquestions.php"; ;
                                break;

                            case 'locations':
                                require_once "_locations.php"; 
                                break;

                            case 'scholarships':
                                require_once "_scholarships.php"; 
                                break;
                            case 'degrees':
                                require_once "_degrees.php"; 
                                break;
                            case 'studyareas':
                                require_once "_studyareas.php"; 
                                break;
                            case 'specialities':
                                require_once "_specialities.php"; 
                                break;
                            case 'universities_relations':
                                require_once "_universities_relations.php"; 
                                break;
                            case 'questions':
                                require_once "_questions.php"; 
                                break;
                            case 'send_question':
                                require_once "_send_question.php"; 
                                break;
                            case 'answers':
                                require_once "_answers.php"; 
                                break;
                            case 'send_answer':
                                require_once "_send_answer.php"; 
                                break;
                            case 'promotion':
                                require_once "_promotion.php"; 
                                break; 
                            case 'news':
                                require_once "_news.php"; 
                                break;
                            case 'jobpostings':
                                require_once "_jobpostings.php"; 
                                break;
                            case 'subscriptions':
                                require_once "_subscriptions.php"; 
                                break;
                            case 'organizations':
                                require_once "_organizations.php"; 
                                break;
                            case 'tags':
                                require_once "_tags.php"; 
                                break;
                            case 'dashboard':
                                require_once "_dashboard.php"; 
                                break;
                            case 'log':
                                require_once "_log.php"; 
                                break;                               
                        }
                    ?>
                    <div class="clear"></div>
                </div>
                <!-- // #container -->
            </div>	
            <!-- // #containerHolder -->
            <p id="footer">Feel free to use and customize it. <a href="http://www.perspectived.com">Credit is appreciated.</a></p>
        </div>
        <!-- // #wrapper -->
    </body>
    </html>
    <?php endif;?>
