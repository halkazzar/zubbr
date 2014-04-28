/**
* Zubr
* Managing question's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

$(document).ready(function(){
    $(".delete_question").click(function() {
        var row = $(this);
        var ID = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот вопрос?");
        if(answer){
            var reason=prompt("Введите причину удаления", "");
            if (reason!=null && reason!=""){
                //$.getJSON("/admin/_universities.php", {
                $.post("/admin/_questions.php", { 'delete': ID, 'reason': reason}, function() {
                    row.parent().parent().fadeOut('slow');
                });
                return false;    
            }
            return false;
               
        }
    })
    
    
    $("form[name='news'] #tag_names").bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
        $( this ).data( "autocomplete" ).menu.active ) {
            event.preventDefault();
        }
    }).autocomplete({
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
    $('#question_category').change(function() {

        $.getJSON("/admin/_questions_controller.php", {
            category: $(this).val()     
        }, 

        //callback
        function(j){
            var options = '';

            for (var i = 0; i < j.length; i++) {
                options += '<option value="' + j[i].id + '">' + j[i].display + '</option>';
            }
            $("#category_object_id").html(options);            
        })
    });
})



