$(document).ready(function() {  
$('#invite_link').live('click', function(e) {
        e.preventDefault();  
        
        var dialog = '#invite';  
        //
        //Get the screen height and width  
                var maskHeight = $(document).height();  
                var maskWidth = $(window).width();  

        //Set height and width to mask to fill up the whole screen  
                $('#mask2').css({'width':maskWidth,'height':maskHeight});  

        //transition effect       
                $('#mask2').fadeTo(0, 0.5);    

        //Get the window height and width  
                var winH = $(window).height();  
                var winW = $(window).width();  

        //Set the popup window to center  
        $(dialog).css('top',  winH/2-$(dialog).height()/2 - 90);
        $(dialog).css('left', winW/2-$(dialog).width()/2 - 15);

        //transition effect  
        $(dialog).fadeIn(300);   
    });  

    //if close button is clicked  
    $('.window2 .close').click(function (e) {  
        //Cancel the link behavior  
        e.preventDefault();  
        $('#mask2, .window2').hide();  
    });       

    //if mask is clicked  
    $('#mask2').click(function () {  
        $(this).hide();  
        $('.window2').hide();  
    }); 
})
