<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    //If user is logged in
    if (!$session->is_logged_in() && isset($_GET['ajax'])):
        //$error_code = 2;
//        redirect_to($_SERVER['HTTP_REFERER']);
        
        $result["logged_id"]=false;
        echo json_encode($result); //returning result to be displayed in referer page   
   
    elseif ($session->is_logged_in() && isset($_GET['ajax'])):
        $result["logged_id"]=true;
        echo json_encode($result);
    endif;
?>