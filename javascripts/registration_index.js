$(document).ready(function(){
    $("#reg_button_place").html('<button type="submit" class="center" name="register_user" id="register_user">Регистрация</button>');
    
});

$(document).ready(function() {
    $("#addMore").click(function(){
        var addmore = $(".additional_part_main").clone();
        addmore.append('<div class="clear"><p></p></div>');

        //Incrementing overall count of universities
        var max = $("#addcount").val();
        max = max*1 + 1;
        $("#addcount").val(max);
        addmore.find("input[name=reg_role_display]").attr('name', 'reg_role_display' + max)
        addmore.find("input[name=reg_role_value]").attr('name', 'reg_role_value' + max)

        addmore.find("input[name=reg_university]").attr('name', 'reg_university' + max)

        addmore.find(".reg_role").removeClass("first");
        $('#roles ul li#abitur').remove();

        //Clearing all input values

        addmore.find(".reg_role").val("");
        addmore.find(".reg_university").val("");
        addmore.hide().appendTo("#container");


        addmore.fadeIn("fast");

        addmore.removeClass("additional_part_main");
        addmore.find(".reg_role").removeClass("first");

        return false;
    })
});

//Validation part

$(document).ready(function() {
    var email_free = true;
    var login_free = true;
    var errors = new Array(1, 1, 1, 1);   //error codes for each validation
    var errors_fb = 0;   //error codes for each validation

    $(".reg_login").live('change', function(){
        errors[0]=0;
        var warning = $(this).prev().find('.loginWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /^[0-9A-Za-z]+$/gi;

        $.getJSON("/users/registration/index_controller.php", {
            //Passing the variables to the controller
            login: $(this).val(),
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        function(data){
            if (data.login_status == true){
                if(value.length < 3){
                    warning.css("color","red");
                    warning.html("Минимум 3 символа");
                    right.hide();
                    wrong.show();
                    errors[0]=1;    
                }
                else{
                    if(pattern.test(value)){
                        warning.css("color","green");
                        warning.html("");
                        wrong.hide();
                        right.show();
                        errors[0]=0; 
                    }
                    else{
                        warning.css("color","red");
                        warning.html("Логин указан неверно");
                        right.hide();
                        wrong.show();
                        errors[0]=1;
                    }    
                }

            }
            else{
                warning.css("color","red");
                warning.html("Логин уже занят");
                right.hide();
                wrong.show();
                errors[0]=1;
            }
        }
        );
    });

    $(".fb_login").live('change', function(){
        var warning = $(this).prev().find('.loginWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /^[0-9A-Za-z]+$/gi;
        var load_finish = false;
        $.getJSON("/users/registration/index_controller.php", {
            //Passing the variables to the controller
            login: $(this).val(),
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        function(data){
            if (data.login_status == true){
                if(value.length < 3){
                    warning.css("color","red");
                    warning.html("Минимум 3 символа");
                    right.hide();
                    wrong.show();
                    errors_fb =1;    
                }
                else{
                    if(pattern.test(value)){
                        warning.css("color","green");
                        warning.html("");
                        wrong.hide();
                        right.show();
                        errors_fb =0; 
                    }
                    else{
                        warning.css("color","red");
                        warning.html("Логин указан неверно");
                        right.hide();
                        wrong.show();
                        errors_fb =1;
                    }    
                }

            }
            else{
                warning.css("color","red");
                warning.html("Логин уже занят");
                right.hide();
                wrong.show();
                errors_fb =1;
            }
            
            load_finish = true;
        }
        );
    });
        
    $(".reg_email").live('change', function(){
        errors[1]=0;
        
        var warning = $(this).prev().find('.emailWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

        $.getJSON("/users/registration/index_controller.php", {
            //Passing the variables to the controller
            email: $(this).val(),
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        function(data){
            if (data.email_status == true){
                if(pattern.test(value)){
                    warning.css("color","green");
                    warning.html("");
                    wrong.hide();
                    right.show();
                    errors[1]=0;
                }
                else{
                    warning.css("color","red");
                    warning.html("Адрес указан не верно");
                    right.hide();
                    wrong.show();
                    errors[1]=1; 
                }
            }
            else{
                warning.css("color","red");
                warning.html("Email адрес уже занят");
                right.hide();
                wrong.show();
                errors[1]=1; 
            }
        }
        );
    }); 

    $(".reg_password").live('change', function(){
        errors[2]=0;
        var warning = $(this).prev().find('.passwordWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /(.){6,}/;

        //Using 'callback' functionality, because we should wait the results from the controller
        if(pattern.test(value)){
            warning.css("color","green");
            warning.html("");
            wrong.hide();
            right.show();
            errors[2]=0;
        }
        else{
            warning.css("color","red");
            warning.html("Минимум 6 символов");
            right.hide();
            wrong.show();
            errors[2]=1;
        }



    }); 

    $(".reg_antibot").live('change', function(){
        errors[3]=0;
        var warning = $(this).prev().find('.antibotWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var sum = $(this).prev().find("span.one").text()*1 +  $(this).prev().find("span.two").text()*1;

        //Using 'callback' functionality, because we should wait the results from the controller
        if(value == sum){
            warning.css("color","green");
            warning.html("");
            wrong.hide();
            right.show();
            errors[3]=0;
        }
        else{
            warning.css("color","red");
            warning.html("Неверное решение");
            right.hide();
            wrong.show();
            errors[3]=1;
        }
    });

    
    
    //Facebook
    $("#fb_login").live('click', function(){
        $("input[class^=fb_]").trigger('change');
        if (errors_fb == 0){
            //Do Something;
            //alert(errors_fb);
            return true;
        }
        else{
            alert('Проверьте правильность логина');
        }
        return false;
    });
    
    //Count errors
    $("#register_user").live('click', function(){
        var i;
        var sum = 0;
        
         
        //trigger validation, in case, when user just presses button when the data is already loaded
        $("input[name^=reg_]").trigger('change');
        
        
        for (i = 0; i < 4; i++){
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
    if($(".reg_login").val() != ''){
        $(".reg_login").trigger('change');
    }
    if($(".reg_email").val() != ''){
        $(".reg_email").trigger('change');
    }
    
    if($(".reg_antibot").val() != ''){
        $(".reg_antibot").trigger('change');
    }
    
    if($(".fb_login").val() != ''){
        $(".fb_login").trigger('change');
    } 
})


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






