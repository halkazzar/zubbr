$(document).ready(function(){
    $("select[id=tag_group]").change(function(){
        var group_id =  $(this).val();
        $.get("/admin/_tags.php", {'filter_group' : group_id, 'ajax': true}, function(data){

            var holder = $("#filter_results").html(data);        
        })

    });    
});
function split( val ) {
        return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }
$(document).ready(function(){
    $("#tag_from").bind( "keydown", function( event ) {
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

    $("#tag_to").bind( "keydown", function( event ) {
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
    $(".delete_tag").live("click", function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот тег?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_tags.php", { 'delete_tag': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});


    
    
    