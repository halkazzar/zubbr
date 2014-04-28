/**
* Zubr
* Managing degrees's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_degree").click(function() {
        var row = $(this);
        var degreeID = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить эту квалификацию?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_degrees.php", { 'delete': degreeID}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});