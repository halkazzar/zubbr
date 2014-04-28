/**
* Zubr
* Managing news's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_jobposting").click(function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить это объявление?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_jobpostings.php", { 'delete': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});