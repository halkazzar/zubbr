/**
* Zubr
* Redirecting newly asked question to the controller and displaying the text
* in questions div
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/  

$(document).ready(function() {
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        $( "#tagsText" )
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

$(document).ready(function(){
    
    $("#questionText").focus(function(){
        $(this).css("height", "57px");
        $(".add").show();
    });

    $("#submitQuestion").click(function () {
        if ($("#questionText").val() != ''){
            $.getJSON("/include/_ask_controller.php", {

                //Passing the variables to the controller
                action: 'save',
                ajax: true,
                question_text: $("#questionText").val(),
                object_id_text: $("#object_id").val(), 
                category_text: $("#category").val(),
                tags: $("#tagsText").val()
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
        var numberOfQuestions = $("div[id^=question-]").length;
        numberOfQuestions++;

        var question = $("#buffer").clone().prependTo("#questions");
        if (data.logged_id != ""){
            question.find("._question_body").text(data.question_body);
            question.find("._published_date").text(data.published_date);
            question.find("._login").text(data.login);
            question.attr('id', 'question-'+numberOfQuestions);
            question.find("._avatar").attr('href', '/users/'+data.login+'/');
            question.find("._question_id").attr('href', '/questions/'+data.question_id+'/');
            $("#questionText").val('');
            question.fadeIn("normal");
            
            //Lets return fields into normal state
            $("#questionText").css("height", "19px");
            
            $(".add").hide();
            
            //Deleting tags
            $("input[name = 'tags']").val();
             
            //Showing tags
            var i;
            var text="";
            for(i =0; i < data.tags.length; i++){
            text += '<a href="/tag/' + data.tags_id[i] +'/">' + data.tags[i] +'</a> ';
            }
            if(text == ""){
                question.find(".tag").html('Без тегов');    
            }
            else{
                question.find(".tag").html('Теги: ' + text);    
            }
                         
        }
        else {
            alert("Чтобы Вы смогли полностью воспользоваться, пожайлуйста зарегистрируйтесь.");
        }

    }
});
