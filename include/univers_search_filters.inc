<?php
    $countries = Location::find_countries();
    
?>

<div class="section">
    <div class="section-title">
        <div class="left">Фильтр по названию</div>
        <div class="right">&nbsp;</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <li>
            <input name="query"  type="text"  id="questionText" value="<?php if(isset($_GET['query'])) echo $_GET['query'];?>"></input>
            </li>
        </ul>
    </div>
</div>



<div class="section">
    <div class="section-title">
        <div class="left">Фильтр по странам</div>
        <div class="right">ВУЗов</div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="section-content">
        <ul class="nice-list">
            <?php
                $i = 0; 
                if (!empty($countries)){
                    foreach ($countries as $country){
                        $total_universities = 0;
                        for($depth = 1; $depth <= 3; $depth++){
                            $total_universities  += University::count_by_keyword('', $depth, $country->location_id, '-1', '-1');
                            $result[$country->location_id] = $total_universities;
                        }
                    }
                }
                arsort($result);
            ?>
            <?php foreach ($result as $country_id => $total):?>
                <?php if($total > 0): ?>
                    <li <?php if(isset($_GET['location_value']) && is_numeric($_GET['location_value']) && $_GET['location_value']==$country_id) echo' class = "selected" '?>>
                        <div class="left "><img src="/images/locations/<?php echo $country_id?>.gif" alt="<?php $loc = Location::find_by_id($country_id); if(!empty($loc)) echo $loc->location_name ?>"/>&nbsp;&nbsp;<a href="/uni/search/?location_value=<?php echo $country_id?>"><?php echo Location::find_by_id($country_id)->location_name?></a></div>
                        <div class="right"><?php echo $total ?></div>
                        <div class="clearer">&nbsp;</div>
                    </li>
                    <?php endif; ?>
                <?php endforeach;?>
        </ul>
    </div>
</div>