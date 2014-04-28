 tinyMCE.init({
    theme : "advanced",
    theme_advanced_layout_manager : "SimpleLayout",
    mode : "none",        //WE'LL DO IT MANUALLY
    
    width : "64",
    content_css : "/stylesheets/style.css",
    plugins : 'paste',
    
    theme_advanced_buttons1 : "bold, italic, bullist,numlist,image, code",
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
    forced_root_block : false,
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

//Turning the textarea of a question is being edited into EDITABLE TINY MCE EDITOR
$(document).ready(function(){
    tinyMCE.execCommand('mceAddControl',false,"question_body");
    var answers_count = document.getElementById('answers_count').value;
    var i;    
    for(i = 1; i <= answers_count; i++){
        tinyMCE.execCommand('mceAddControl',false,"answer_body_" + i);    
    }
})


$(document).ready(function(){
    $(".delete_testquestion").live("click", function() {
        var row = $(this);
        var id = $(this).attr("id");
        var answer=confirm("Вы действительно хотите удалить этот вопрос?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_testquestions.php", { 'delete_testquestion': id}, function() {
                row.parent().parent().fadeOut('slow');
            } );    
        }
        return false;
    })
});

$(document).ready(function(){
    $(".delete_testanswer").live("click", function() {
        var id = $(this).attr("id");
        var question_id = $("input[name=question_id]").attr("value");
        var answer=confirm("Вы действительно хотите удалить этот вариант?");
        if(answer){
            //$.getJSON("/admin/_universities.php", {
            $.post("/admin/_testquestions.php", { 'delete_testanswer': id}, function() {
                location.href='/admin/page/testquestions/' + question_id +'/';
            } );    
        }
    })
});

$(document).ready(function(){
    $("input[name=add_answer]").click(function(){
        var total_answers = $('input[name="answers_count"]').val();
            total_answers++;
            $('input[name="answers_count"]').val(total_answers);
            
        var question_id = $('input[name="question_id"]').val();
        var test_id = $('#test_id').val();
        //Let's save newly added answer
        //Then put an ID of the answer on the page
        $.getJSON("/admin/_testquestions.php", {'add_question': question_id, 'test_id':test_id, 'ajax' : true}, function(data){
            
        var buffer = $("#buffer").clone();
            buffer.find('span[class="option_number"]').text(total_answers);
            buffer.find('input[name="answer_id"]').val(data.answer_id);
            buffer.find('input[name="answer_id"]').attr("name", "answer_id_" + total_answers);
            
            
            buffer.find('textarea[name="answer_body"]').attr("name", "answer_body_" + total_answers);
            buffer.find('textarea[id="answer_body"]').attr("id", "answer_body_" + total_answers);
            


            
            buffer.find('input[name="check"]').attr("name", "check_" + total_answers);
            buffer.find('input[name="remove_answer"]').attr("name", "remove_answer_" + total_answers);
            buffer.find('input[name="remove_answer"]').attr("id", data.answer_id);
            buffer.removeAttr("id"); //Removing BUFFER attribute from the div
            buffer.hide().appendTo($("#answers"));
            
            buffer.fadeIn("fast");
            

            tinyMCE.execCommand('mceAddControl',false,"answer_body_" + total_answers);

            
            if (data.question_id != null)
            $('input[name="question_id"]').val(data.question_id); //IF This is newly added question, then we should give it an ID
        });
    })
})



$(document).ready(function(){
    $("select[id=test_id]").change(function(){
        var test_id =  $(this).val();
        $.get("/admin/_testquestions.php", {'filter_test' : test_id, 'ajax': true}, function(data){
        
        var holder = $("#filter_results").html(data);        
        })
        
    })    
})




