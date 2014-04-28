<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/initialize.php';

    if (isset($_GET['category'])){

        //Finding options in the category
        if ($_GET['category'] == 'university')      $options = University::find_all();
        if ($_GET['category'] == 'location')        $options = Location::find_cities();
        if ($_GET['category'] == 'scholarship')     $options = Scholarship::find_all();
        if ($_GET['category'] == 'question')        $options = Question::find_all();

    }

    //Preparing the result to be shown in questions div
    $i = 0;
    if (!empty($options) && ($_GET['category'] == 'university')){
        foreach ($options as $option){
            $result[$i]["id"]=$option->university_id;
            $result[$i]["display"]=$option->short_name;
            $i++; 
        }    
    }
    if (!empty($options) && ($_GET['category'] == 'location')){
        foreach ($options as $option){
            $result[$i]["id"]=$option->location_id;
            $result[$i]["display"]=$option->location_name;
            $i++; 
        }    
    }
    if (!empty($options) && ($_GET['category'] == 'scholarship')){
        foreach ($options as $option){
            $result[$i]["id"]=$option->scholarship_id;
            $result[$i]["display"]=$option->title;
            $i++; 
        }    
    }
    if (!empty($options) && ($_GET['category'] == 'question')){
        foreach ($options as $option){
            $result[$i]["id"]=$option->question_id;
            $result[$i]["display"]=substr($option->question_body, 0, 50);
            $i++; 
        }    
    }
    //sending it to javascript which will display it
    echo json_encode($result);
?>