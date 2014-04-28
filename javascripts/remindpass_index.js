//Validation part

$(document).ready(function() {
    var email_free = true;
    var login_free = true;
    var errors = new Array(1);   //error codes for each validation

    $(".reg_password").live('change', function(){
        var warning = $(this).prev().find('.passwordWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /^([a-zA-Z0-9_.-]){6,}/;

        //Using 'callback' functionality, because we should wait the results from the controller
        if(pattern.test(value)){
            warning.css("color","green");
            warning.html("");
            wrong.hide();
            right.show();
            errors[0]=0;
        }
        else{
            warning.css("color","red");
            warning.html("Минимум 6 символов");
            right.hide();
            wrong.show();
            errors[0]=1;
        }
    }); 

    //Count errors
    $("button.center").live('click', function(){
        //trigger validation, in case, when user just presses button when the data is already loaded
        $("input[name^=reg_]").trigger('change');

        var i;
        var sum = 0;
        for (i = 0; i < 1; i++){
            sum = sum + errors[i];    
        }

        if (sum == 0){
            //Do Something;
            return true;
        }
        else{
            alert('Вы ввели неверные данные');
        }
        return false;
    });
}); 

//JSON request for universities
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






