/**
* Zubr
* Managing news's deletion in admin panel
* @package Zubr
* @author Dauren Sarsenov 
* @version 1.0, 01.09.2010
* @since engine v.1.0
* @copyright (c) 2010+ by Dauren Sarsenov
*/

 tinyMCE.init({
    theme : "advanced",
    theme_advanced_layout_manager : "SimpleLayout",
    mode : "exact",
    elements : "body, description, avatar",
    width : "64",
    content_css : "/stylesheets/style.css",
    plugins : 'paste',
    
    theme_advanced_buttons1 : "bold, italic, bullist,numlist,image, code, link, unlink",
    theme_advanced_buttons2 : "outdent,indent,paste,undo,redo",
    theme_advanced_buttons3 : "hr,removeformat,visualaid,sub,sup,charmap",
    //valid_elements : "p,img[src|border|alt=|width|height|align|class|id]",
    file_browser_callback : "ajaxfilemanager",
    paste_use_dialog : false,
    paste_auto_cleanup_on_paste : true,
    paste_create_paragraphs: true,
    theme_advanced_resizing : true,
    theme_advanced_resize_horizontal : true,
    apply_source_formatting : false,
    force_br_newlines : false,
    forced_root_block : 'p',
    force_p_newlines : true,
    document_base_url : '/',
    relative_urls : true
});


function ajaxfilemanager(field_name, url, type, win) {
 //var ajaxfilemanagerurl = "/javascripts/tinymce/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
switch (type) {
case "image":
break;
case "media":
break;
case "flash": 
break;
case "file":
break;
default:
return false;
}
tinyMCE.activeEditor.windowManager.open({
url: "/javascripts/tinymce/jscripts/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",

inline : "yes",
close_previous : "no"
},{
window : win,
input : field_name
});
}


$(document).ready(function(){
    $(".delete_article").click(function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить эту статью?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_news.php", { 'delete': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
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
    
    $("form[name='news'] #uni_names").bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
        $( this ).data( "autocomplete" ).menu.active ) {
            event.preventDefault();
        }
    }).autocomplete({
        source: function( request, response ) {
            $.getJSON( "/include/_ask_controller.php", {
                uni: extractLast( request.term )
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

