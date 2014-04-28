<?php
    //Description: User pass test here
    //
    //Directory: /test/index.php
    //
    //Created: by Dd on Dec 15, 2009
    //
    //Last Edit: by Dd on Feb 27, 2010
    //
    //Last edit comment: - added new CSS



    //require_once needed class which extends all others
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if(isset($_GET['test']))
    {
        //check if isset $test
        $test_id = $_GET['test'];
        //check if test is integer and really is in the DB
        //Output test and description
        $test = Test::find_by_id($test_id);
    }
    if(!empty($test)):    
        $page_title = '}:) - Образование в Казахстане и за рубежом '.$pipe.' Тесты '. $pipe . ' Сдать тест по '. Test::find_by_id($test_id)->test_title;
        $temp = Test::find_by_id($test_id)->test_title;
        $meta_keywords = "баллы по $temp, бесплатные тесты по $temp, варианты $temp, $temp, $temp 2010, $temp 2010 ответы, $temp 2010 скачать, $temp 2011, $temp 2011 ответы, $temp 2011 скачать, вопросы по $temp, $temp онлайн, $temp скачать, $temp тестирование, $temp тесты, $temp тесты бесплатно, $temp тесты скачать";
        
        $meta_description = 'Сдай пробный тест по ' .Test::find_by_id($test_id)->test_title.' и узнай поступишь ли ты на грант';
    
    

    //Error redirect to home page
    //else header('Location: '.$my->PATH_HTTP);    

    $include_in_head = '<link rel="stylesheet" type="text/css" href="/javascripts/paginator3000/paginator3000.css" media="screen" />';
    $include_in_head.= "\n <script type='text/javascript' src='/ajax/ajax.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/ajax/GoTest.js' ></script>";
    $include_in_head.= "\n <script type='text/javascript' src='/javascripts/paginator3000/paginator3000.js' ></script>";

    // template: include top part
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/top.inc";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/info_block.inc";
    //Navigation block
    $sub_navigation = "tests";
    $current_tab = "in russian";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/navigation.inc";
?>

<div class="main" id="main-two-columns">

<div class="left" id="main-left">
<div class="post">
<div class="left">
    <div class="post-title"><h2><?php echo $test->test_title?></h2></div>            
    <div class="post-body"><p class="large"><?php echo $test->test_desc?></p></div>
    <div class="post-body"><p class="large"><?php $count = count(Testquestion::find_by_test_id($test->test_id)); echo 'В тесте ' . $count . ' вопросов. '?></p></div>
</div>
<div class="right">

    <div id="test_timer" class="hidden">
        <input id="time" value="0" class="hidden"/>
        <input id="status" value="1" class="hidden"/>
        <div class="left">
        <p class="nopadding large nomargin">Общее время</p>
        <h2><span id="test_timer_clock">0:00:00
        </span></h2>
        </div>
        <div class="right">
        <div id="pause_timer" class="pause">
        </div>
        </div>
        <div class="clearer">&nbsp;</div>
     </div>
</div>
<div class="clearer">&nbsp;</div>

<div class = "content-separator">&nbsp;</div>
<div id="go_test" class="nopadding">
<!---------------------------------------------------->


<script type="text/javascript">
<?php 
    echo    "var questions = new Array(); \n";
    //Get question amount of the test
    $questions = Testquestion::list_all_questions($test_id);
    $i=0;
    foreach ($questions as $question) {
        echo "questions[".$i++."]='{$question->question_id}';\n";

    }

?>
</script>

<div class="clear">
<span style="position:relative; top:3px; margin-right:10px; float:left; font-weight: bold;">Когда будете готовы нажмите:</span> <a class='rbutton' onClick='GoTest(<?=$test_id?>, 0, 0, "next",questions)'><span>СТАРТ</span></a>
        
</div>
</div>
            
</div>
</div>

        <div class="right sidebar" id="sidebar-2">
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_news_recent.inc";?>
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_user_rating.inc";?>	 
			<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_partners.inc";?>	 	 
			</div>
        <div class="clearer">&nbsp;</div>
    </div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/bottom.inc";?>
<?php
    else:
    redirect_to('/notfounnd');
    endif;
?>