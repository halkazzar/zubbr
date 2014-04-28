/**
* Zubr
* Managing organization deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_organization").click(function() {
        var row = $(this);
        var orgAlias = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить " + orgAlias + "?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_organizations.php", { 'delete': orgAlias}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});