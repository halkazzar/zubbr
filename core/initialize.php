<?php
    /**
    * Zubr
    * Initializataion file
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */    
    
    error_reporting(E_ERROR);
    //error_reporting(E_ALL);
    
    mb_internal_encoding("UTF-8");
    $dateformat = 'Y-m-d H:i:s';
    $requested_uri;
    $pipe = '›';
    
    require_once("session.php");
    require_once("functions.php");
    
    require_once("config.php");
    require_once("database.php");
    require_once("user.php");
    require_once("errors.php");
    require_once("pagination.php");
    require_once("lastRSS.php");
    
    require_once("achievement.php");
    require_once("answer.php");
    require_once("degree.php");
    require_once("invitedemail.php");
    require_once("jobposting.php");
    require_once("location.php");
    require_once("news.php");
    require_once("news_uni.php");
    require_once("organization.php");
    require_once("option.php");
    require_once("optionsmap.php");
    require_once("promocode.php");
    require_once("scholarship.php");
    require_once("specialities.php");
    require_once("stats_universityview.php");
    require_once("studyarea.php");
    require_once("subscription.php");
    require_once("tag.php");
    require_once("tagmap.php");
    require_once("test.php");
    require_once("passed_test.php");
    require_once("test_data.php");
    require_once("testquestion.php");
    require_once("testquestiontype.php");
    require_once("testanswer.php");
    require_once("university.php");   
    require_once("university_slide.php");   
    require_once("university_synonym.php");   
    require_once("university_relation.php");   
    require_once("vote.php");   
    require_once("class.upload/class.upload.php");
    require_once("class.phpmailer/class.phpmailer-lite.php");
    require_once("class.vk/vk.php");
    
    require_once("user_achievement.php");
    require_once("question.php");
    
    
    if (isset($_SESSION['language'])){
    require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "languages". DIRECTORY_SEPARATOR . $_SESSION['language'] . ".lang.php");
    }
    else require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "languages". DIRECTORY_SEPARATOR . "ru.lang.php");

    
    define('FACEBOOK_APP_ID', '153185531368569');
    define('FACEBOOK_SECRET', 'b5aaff1cbc8d5d3f891a679ff15a690e');
    
    define('VKONTAKTE_APP_ID', '2225351');
    define('VKONTAKTE_SECRET', 'bbrNNHo3KbhLcLILrVNM');
    
    define('MAILRU_APP_ID', '603062');
    define('MAILRU_SECRET', 'a2dce2b913fd0be658d41f7a8fe27e6a');
    define('MAILRU_PRIVATE', '771522cd58fcd888256b885672ea3bc4 ');
?>