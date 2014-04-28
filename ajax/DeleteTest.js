
function DeleteTest(myVar)
{
  var xmlHttp = getXMLHttp();
  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      HandleResponse_delete(xmlHttp.responseText, myVar);
    }
  }

  xmlHttp.open("GET", "/ajax/delete_test.php?test="+myVar, true); 
  xmlHttp.send(null);
}

function HandleResponse_delete(response, myVar)
{
  document.getElementById('test'+myVar).innerHTML = response;
}