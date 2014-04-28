/**
* Zubr
* Redirecting answer to the question to the controller and displaying the text
* in questions div
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/  

$(document).ready(function(){
    $("#submitAnswer").click(function () {
        if ($("#answerText").val() != ''){
            $.getJSON("/include/_answer_controller.php", {

                //Passing the variables to the controller
                
                answer_text: $("#answerText").val(),
                question_id: $("#question_id").val() 
                //subscribe  : $("#subscribe:checked").val() 
            }, 

            //Using 'callback' functionality, because we should wait the results from the controller
            redrawPage
            );

            //Returning false to deactivate the link, user has pressed
            //return false;
        }
        return false;
    })

    function redrawPage(data){
        
        //Finding out how many questions already exitsts on the page
        var numberOfAnswers = $("div[id^=answer-]").length;
        numberOfAnswers++;

        var answer = $("#bufferAnswer").clone().prependTo("#answers");
        if (data.logged_id != ""){
            answer.find("._answer_body").text(data.answer_body);
            answer.find("._published_date").text(data.published_date);
            answer.find("._login").text(data.login);
            answer.attr('id', 'answer-'+numberOfAnswers);
            answer.find("._avatar").attr('href', '/users/'+data.login+'/');
              
            $("#answerText").val('');
            
            $("#answerButton").fadeIn("normal");
            $("#answerForm").hide();
            answer.fadeIn("normal");          
        }
        else {
            alert("Зайди чтобы ответить.");
        }

    }
});
$(document).ready(function() {
    $("#openAnswerForm").click(function(){
        $("#answerButton").hide();
        $("#answerForm").fadeIn("normal");
        return false;     
    })
});

$(document).ready(function(){
    $("#cancelAnswer").click(function(){
        $("#answerButton").fadeIn("normal");
        $("#answerForm").hide(); 
        return false;
    })    
})

$(document).ready(function(){
     $(".meta").hide();
     var answers = $("div[id^=answer-]");
     answers.live('mouseenter',  function(){$(this).find("div.meta").fadeIn(0); $(this).find("div.meta-hidden").fadeOut(0); })
     answers.live('mouseleave', function(){$(this).find("div.meta").fadeOut(0); $(this).find("div.meta-hidden").fadeIn(0); })
    
});