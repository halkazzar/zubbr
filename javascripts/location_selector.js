$(document).ready(function() {
    //Global variables - Input element, which holds displayed value and actual value
    var inputElement;
    
    //Popup menu's pages
    var level_1 = $('#loc_countries');
    var level_2 = $('#loc_regions');
    var level_3 = $('#loc_cities');

    $('#loc_countries ul li').click(function(){
        var id = $(this).attr("id");
        value = $(this).text();
          
        
        //inputElement.text(value);        
        inputElement.next().text(id);
        //inputElement.next().val(id);
        
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

    $('#loc_regions ul li').live('click', function(){
        var id = $(this).attr("id");
        value = $(this).text();
          
        
        inputElement.text(value);        
        inputElement.next().text(id);
        inputElement.next().val(id);
        
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

    $('#loc_cities ul li').live('click', function(){
        var id = $(this).attr("id");
        value = $(this).text();
          
        $('.window').hide();
        inputElement.text(value);        
        inputElement.next().text(id);
        inputElement.next().val(id);
        $("#mask").hide();
        
        var event = jQuery.Event("change");
        inputElement.trigger(event);        
        
        $.post("/include/_inline_editor_controller.php", { 
            id      : inputElement.next().attr("id"), 
            value   : inputElement.next().text()
        });
          
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
            $("#loc_regions ul.countries_regions-list").html(regions).fadeIn("fast");           
        }
        else{
            level_1.hide();
            level_3.fadeIn("fast");
            $("#loc_cities ul.cities-list").html(cities).fadeIn("fast");    
        }

    }

    function redrawCities(j){
        var cities = '';

        for (var i = 0; i < j.length; i++) {
            if (j[i].type == 'city') cities += "<li id='"+j[i].optionValue +"'>" + j[i].optionDisplay + "</li>"; 
        }
        level_2.hide();
        level_3.fadeIn("fast");
        $("#loc_cities ul.cities-list").html(cities).fadeIn("fast");              
    }
    
    
    $('#loc_countries ul li').hover(function(){
        $(this).find('a.all').show();    
    }, function(){
        $(this).find('a.all').hide();    
    }
    
    )
    
    $('a.all').click(function(){
        var parent_li = $(this).parent();
        inputElement.text($(this).attr("title"));
        inputElement.next().val(parent_li.attr("id"));
        //var id = $(this).attr("id");
        //value = $(this).text();
        $(".window").hide();
        $("#mask").hide();
    })
    
        //Entering point
    $('.reg_location').live('click', function(e) {  
        level_1.show();
            $('a.all').hide();
        level_2.hide();
        level_3.hide();
        
        var dialog = '#location_selector';  
        //
        //Get the screen height and width  
                var maskHeight = $(document).height();  
                var maskWidth = $(window).width();  

        //Set height and width to mask to fill up the whole screen  
                $('#mask').css({'width':maskWidth,'height':maskHeight});  

        //transition effect       
                $('#mask').fadeTo(0, 0.8);    

        //Get the window height and width  
                var winH = $(window).height();  
                var winW = $(window).width();  

        //Set the popup window to center  
        //$(id).css('top',  winH/2-$(id).height()/2);
        inputElement = $(this);
        var offset = $(this).offset();
        var x = offset.left;
        var y = offset.top;

        
        
        $(dialog).css('top',  y);  

        $(dialog).css('left', x);  

        //transition effect  
        $(dialog).fadeIn(300);
        
        return false;   
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