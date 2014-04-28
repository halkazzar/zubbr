<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if(isset($_GET['call']) && ($_GET['call'] == 'st')){ 
        $studyareas = Studyarea::find_all();
        $result = array();    
        for($i = 0; $i<count($studyareas); $i++){
            $result[$i]['text'] = $studyareas[$i]->title;    
        }
        echo json_encode($result);    
    }
    elseif(isset($_GET['call']) && ($_GET['call'] == 'u')){
        $universities = University::find_all(0, -1, 'published');
        $synonyms = Synonym::find_all_published();
        
        $result = array(); 
        $needle = 0;   
        for($i = 0; $i<count($universities); $i++){
            $result[$i]['text'] = $universities[$i]->long_name;
            $result[$i]['alias'] = $universities[$i]->alias;
            $needle++;    
        }
        for($i = 0; $i<count($synonyms); $i++){
            $result[$i + $needle]['text'] = $synonyms[$i]->title;
            $uni = University::find_by_id($synonyms[$i]->university_id);
            if(!empty($uni)){
            $result[$i + $needle]['alias'] = $uni->alias;    
            }
                
        }
        echo json_encode($result);
    }
    elseif(isset($_GET['call']) && ($_GET['call'] == 's')){
        $scholarships = Scholarship::find_all();
        $result = array();    
        for($i = 0; $i < count($scholarships); $i++){
            $result[$i]['text'] = $scholarships[$i]->title;    
        }
        echo json_encode($result);    
    }

    elseif(isset($_GET['call']) && ($_GET['call'] == 'l')){
        $degrees = Location::find_cities();
        $result = array();    
        for($i = 0; $i < count($degrees); $i++){
            $result[$i]['text'] = $degrees[$i]->location_name;    
        }
        echo json_encode($result);    
    }
    //Registration page behavior
    elseif(isset($_GET['invoker']) && strstr($_GET['invoker'], 'registration_uni')){
        $universities  = University::find_all();
        $synonyms = Synonym::find_all();
        
        $result = array(); 
        $needle = 0;   
        for($i = 0; $i<count($universities); $i++){
            $result[$i]['text'] = $universities[$i]->long_name;
            $needle++;    
        }
        for($i = 0; $i<count($synonyms); $i++){
            $result[$i + $needle]['text'] = $synonyms[$i]->title;    
        }
        echo json_encode($result);    
    }
    //echo json_encode($studyareas);
?>