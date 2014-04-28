/**
* Zubr
* Managing specialities' deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_speciality").click(function() {
        var row = $(this);
        var specialityID = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить эту специальность?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_specialities.php", { 'delete': specialityID}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
                //Passing the variables to the controller
           //     'delete': universityId
           // });
        }
        return false;
        
    })
});