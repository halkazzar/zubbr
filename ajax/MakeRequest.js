
function MakeRequest(myVar)
{
  var xmlHttp = getXMLHttp();
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse(xmlHttp.responseText);
    }
  }

  xmlHttp.open("GET", "ajax.php?type="+myVar, true); 
  xmlHttp.send(null);
}

function HandleResponse_delete(response, myVar)
{
  document.getElementById('test'+myVar).innerHTML = response;
}