$(document).ready(function() {
    //Global variables - Input element, which holds displayed value and actual value
    var inputElement;

    //Popup menu's pages
    var level_1 = $('#months');

    //Selecting Role
    $('#months ul li').live('click', function(){
        var id = $(this).attr("id");
        value = $(this).text();

        inputElement.text(value);        
        inputElement.next().text(id);
        $('.window').hide();
        $("#mask").hide();

        var event = jQuery.Event("change");
        inputElement.trigger(event);
        //alert(inputElement.parent().children().html());
        
        //Pulling data
        base = inputElement.parent().parent().next();
        base_prev = base.prev();
        base_prev_prev = base_prev.prev();
       
        relation = base_prev_prev.text();
        
        monthofenroll = base_prev.find("span[id^=reg_monthofenroll_value]").text();
        yearofenroll = base_prev.find("span[id^=reg_yearofenroll]").text();
        monthofgraduation = base_prev.find("span[id^=reg_monthofgraduation_value]").text();
        yearofgraduation = base_prev.find("span[id^=reg_yearofgraduation]").text();
        
        university = base.find("span[id^=reg_university_value]").text();
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
            reg_university_value        : university,
            reg_degree_value            : degree,
            reg_studyarea               : studyarea,
            reg_scholarship_value       : scholarship
        },  function(data){
            if (base_prev_prev.text() == -1){
                base_prev_prev.text(data.rel_id).css("background", "yellow");    
            }
            inputElement.text(value);        
            $("#wait").hide();  //This is callback function
            base.find("a[class^=delete_university]").fadeIn();
        });
        inputElement.text('Сохранение');
        $("#wait").show();
    })



    //Entering point
    $('.reg_month').live('click', function(e) {
        level_1.show();

        var dialog = '#month_selector';  
        //
        //Get the screen height and width  
        var maskHeight = $(document).height();  
        var maskWidth = $(window).width();  

        //Set height and width to mask to fill up the whole screen  
        $('#mask').css({'width':maskWidth,'height':maskHeight});  
        $('#wait').css({'width':maskWidth,'height':maskHeight});
        //transition effect       
        $('#mask').fadeTo(0, 0.8);    

        //Get the window height and width  
        var winH = $(window).height();  
        var winW = $(window).width();  

        //Set the popup window to center  

        inputElement = $(this);

        var offset = $(this).offset();
        var x = offset.left;
        var y = offset.top;



        $(dialog).css('top',  y);  

        $(dialog).css('left', x);  

        //transition effect  
        $(dialog).fadeIn(300);   
    });  

    //if close button is clicked  
    $('.window .close').click(function (e) {  
        //Cancel the link behavior  
        e.preventDefault();  
        $('#mask, .window').hide();  
    });       

    //if mask is clicked  
    $('#mask').click(function () {  
        $(this).hide();  
        $('.window').hide();  
    });

    return true;
});