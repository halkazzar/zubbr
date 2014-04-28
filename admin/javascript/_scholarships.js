/**
* Zubr
* Managing scholarship's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_scholarship").click(function() {
        var row = $(this);
        var scholarshipId = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить эту стипендию?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_scholarships.php", { 'delete': scholarshipId}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
                //Passing the variables to the controller
           //     'delete': universityId
           // });
        }
        return false;
        
    })
});
