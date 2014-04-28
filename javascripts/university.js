$(document).ready(function(){
    $(".content").hide();
    $(".toggle").click(function () {
        $(".content").toggle(500, 'easeInOutExpo');
        
        var exp = $('._expander');
        if(exp.hasClass('expander')){
            exp.removeClass('expander');
            exp.addClass('expander_open');
            exp.text('Свернуть');    
        }
        else{
            exp.removeClass('expander_open');
            exp.addClass('expander');
            
            exp.text('Развернуть');
        }
        return false;
    }
    );
     
    

});
$(document).ready(function(){
$(".imagetoolsshade").show();   
});