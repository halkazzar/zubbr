<?php

    //require_once $_SERVER['DOCUMENT_ROOT'] . "/core/functions.php";
    //redirect_to('/uni/energo/');
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/stylesheets/form.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.json2.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/json_suggest/jquery.jsonSuggest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/advanced_search.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/location_selector.js' ></script>";
    
    $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' ВУЗы ' .$pipe. ' Центр Управления ';
    $meta_keywords = 'казгу, кбту, муит, назарбаев университет, гарвард, university, подбор вуза, поиск вуза';
    $meta_description = 'Узнай какими вузами интересуются больше всего твои друзья и сверсники';
    
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";


    //Navigation block
    $sub_navigation = "universities";
    $current_tab = "start";
    require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";     
?>

<div class="main" id="main-two-columns">
    <div class="left sidebar" id="main-left">
        <div class="post">
            <?php require_once  $_SERVER['DOCUMENT_ROOT'] . "/include/univers_promoted.inc";?>        
        </div>    
        <div class="clearer">&nbsp;</div>
        

        <div class="col3 left">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/include/univers_topasked.inc";?>
        </div>

        <div class="col3 col3-mid left">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/univers_iwant.inc";?>         
        </div>

        <div class="col3 right">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/univers_topviewed.inc";?>         
        </div>

        <div class="clearer">&nbsp;</div>
        
        <div class="post">
            <?php include  $_SERVER['DOCUMENT_ROOT'] . "/include/univers_oftheweek.inc";?>        
        </div>
    </div>


    <div class="right sidebar" id="sidebar-2">
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/univers_search_filters.inc";?>     
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_banner.inc";?>     
                  
    </div>
    <div class="clearer">&nbsp;</div>
</div>
        
        
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
        