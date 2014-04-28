$(document).ready(function() {
        var dialog = '#social_network';  
        //
        //Get the screen height and width  
                var maskHeight = $(document).height();  
                var maskWidth = $(window).width();  

        //Set height and width to mask to fill up the whole screen  
                $('#wait').css({'width':maskWidth,'height':maskHeight});  

        //transition effect       
                $('#wait').fadeTo(0, 0.5);    

        //Get the window height and width  
                var winH = $(window).height();  
                var winW = $(window).width();  

        //Set the popup window to center  
        $(dialog).css('top',  winH/2-$(dialog).height()/2 - 90);
        $(dialog).css('left', winW/2-$(dialog).width()/2 - 15);

        //transition effect  
        $(dialog).fadeIn(300);
})
 

$(document).ready(function() {  

var level_1 = $('#reg');

$('#login_link').live('click', function(e) {
        e.preventDefault();  
        
        var dialog = '#basic';  
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

$('#remind_pass_link').live('click', function(e) {
        e.preventDefault();  
        $("#basic").hide(); //Let's hide LOGIN FORM
        var dialog = '#remind_pass';  
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

$(document).ready(function(){
    $('div.toolbar a').click(function(){
        link = $(this); 
        href = $(this).attr("href");
        a_class = $(this).attr("class");
        
        $.getJSON("/include/_logged_in_controller.php", {ajax : 1}, redraw);        
        return false;
    });
    
    function redraw(data){
        if (data.logged_id == ""){
            alert("Пожалуйста зарегистрируйтесь либо войдите в систему");
        }
        else{
            q_id = link.parent().attr("id");       //id is = smth like q32
            q_id = q_id.replace("q", "")    //removing leading 'q'
            
            a_id = link.parent().attr("id");       //id is = smth like a32
            a_id = q_id.replace("a", "")    //removing leading 'a'
            
            if(a_class == 'like'){
                window.location.href = href;
            }
            if(a_class =='notfollow_question'){
                link.removeClass('notfollow_question');
                $.getJSON("/include/_answer_controller.php", {
                    //Passing the variables to the controller
                    question_id: q_id, 
                    follow  : 'on' 
                }, 

                //Using 'callback' functionality, because we should wait the results from the controller
                function(response){
                    link.addClass('follow_question');
                    link.removeClass('loading');
                    var temp = link.next().find("span.counter_number").html();
                    link.next().find("span.counter_number").html(temp * 1 + 1);
                }
                );
                link.addClass('loading');
                return false;
             }
            if(a_class =='follow_question'){
                 link.removeClass('follow_question');
                 $.getJSON("/include/_answer_controller.php", {
                    //Passing the variables to the controller
                    question_id: q_id, 
                    follow  : 'off' 
                }, 

                //Using 'callback' functionality, because we should wait the results from the controller
                function(response){
                    link.addClass('notfollow_question');
                    link.removeClass('loading');
                    var temp = link.next().find("span.counter_number").html();
                    link.next().find("span.counter_number").html(temp * 1 - 1);
                }
                );    
                link.addClass('loading');
                return false;
             }
            if(a_class =='thumb_down'){
                link.removeClass('thumb_down');
                $.getJSON("/include/_answer_controller.php", {
                    //Passing the variables to the controller
                    answer_id: a_id, 
                    thumb  : 'on' 
                }, 

                //Using 'callback' functionality, because we should wait the results from the controller
                function(response){
                    link.addClass('thumb_up');
                    link.removeClass('loading');
                    var temp = link.next().find("span.counter_number").html();
                    link.next().find("span.counter_number").html(temp * 1 + 1);
                }
                );
                link.addClass('loading');
                return false;
             }
             if(a_class =='thumb_up'){
                link.removeClass('thumb_up');
                $.getJSON("/include/_answer_controller.php", {
                    //Passing the variables to the controller
                    answer_id: a_id, 
                    thumb  : 'off' 
                }, 

                //Using 'callback' functionality, because we should wait the results from the controller
                function(response){
                    link.addClass('thumb_down');
                    link.removeClass('loading');
                    var temp = link.next().find("span.counter_number").html();
                    link.next().find("span.counter_number").html(temp * 1 - 1);
                }
                );
                link.addClass('loading');
                return false;
             }
        }
    }
})

