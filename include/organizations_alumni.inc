
<?php

    $random = 2;
    $temp = $random;
    $org_list = Organization::find_random($random);

    if (!empty($org_list)): 
        foreach ($org_list as $org):
            if ($temp != $random) echo '<div class="university-separator"></div>'
        ?>        

        <div class="section-university">
            <div class="post"> 
                <p><a href="/org/<?php echo $org->alias ?>/"><img src="/images/organizations/avatars/<?php echo $org->alias . $org->picture_extension ?>" alt="Фото <?php echo $org->short_name?>" class="left bordered" /></a></p> 
                <h3><a href="/org/<?php echo $org->alias ?>/"><?php echo $org->short_name?></a></h3>
                <p><?php echo $org->short_description?></p>
                <div class="clearer">&nbsp;</div> 
            </div>
        </div>   <?php            
            $temp--;    
            endforeach;
        endif;

?>