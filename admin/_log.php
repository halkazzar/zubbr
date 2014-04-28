<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";
?>  

<div id="main">
    <?php
    if($data = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt')):
?>

<textarea rows="30" cols="80"  readonly><?php echo $data?></textarea>
<?php
    endif;
?>
</div>
                <!-- // #main -->