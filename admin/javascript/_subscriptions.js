/**
* Zubr
* Managing subscription's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_subscription").click(function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить эту подписку?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_subscriptions.php", { 'delete': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});


$(document).ready(function(){
    $('#subscription_category').change(function() {

        $.getJSON("/admin/_questions_controller.php", {
            category: $(this).val()     
        }, 

        //callback
        function(j){
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options += '<option value="' + j[i].id + '">' + j[i].display + '</option>';
            }
            $("#category_object_id").html(options);            
        })
    });
})



