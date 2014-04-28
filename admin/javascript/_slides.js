/**
* Zubr
* Managing slide deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_slide").click(function() {
        var row = $(this);
        var slideId = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот слайд ?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_slides.php", { 'delete': slideId}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
                //Passing the variables to the controller
           //     'delete': universityId
           // });
        }
        return false;
        
    })
});