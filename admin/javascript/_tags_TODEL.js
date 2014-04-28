$(document).ready(function(){
    $("select[id=tag_group]").change(function(){
        var group_id =  $(this).val();
        $.get("/admin/_tags.php", {'filter_group' : group_id, 'ajax': true}, function(data){

            var holder = $("#filter_results").html(data);        
        })

    });    

    //$("#tag_names").fcbkcomplete({
    //        json_url: "/include/_ask_controller.php",
    //        height: 10,
    //        newel: true,
    //        delay: 0.001,
    //        firstselected: true,
    //        complete_text: 'Разделайте теги запятой',
    //        maxitimes: 10,
    //        onselect: function(data){
    //                  var myObject = eval('(' + data + ')');
    //                  var option = "<option value="+myObject._value+">"+ myObject._value +"</option>";
    //                  $("#default_tag_value").append(option);
    //                  },
    //        onremove: function(data){
    //                  var myObject = eval('(' + data + ')');
    //                  $("#default_tag_value").find('option[value='+myObject._value+']').remove();
    //        }
    //    });
    $("#tag_from").fcbkcomplete({
        json_url: "/include/_ask_controller.php",
        height: 10,
        newel: false,
        delay: 0.001,
        firstselected: true,
        complete_text: 'Разделайте теги запятой',
        maxitems: 4
    });
    $("#tag_to").fcbkcomplete({
        json_url: "/include/_ask_controller.php",
        height: 10,
        newel: false,
        delay: 0.001,
        firstselected: true,
        complete_text: 'Разделайте теги запятой',
        maxitems: 1
    });
});

$(document).ready(function(){
    $(".delete_tag").live("click", function() {
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот тег?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_tags.php", { 'delete_tag': id}, function() {
                location.href='/admin/page/tags/';
            } );    
        }
    })
});


    
    
    