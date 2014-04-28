<?php
    /**
    * Zubr
    * Question listing asking block
    * To use it properly 
    *   '$question_mode'
    *   '$current_id'        - 2 mode
    *   '$current_object_id'  - 1 mode
    *   '$current_category' variables are required - 1 mode
    * @package Zubr
    * @author Dauren Sarsenov 
    * @version 1.0, 01.09.2010
    * @since engine v.1.0
    * @copyright (c) 2010+ by Dauren Sarsenov
    */
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';
?>

<?php
    if(!empty($_GET['ajax'])){
        if($_GET['invoker'] == 'test'){

            $tag_id = $_GET['tag_id'];

            if(Tag::find_by_id($tag_id)->group_id != 0){
                $syn_tags = Tag::find_by_group_id(Tag::find_by_id($tag_id)->group_id);
            }
            else{
                $syn_tags[] = Tag::find_by_id($tag_id);
            }

            $tests = array();

            foreach($syn_tags as $syn_tag){
                $temp_t = Tagmap::find_by_object_type_tag_id('test', $syn_tag->tag_id);
                if(!empty($temp_t)){
                foreach ($temp_t as $tagmap_t) {
                    if(!in_array(test::find_by_id($tagmap_t->object_id), $tests)){
                        $tests[] = test::find_by_id($tagmap_t->object_id);
                    }
                }
            };
            }

            if(ctype_digit($_GET['first']) && ctype_digit($_GET['last'])){
                $per_page = $_GET['last'] - $_GET['first'] + 1;
                $page = $_GET['last'] / $per_page;

                $total = count($tests);
                $pagination = new Pagination($page, $per_page, $total);
                $tests =  array_slice($tests, $pagination->offset(), $per_page);
                $test_mode = 'ready';    
            }

            header('Content-Type: text/xml');
            echo '<data>';
            echo '<total>' . $total . '</total>';
        }
    }

?>

<?php
    

    
    switch ($test_mode) {
        case 'lang':
            $tests = Test::find_by_lang($lang_id, $per_page, $pagination->offset());
            break;
            
        case 'random':
            $tests = Test::find_random(3);
            break;
        case 'ready':
            break;
    }

    if (!empty($tests)) foreach($tests as $test): ?>
    <?php if(!empty($_GET['ajax'])) echo '<result><res_id>'.$article->news_id.'</res_id><res_data><![CDATA[';?>
    <div class="post">
        <div class="archive-post-date">
            <img src="/images/test.png" alt=""/>
        </div>

        <div class="archive-post-title">
            <h3 id='test<?php echo $test->test_id?>'>
                <a href="/test/passing/<?php echo $test->test_id?>">
                <?php echo $test->test_title?></a></h3>

            <div class="post-date">тест пройден 
                <?php $passed = Passed_test::test_passed_count($test->test_id); 
                    if(!$passed) 
                    { echo "0";}
                    else {echo $passed;}

                    $ld = substr($passed, -1); //Last Digit
                    $sld = substr($passed, -2,1); //Second from Last Digit
                    if ($ld =='0' OR $ld =='1' OR $ld >= '5' OR $sld=='1') echo " раз";
                    else echo " разa";
                ?>. 

                <span class="tag"> 
                            <?php
                                $tagmaps = Tagmap::test_get_tags($test->test_id);
                                if (!empty($tagmaps)):
                                    foreach ($tagmaps as $tagmap):
                                    ?>

                                    <a href="/tag/<?php echo $tagmap->tag_id?>/"><?php echo Tag::find_by_id($tagmap->tag_id)->tag_name?></a>

                                    <?php endforeach; else:?>
                                Без тегов
                                <?php endif;?>
                        </span>
            
            
            </div>
        </div>

        <div class="clearer">&nbsp;</div>
    </div>

    <div class="archive-separator"></div>
    <?php if(!empty($_GET['ajax'])) echo ']]></res_data></result>';?>
    <?php endforeach;?>
<?php if(!empty($_GET['ajax'])) echo '</data>';?>