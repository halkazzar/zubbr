
function haha(v)
{
	alert(v);	
}


function testCreateCheck()
{

	var test_title = document.getElementById("test_title").value;

	if (test_title=="" || test_title=="UNDEFINED")
		{
			document.getElementById("titleCorrectDiv").style.display = "none";
			document.getElementById("titleWrongDiv").style.display = "none";
			//document.getElementById("titleWarning").style.display = "block";
			//return;
		}
		
	var test_hr = document.getElementById("test_hr").value;
	var test_min = document.getElementById("test_min").value;
	var test_sec = document.getElementById("test_sec").value;
	
	if (test_hr=="" || test_hr=="UNDEFINED" || test_min=="" || test_min=="UNDEFINED" ||  test_sec=="" || test_sec=="UNDEFINED")
		{
			document.getElementById("timeCorrectDiv").style.display = "none";
			document.getElementById("timeWrongDiv").style.display = "none";
			//return;
		}	
	
	
  var xmlHttp = getXMLHttp();
	

  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
		HandleResponse_testCreate(xmlHttp.responseText);
    }
  }

  xmlHttp.open("GET", "/ajax/TestCreate/?test_title="+test_title+"&test_hr="+test_hr+"&test_min="+test_min+"&test_sec="+test_sec, true); 
  xmlHttp.send(null);	
}


function HandleResponse_testCreate(response)
{

//	document.getElementById("ajaxResult").innerHTML = response;
	
	var flag = 0;
	if (response.charAt(0)=="0")
		{
			document.getElementById("titleCorrectDiv").style.display = "block";
			document.getElementById("titleWrongDiv").style.display = "none";
		}
		else
		{
			
			document.getElementById("titleCorrectDiv").style.display = "none";
			
			if (document.getElementById("test_title").value!="")
				document.getElementById("titleWrongDiv").style.display = "block";
				else
				document.getElementById("titleWrongDiv").style.display = "none";
				
			flag = 1;
		}
		
	if (response.charAt(1)=="0")
		{
			document.getElementById("timeCorrectDiv").style.display = "block";
			document.getElementById("timeWrongDiv").style.display = "none";
		}
		else
		{
			
			document.getElementById("timeCorrectDiv").style.display = "none";
			if ((document.getElementById("test_hr").value=="")||(document.getElementById("test_min").value=="")||(document.getElementById("test_sec")==""))
			document.getElementById("timeWrongDiv").style.display = "none";
				else 
			document.getElementById("timeWrongDiv").style.display = "block";
			flag = 1;
		}
		
		if (flag==1)
		document.getElementById("submit_btn").disabled = "disabled";
		else
		document.getElementById("submit_btn").disabled = "";
}
