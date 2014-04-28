$(document).ready(function(){
     $(".meta").hide();
     var questions = $("div[id^=question-]");
     questions.live('mouseenter',  function(){$(this).find("div.meta").fadeIn(0); $(this).find("div.meta-hidden").fadeOut(0); })
     questions.live('mouseleave', function(){$(this).find("div.meta").fadeOut(0); $(this).find("div.meta-hidden").fadeIn(0); })
    
});



