$(document).ready(function() {
    $('.first_name').editable('/include/_inline_editor_controller.php', {
        indicator : '',
        tooltip   : 'Щелкните для изменения...',
        style     : 'display: inline',
        onblur    : 'submit',
        placeholder: 'Введите имя',
        event     : 'click'
    });

    $('.first_name').live('click', edit);

    function edit(){
        $('.edit').editable('/include/_inline_editor_controller.php', {
            indicator : '',
            tooltip   : 'Щелкните для изменения...',
            style     : 'display: inline',
            onblur    : 'submit',
            placeholder: 'Имя',
            event     : 'click'
        });
    };
});

$(document).ready(function() {
    $('.last_name').editable('/include/_inline_editor_controller.php', {
        indicator : '',
        tooltip   : 'Щелкните для изменения...',
        style     : 'display: inline',
        onblur    : 'submit',
        placeholder: 'Фамилия',
        event     : 'click'
    });

    $('.last_name').live('click', edit);

    function edit(){
        $('.edit').editable('/include/_inline_editor_controller.php', {
            indicator : '',
            tooltip   : 'Щелкните для изменения...',
            style     : 'display: inline',
            onblur    : 'submit',
            placeholder: 'Фамилия',
            event     : 'click'
        });
    };
});

//JSON Suggesting for University and Studyarea
$(document).ready(function(){
    $('.suggest').live('focus', suggest);
    
    function suggest(){
        $.getJSON("/include/_profile_controller.php", {invoker : $(this).attr("id")},
        function(data){
            $('.suggest').jsonSuggest(data, {maxResults:20});
        }
        );    
    }

})



$(document).ready(function(){
    $('.suggest').change(function(){
        var text = $(this).val();
        $(this).attr("size", text.length);
        
        
        //Pulling data
        obj = $(this);
        base = obj.parent().parent();
        
        base_prev = base.prev();
        base_prev_prev = base_prev.prev();
       
        relation = base_prev_prev.text();
        
        monthofenroll = base_prev.find("span[id^=reg_monthofenroll_value]").text();
        yearofenroll = base_prev.find("span[id^=reg_yearofenroll]").text();
        monthofgraduation = base_prev.find("span[id^=reg_monthofgraduation_value]").text();
        yearofgraduation = base_prev.find("span[id^=reg_yearofgraduation]").text();
        
        university = base.find("input[id^=reg_university]").val();
        scholarship = base.find("span[id^=reg_scholarship_value]").text();
        degree = base.find("span[id^=reg_degree_value]").text();
        studyarea = base.find("input[id^=reg_studyarea]").val();
        
        $.getJSON("/include/_inline_editor_controller.php", { 
            //max_relations : $("#maxcount").text(),
            reg_relation_id             : relation,
            reg_monthofenroll_value     : monthofenroll,
            reg_yearofenroll            : yearofenroll,
            reg_monthofgraduation_value : monthofgraduation,
            reg_yearofgraduation        : yearofgraduation,
            reg_university              : university,
            reg_degree_value            : degree,
            reg_studyarea               : studyarea,
            reg_scholarship_value       : scholarship
        },  function(data){
            if (base_prev_prev.text() == -1){
                base_prev_prev.text(data.rel_id).css("background", "yellow");    
            }
            
            obj.val(text);
            
            //Let's show DELETE UNIVERSITY link
            
            base.find("a[class^=delete_university]").fadeIn();
                    
            //$("#wait").hide();  //This is callback function
        });
        obj.val('Сохранение');
    })
})






$(document).ready(function() {
    $("#addMoreUni").click(function(){
        var addmore = $(".additional_part_main").clone();

        //Incrementing overall count of universities
        var max = $("#maxcount").text();
        max = max*1 + 1;
        $("#maxcount").text(max);

        addmore.find("span[id=reg_relation]").attr('id', 'reg_relation' + max)

        addmore.find("span[id=reg_university_display]").attr('id', 'reg_university_display' + max)
        addmore.find("span[id=reg_university_value]").attr('id', 'reg_university_value' + max)

        addmore.find("span[id=reg_scholarship_display]").attr('id', 'reg_scholarship_display' + max)
        addmore.find("span[id=reg_scholarship_value]").attr('id', 'reg_scholarship_value' + max)

        addmore.find("input[id=reg_studyarea]").attr('id', 'reg_studyarea' + max)
        
        var event = jQuery.Event("change");
        addmore.find("input[id=reg_studyarea]").trigger(event);
        //addmore.find("span[id=reg_studyarea_value]").attr('id', 'reg_studyarea_value' + max)

        addmore.find("span[id=reg_degree_display]").attr('id', 'reg_degree_display' + max)
        addmore.find("span[id=reg_degree_value]").attr('id', 'reg_degree_value' + max)

        //addmore.find("span[id=reg_dayofenroll]").attr('id', 'reg_dayofenroll' + max)

        addmore.find("span[id=reg_monthofenroll_display]").attr('id', 'reg_monthofenroll_display' + max)
        addmore.find("span[id=reg_monthofenroll_value]").attr('id', 'reg_monthofenroll_value' + max)

        addmore.find("span[id=reg_yearofenroll]").attr('id', 'reg_yearofenroll' + max)

        //addmore.find("span[id=reg_dayofgraduation]").attr('id', 'reg_dayofgraduation' + max)

        addmore.find("span[id=reg_monthofgraduation_display]").attr('id', 'reg_monthofgraduation_display' + max)
        addmore.find("span[id=reg_monthofgraduation_value]").attr('id', 'reg_monthofgraduation_value' + max)

        addmore.find("span[id=reg_yearofgraduation]").attr('id', 'reg_yearofgraduation' + max)


        addmore.hide().appendTo("#container");


        addmore.fadeIn("fast");

        addmore.removeClass("additional_part_main");

        return false;
    })
});


$(document).ready(function(){
    $(".delete_university").live("click", delete_university);
    
    function delete_university(){
        var answer=confirm("Вы действительно хотите удалить эту запись?");
        if(answer){
        var uni_count  =  $("#maxcount").text();//Uni counter set on the page;
            uni_count  = uni_count*1 - 1;
            $("#maxcount").text(uni_count);
            
            obj = $(this);
            base = obj.parent().parent();
        
            base_prev = base.prev();
            base_prev_prev = base_prev.prev();
       
            relation = base_prev_prev.text();
            
            $.getJSON("/include/_inline_editor_controller.php", {relation_id : relation, action : 'delete_relation'}, function(data){
                if(data.deleted){
                    base.fadeOut();
                    base_prev.fadeOut();
                    base_prev_prev.fadeOut();
                }
            })    
        }
        return false;
    }
    
});