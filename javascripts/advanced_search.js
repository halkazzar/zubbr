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

$(document).ready(function(){
    $('#questionText').live('focus', suggest);
    function suggest(){
        $.getJSON("/include/_profile_controller.php", {call : 'u'},
        function(data){
            $('#questionText').jsonSuggest(data, {maxResults:20});
        }
        );    
    }    
})