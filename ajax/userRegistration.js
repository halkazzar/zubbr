var xmlhttp;



function registerUser(login, pass, email, antibot, location_id)
{

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Your browser does not support XMLHTTP!");
  return;
  }
  

var url="/ajax/userRegistration.php";
url=url+"?login="+login;
url=url+"&pass="+pass;
url=url+"&email="+email;
url=url+"&antibot="+antibot;
url=url+"&location_id="+location_id;
xmlhttp.onreadystatechange=stateChanged1;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);

}

function stateChanged1()
{
if (xmlhttp.readyState==4)
  {
  
  document.getElementById("registrationResult").innerHTML=xmlhttp.responseText;
  document.getElementById("reg_login").value = "";
  document.getElementById("reg_pass1").value = "";
 // document.getElementById("reg_pass2").value = "";  
  document.getElementById("reg_email").value = "";
  document.getElementById("loginWarning").style.visibility = "hidden";
  document.getElementById("emailWarning").style.visibility = "hidden";
  document.getElementById("passWarning").style.visibility = "hidden";
  document.getElementById("loginCorrectDiv").style.display = "none";
  document.getElementById("emailCorrectDiv").style.display = "none";
  document.getElementById("passwordCorrectDiv1").style.display = "none";
  //document.getElementById("passwordCorrectDiv2").style.display = "none";  
  document.getElementById("loginWrongDiv").style.display = "none";
  document.getElementById("emailWrongDiv").style.display = "none";
  document.getElementById("passwordWrongDiv1").style.display = "none";
  //document.getElementById("passwordWrongDiv2").style.display = "none";

  }
  
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}