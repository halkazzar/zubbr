/**
* Zubr
* Managing location deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/
$(document).ready(function(){
    $(".delete").click(function() {
        var answer = confirm("Вы действительно хотите удалить это расположенеие ?");
        if (answer) return true; else return false;
    });


});


$(document).ready(function(){
    $('#countries').change(function() {

        $.getJSON("/admin/_locations_controller.php", {
            id: $(this).val()     
        }, 

        //callback
        function(j){
            var regions = '';
            var cities = '';

            for (var i = 0; i < j.length; i++) {
                if (j[i].type == 'region') regions += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                if (j[i].type == 'city') cities += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#regions").html(regions);            
            $("#cities").html(cities);            
        })
    });
})

$(document).ready(function(){
    $('#regions').change(function() {

        $.getJSON("/admin/_locations_controller.php", {
            id: $(this).val()     
        }, 

        //callback
        function(j){
            var cities = '';

            for (var i = 0; i < j.length; i++) {
                if (j[i].type == 'city') cities += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
            }
            $("#cities").html(cities);            
        })
    });
})

