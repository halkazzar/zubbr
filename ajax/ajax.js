function getXMLHttp()
{
  var xmlHttp

    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
    else {
        if (window.ActiveXObject) {
            try {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) { }
        }
    }
    
  return xmlHttp;
}

function HandleResponse(response)
{
  document.getElementById('ResponseDiv').innerHTML = response;
}
