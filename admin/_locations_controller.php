<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';

    if (isset($_GET['id'])){

        //Finding regions in the country
        $regions = Location::find_locations('region', $_GET['id']);

        //Finding cities accidently put directly after country, instead of being put after region
        $cities = Location::find_locations('city',$_GET['id']);
    }

    //Preparing the result to be shown in questions div
    $i = 0;
    if (!empty($regions)){
        foreach ($regions as $region){
            $result[$i]["type"]='region';
            $result[$i]["optionValue"]=$region->location_id;
            $result[$i]["optionDisplay"]=$region->location_name;

            $i++;

            //Finding cities in the current region 
            $cities_in_region = Location::find_locations('city', $region->location_id);
            if (!empty($cities_in_region)){
                foreach($cities_in_region as $city_in_region){
                    $result[$i]["type"] = 'city';
                    $result[$i]["optionValue"]=$city_in_region->location_id;
                    $result[$i]["optionDisplay"]=$city_in_region->location_name;
                    $i++;                
                }
            } 
        }    
    }

    if (!empty($cities)){
        foreach ($cities as $city){
            $result[$i]["type"]='city'; 
            $result[$i]["optionValue"]=$city->location_id;
            $result[$i]["optionDisplay"]=$city->location_name;     
            $i++;
        }    
    }


    //sending it to javascript which will display it
    echo json_encode($result);
?>