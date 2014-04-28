<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/core/initialize.php";

    if($session->is_logged_in()){
        //User fields:
        $user = User::find_by_id($session->user_id);

        if ($_POST["id"] == "reg_firstname"){
            $user->first_name = $_POST["value"];

        }
        if ($_POST["id"] == "reg_lastname"){
            $user->last_name = $_POST["value"];
        }
        if ($_POST["id"] == "reg_email"){
            $user->email = $_POST["value"];
        }
        if ($_POST["id"] == "reg_location_value"){
            $user->location_id = $_POST["value"];
        }
        $user->save();
        echo $_POST["value"];
        
        //User <-> University relation fields:
        if (isset($_GET["reg_relation_id"]) && !empty($_GET["reg_relation_id"])){
            if ($_GET["reg_relation_id"] == -1){   //If this is a new relation
                $relation = new Relation();
            }
            else {
                $relation = Relation::find_by_id(trim($_GET["reg_relation_id"]));
            }
            
            $relation->user_id = $session->user_id;
            
                //finding univeristy
                $uni = University::find_by_long_name($_GET['reg_university']);
                
                if(!empty($uni)){
                $relation->university_id = $uni->university_id;    
                }
                else{
                $new_uni = new University();
                $new_uni->long_name = $_GET['reg_university'];
                $new_uni->status = 'draft';
                $new_uni->save();
                $relation->university_id = $new_uni->university_id;    
                }
            
            $relation->scholarship_id = $_GET['reg_scholarship_value'];
            $relation->degree_id = $_GET['reg_degree_value'];
            
                //finding studyarea
                $study = Studyarea::find_by_title($_GET['reg_studyarea']);
                if(!empty($study)){
                $relation->studyarea_id = $study->studyarea_id;    
                }
                else{
                $new_study = new Studyarea();
                $new_study->title = $_GET['reg_studyarea'];
                $new_study->save();
                $relation->studyarea_id = $new_study->studyarea_id;    
                }
            
            
            $relation->scholarship_id = $_GET['reg_scholarship_value'];
            $relation->date_of_enroll = date($dateformat, mktime(0,0,0,$_GET['reg_monthofenroll_value'], 1, $_GET['reg_yearofenroll']));
            $relation->date_of_graduation = date($dateformat, mktime(0,0,0,$_GET['reg_monthofgraduation_value'], 1, $_GET['reg_yearofgraduation']));
            if ($relation->date_of_graduation < getdate()) $relation->role = 'alumni';
            if ($relation->date_of_graduation > getdate()) $relation->role = 'student';
            
            $relation->save();
            
            //Echo back relation's id, which will be placed where -1 had been
            $result['rel_id'] = $relation->university_relation_id;
            echo json_encode($result);
        }
    }

        if (isset($_GET["action"]) && !empty($_GET["action"])){
            if(Relation::delete_by_id(trim($_GET["relation_id"]))) {
                $result['deleted']='true';
                echo json_encode($result);
            } else {
                $result['deleted']='false';
            }
            
        }

?>