$(document).ready(function() {
    //Global variables - Input element, which holds displayed value and actual value
    var inputElement;
    
    //Popup menu's pages    
    var level_1 = $('#roles');
     
    //Selecting Role
    $('#roles ul li').live('click', function(){
        var id = $(this).attr("id");
        value = $(this).text();
          
        
        inputElement.val(value);        
        inputElement.next().val(id);
        $('.window').hide();
        $("#mask").hide();
        if (id != "abitur"){
            $(".additional_part").fadeIn("fast");
        } else {
            $(".additional_part").fadeOut("fast");
        }
        
        var event = jQuery.Event("change");
        inputElement.trigger(event);
        
    })


    
    //Entering point
    $('.reg_role').live('focus', function(e) {  
        level_1.show();
        
        var dialog = '#role_selector';  
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