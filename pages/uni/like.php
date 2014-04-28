<?php
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
    
    if ($session->is_logged_in()):
    $page_title = '}:) '.$pipe.' ВУЗы ' .$pipe. ' Вы хотите учиться ';
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";

    $alias = $_GET['alias'];
    if (!empty($alias)){
        $relation = Relation :: find_by_all('abitur', University::find_by_alias($alias)->university_id, $session->user_id);
        if (empty($relation)) {
            $relation = new Relation();
            $relation->user_id = $session->user_id;
            $relation->university_id = University::find_by_alias($alias)->university_id;
            $relation->role = 'abitur';
            
            if ($relation->save()) $error_code = -1;    
        }
    }

    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "users";
    $current_tab = "all users";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     


?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="col3 left">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/include/users_topanswered.inc";?>
        </div>

        <div class="col3 col3-mid left">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/users_verified.inc";?>         
        </div>

        <div class="col3 right">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/users_whowanttoo.inc";?>             
        </div>
    </div>

    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news.inc";?>          
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
<?php endif;?>