$(document).ready(function(){
    
    $("#pause_timer").toggle(function() {
        $(this).addClass('play');
        $(this).removeClass('pause');
        $("#status").val(0);
    }, function() {
        $(this).addClass('pause');
        $(this).removeClass('play');
        $("#status").val(1);
        update_timer();
        
    });

})

function GoTest(test_id, question_id, answer_id, direction, questions)
{
  $("#test_timer").fadeIn('slow');
  //UPDATE TEST
  var xmlHttp = getXMLHttp();
  var seconds = 0;
  var timer;
  
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
		HandleResponse_GoTest(xmlHttp.responseText,test_id, question_id,direction, questions);
    }
    else{
        document.getElementById('go_test').innerHTML = '<img src="/images/ajax-loader.gif" class="center" style="margin-top: 50px">';
        pause_timer();
        
    }
  }

  xmlHttp.open("GET", "/ajax/GoTest/?test_id="+test_id+"&question_id="+question_id+"&answer_id="+answer_id+"&direction="+direction, true); 
  xmlHttp.send(null);
}

function pause_timer(){
    clearTimeout(timer);
}

function resume_timer(){
    update_timer();
}

function update_timer(){
    if(document.getElementById('status').value == 1){
        seconds = document.getElementById('time').value;
        seconds= seconds * 1 + 1;
        document.getElementById('time').value = seconds;
        format_timer(seconds);
        timer = setTimeout("update_timer()",1000);
    }
}

function format_timer(seconds){
    var sec = seconds % 60;
    var hour = Math.floor(seconds / 3600);
    seconds = seconds - hour * 3600; 
    var min = Math.floor(seconds / 60);
    
        if(min < 10){
            min = '0'+min;
        }
        if(sec < 10){
            sec = '0'+sec;
        }
    document.getElementById('test_timer_clock').innerHTML = hour + ':' + min +':' + sec;
}

function HandleResponse_GoTest(response,test_id,question_id,direction,questions)
{
 document.getElementById('go_test').innerHTML = response; 
 resume_timer();
 //Resume Timer
  
  //Get data for Paginator3000
  if (typeof(direction)=="string"){
	if (direction=="next") {
        dir=1
    }
	else if (direction=="prev") {
        dir = -1
    }
    else if (direction=="finish"){
        pause_timer();
        $("#pause_timer").hide();
        
    }	
	
    current_page = 1;
	for (var i = 1; i <= questions.length; i++)
		{
			if (questions[i-1] == question_id) current_page=i+dir;			
		}
	}
else current_page = direction;

myURL = test_id + "," + questions[current_page-1];
		 //Update question
		
		paginator_example = new Paginator(
		"paginator_example", // id контейнера, куда ляжет пагинатор
		questions.length, // общее число страниц
		10, // число страниц, видимых одновременно
		current_page, // номер текущей страницы
		"",
		myURL // url страниц
		);
}

function CheckGoTest(test_id, question_id, direction, questions)
{

var cnt = -1;
len = document.forms['select_answer'].elements.length;

    for (var i = len; i > 0; i--) {
		
		if (document.forms['select_answer'].question[i-1].checked)
		{
			cnt = i; j = i-1; i = -1;
		}
    }

    if (cnt > -1) answer_id = document.forms['select_answer'].elements[j].value;
    else answer_id = 0;	
	
	GoTest(test_id, question_id, answer_id, direction,questions);
}

