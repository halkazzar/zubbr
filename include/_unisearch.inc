<div class="post">
    <div id="boxes">
        <div id="location_selector" class="window">
            <div id="loc_countries" class="col-countries left">
                <ul class="countries_regions-list">
                    <?php 
                        $countries = Location::find_locations('country',0);
                        foreach ($countries as $country):
                        ?>
                        <li id="<?php echo $country->location_id?>"><?php echo $country->location_name;?> <a href="#" title="<?php echo $country->location_name;?>" class="right all">Все&nbsp;города</a></li>
                        <?php endforeach;?>
                </ul>
            </div>
            <div id="loc_regions" class="col-regions left">
                <ul class="countries_regions-list">
                </ul>
            </div>
            <div id="loc_cities" class="col-cities left">
                <ul class="cities-list">
                </ul>
            </div> 

        </div>
        <div id="mask">
            </div>
    </div>


    <div id="search" class="myform">
        <form name="search_uni" action="/uni/search/" method="get">
            <label>Я ищу:</label>
            <input name="query"  type="text"  id="questionText"></input>
            <button type="submit" id="submitQuery" class="right">Найти</button>
            <label id="advanced_search"><a href="#">Расширенный поиск</a></label>

            <div class="clear"></div>
            <div id="advanced">
                <div class="col2 ">
                    <label>Расположение:</label>
                    <a href="#" style="text-decoration: none"><span class="reg_location" id="reg_location_display">щелкните</span>
                    <input class="reg_location hidden" name="location_value"  type="text"  id="location_value"></input></a>
                    <br /><br />
                    <label>Стоимость обучения: от</label>
                    <input name="range_min"  type="text"  id="range_min"></input>
                    <label>до</label>
                    <input name="range_max"  type="text"  id="range_max"></input>
                
                 </div>
                <div class="clear"></div>
            </div>
        </form>
    </div> 

        </div>