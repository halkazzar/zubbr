$(document).ready(function() {
    //Global variables - Input element, which holds displayed value and actual value
    var inputElement;
    
    //Popup menu's pages    
    var level_1 = $('#uni_countries');
    var level_2 = $('#uni_regions');
    var level_3 = $('#uni_cities');
    var level_4 = $('#uni_universities');

    //Selecting Contry
    $('#uni_countries ul li').click(function(){
        level_2.hide();
        level_3.hide();
        $.getJSON("/include/_modal_window_controller.php", {
             //Passing the variables to the controller
            id: $(this).attr("id")
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        redrawRegionsCities
        );
        
        //Returning false to deactivate the link, user has pressed
        //return false;
        return false;
    })
    function redrawRegionsCities(j){
        var regions = '';
        var cities = '';

        for (var i = 0; i < j.length; i++) {
            if (j[i].type == 'region') regions += "<li id='"+j[i].optionValue +"'>" + j[i].optionDisplay + "</li>";
            if (j[i].type == 'city') cities += "<li id='"+j[i].optionValue +"'>" + j[i].optionDisplay + "</li>"; 
            //if (j[i].type == 'city') cities += "<li id='"+j[i].optionValue + "'><a id='"+j[i].optionValue + "' href ='#'>" + j[i].optionDisplay + "</a></li>"; 
        }
        if (regions != ""){
            level_1.hide();
            level_2.fadeIn("fast");
            $("#uni_regions ul").html(regions).fadeIn("fast");           
        }
        else{
            level_1.hide();
            level_3.fadeIn("fast");
            $("#uni_cities ul").html(cities).fadeIn("fast");    
        }

    }    
    
    //Selecting Region
    $('#uni_regions ul li').live('click', function(){
        $.getJSON("/include/_modal_window_controller.php", {

            //Passing the variables to the controller
            id: $(this).attr("id")
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        redrawCities
        );

        //Returning false to deactivate the link, user has pressed
        //return false;
        return false;
    })
    function redrawCities(j){
        var cities = '';

        for (var i = 0; i < j.length; i++) {
            if (j[i].type == 'city') cities += "<li id='"+j[i].optionValue +"'>" + j[i].optionDisplay + "</li>"; 
        }
        level_2.hide();
        level_3.fadeIn("fast");
        $("#uni_cities ul").html(cities).fadeIn("fast");              
    }
    
    //Selecting City
    $('#uni_cities ul li').live('click', function(){
        $.getJSON("/include/_modal_window_controller.php", {

            //Passing the variables to the controller
            id: $(this).attr("id")
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        redrawUniversities
        );

        //Returning false to deactivate the link, user has pressed
        //return false;
        return false;
    })
    function redrawUniversities(j){
        var universities = '';

        for (var i = 0; i < j.length; i++) {
            if (j[i].type == 'university') universities += "<li id='"+j[i].optionValue +"'>" + j[i].optionDisplay + "</li>"; 
        }
        level_3.hide();
        level_4.fadeIn("fast");
        $("#uni_universities ul").html(universities).fadeIn("fast");              
    }
    
    //Selecting University
    $('#uni_universities ul li').live('click', function(){
        var id = $(this).attr("id");
        value = $(this).text();
          
        $('.window').hide();
        inputElement.text(value);        
        inputElement.next().text(id);
        $("#mask").hide();
        
        var event = jQuery.Event("change");
        inputElement.trigger(event);
        
        //Pulling data
        base = inputElement.parent().parent();
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
        });
        inputElement.text('Сохранение');
        $("#wait").show();
    })


    
    //Entering point
    $('.reg_university').live('click', function(e) {  
        level_1.show();
        level_2.hide();
        level_3.hide();
        level_4.hide();
        
        var dialog = '#university_selector';  
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