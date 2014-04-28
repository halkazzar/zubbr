/**
* Zubr
* Managing relation's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/


$(document).ready(function(){
    $("#search").keyup(function() {
        
        var query = $(this).val();
        $("#result_table tbody tr").hide();
        $("#result_table tbody tr:contains('"+query+"')").show();
    })
});    


$(document).ready(function(){
    $(".delete_university_relation").live('click', function() {
        var row = $(this);
        var relationID = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить " + relationID + "?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_universities_relations.php", { 'delete': relationID}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});