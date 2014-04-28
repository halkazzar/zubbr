$(document).ready(function() {
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        $( "#tags" )
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( "/include/_ask_controller.php", {
                        tag: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 2 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
    });

$(document).ready(function() {

    var tab_counter = $("#total").val()*1 + 1;


    // tabs init with a custom tab template and an "add" callback filling in the content
    var $tabs = $("#tabs").tabs({
        tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Удалить запись</span></li>",

        add: function(event, ui) {
            var tab_content = $("#buffer").html(); //Buffer contains default template to be added
            $(ui.panel).append(tab_content);
            $(ui.panel).find("input[name=relation]").attr("name", "relation" + tab_counter);
            $(ui.panel).find("input[name=university]").attr("name", "university" + tab_counter);
            $(ui.panel).find("select[name=monthstart]").attr("name", "monthstart" + tab_counter);
            $(ui.panel).find("select[name=yearstart]").attr("name", "yearstart" + tab_counter);
            $(ui.panel).find("select[name=monthfinish]").attr("name", "monthfinish" + tab_counter);
            $(ui.panel).find("select[name=yearfinish]").attr("name", "yearfinish" + tab_counter);
            $(ui.panel).find("select[name=degree]").attr("name", "degree" + tab_counter);
            $(ui.panel).find("input[name=studyarea]").attr("name", "studyarea" + tab_counter);
            $(ui.panel).find("input[name=scholarship]").attr("name", "scholarship" + tab_counter);
            
            $("#total").val(tab_counter);
        }
    });

    // actual addTab function: adds new tab using the title input from the form above
    function addTab() {
        var tab_title = "Добавление ВУЗа";
        $tabs.tabs( "add", "#tabs-" + tab_counter, tab_title );
        tab_counter++;
    }

    // addTab button: just opens the dialog
    $( "#add_tab" ).click(function() {
        addTab();
        return false;
    });

    // close icon: removing the tab on click
    // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
    $( "#tabs span.ui-icon-close" ).live( "click", function() {
        var answer = confirm("Вы действительно хотите удалить запись?");
        if (answer){
            var index = $( "li", $tabs ).index( $( this ).parent() );

            var href = $(this).prev().attr("href");
            var x =$(href).find('input').val();
            $tabs.tabs( "remove", index );

            $("#remove").append('<input type="hidden" name="remove[]" value="'+ x +'"/>');
        }
    });



    //$("#tags").fcbkcomplete({
//        json_url: "/include/_ask_controller.php",
//        height: 10,
//        newel: true,
//        delay: 0.001,
//        firstselected: true,
//        complete_text: 'Разделайте теги запятой',
//        maxitimes: 10

//    });
    
    
    //var email_free = true;
    //var login_free = true;
    //var errors = new Array(0, 0);   //error codes for each validation
    var email_error = 0;
    var password_error = 0;
    var value;

    $(".email").live('change', function(){
        var warning = $(this).prev().find('.emailWarning');
        var right = $(this).next();
        var wrong = right.next();
        var value = $(this).val();
        var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

        $.getJSON("/users/settings/", {
            //Passing the variables to the controller
            email: $(this).val(),
            ajax: true
        }, 

        //Using 'callback' functionality, because we should wait the results from the controller
        function(data){
            if (data.email_status == true){
                if(pattern.test(value)){
                    warning.css("color","green");
                    warning.html("");
                    wrong.css("display", "none");
                    right.css("display", "inline");
                    email_error=0;
                }
                else{
                    warning.css("color","red");
                    warning.html("Адрес указан не верно");
                    wrong.css("display", "inline");
                    right.css("display", "none");
                    email_error=1; 
                }
            }
            else{
                warning.css("color","red");
                warning.html("Email адрес уже занят");
                wrong.css("display", "inline");
                right.css("display", "none");
                email_error=1; 
            }
        }
        );
    }); 



    $(".password").live('change', function(){
        var warning = $(this).prev().find('.passwordWarning');
        var right = $(this).next();
        var wrong = right.next();
            value = $(this).val();
        var pattern = /(.){6,}/;

        //Using 'callback' functionality, because we should wait the results from the controller
        if (value == ""){
            password_error=0;
            warning.css("color","green");
            warning.html("");
            wrong.css("display", "none");
            right.css("display", "none");
            password_error=0;    
        }
        else{
            if(pattern.test(value)){
                warning.css("color","green");
                warning.html("");
                wrong.css("display", "none");
                right.css("display", "inline");
                password_error=0;
            }
            else{
                warning.css("color","red");
                warning.html("Минимум 6 символов");
                wrong.css("display", "inline");
                right.css("display", "none");
                password_error=1;
            }
        }



    }); 

    $(".password2").live('change', function(){
        var warning = $(this).prev().find('.password2Warning');
        var right = $(this).next();
        var wrong = right.next();
        var value2 = $(this).val();
        
        //Using 'callback' functionality, because we should wait the results from the controller
        if(value2 == value){
            warning.css("color","green");
            warning.html("");
            wrong.css("display", "none");
            right.css("display", "inline");
            password_error=0;    
        }
        else{
            warning.css("color","red");
            warning.html("Пароли не совпадают");
            wrong.css("display", "inline");
            right.css("display", "none");
            password_error=1;
        }



    });
    //Count errors
    $("button[name=basic_info_save]").live('click', function(){
        //trigger validation, in case, when user just presses button when the data is already loaded
        $("input[name=email]").trigger('change');
        if (email_error == 0){
            //Do Something;
            return true;
        }
        else{
            alert('Адрес электронной почты неверный');
        }
        return false;
    });

    $("button[name=change_password_save]").live('click', function(){
        //trigger validation, in case, when user just presses button when the data is already loaded
        $("input[name=password]").trigger('change');
        $("input[name=password2]").trigger('change');
        if (password_error == 0){
            //Do Something;
            return true;
        }
        else{
            alert('Пароль не соответствует минимальным требованиям');
        }
        return false;
    });

    //JSON Suggesting for University and Studyarea
    $('.university').live('focus', function(){
        $.getJSON("/include/_profile_controller.php", {invoker: 'registration_uni'},
        function(data){
            $('.university').jsonSuggest(data, {maxResults:20})
        }
        );        
    });

    $('.spec').live('focus', function(){
        $.getJSON("/include/_profile_controller.php", {call : 'st'},
        function(data){
            $('.spec').jsonSuggest(data, {maxResults:20})
        }
        );        
    });
    $('.scholarship').live('focus', function(){
        $.getJSON("/include/_profile_controller.php", {call : 's'},
        function(data){
            $('.scholarship').jsonSuggest(data, {maxResults:20})
        }
        );        
    });
    
    $('.location').live('focus', function(){
        $.getJSON("/include/_profile_controller.php", {call : 'l'},
        function(data){
            $('.location').jsonSuggest(data, {maxResults:20})
        }
        );        
    });
    
    
    $("#confirm_email").click(function(){
        $.getJSON("/users/settings/", {
            confirm : '1',
            ajax : true
        },
        function(data){
            if(data.email_sent == true){
            $("#confirm_email").text("Ключ выслан");    
            }
        })
        return false;    
    });
});

$(document).ready(function(){
    $(".delete_relation").live('click', function() {
        var object = $(this);
        var relationID = $(this).attr("id");
        var text = $(this).next().text();
        var answer=confirm("Вы действительно больше не хотите учиться в " + text +" ?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/pages/users/settings.php", { 'delete_relation': relationID}, function(data) {
                object.next().next().fadeOut('fast');
                object.next().fadeOut('fast');
                object.fadeOut('fast');
            } );    
        }
        return false;
    })
});

$(document).ready(function(){
    $(".delete_sub").live('click', function() {
        var object = $(this);
        var subID = $(this).attr("id");
        var text = $(this).next().text();
        var answer=confirm("Вы действительно больше не хотите получать уведомление про то, " + text +" ?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/pages/users/settings.php", { 'delete_sub': subID}, function(data) {
                object.next().next().fadeOut('fast');
                object.next().fadeOut('fast');
                object.fadeOut('fast');
            } );    
        }
        return false;
    })
});