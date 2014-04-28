$(document).ready(function(){
    $(".delete_test").click(function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот тест?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_tests.php", { 'delete': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
    
    
    
        $("form[name='tests'] #tag_names").bind( "keydown", function( event ) {
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