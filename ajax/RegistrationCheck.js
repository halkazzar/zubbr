
function RegistrationCheck(login, pass1, email, antibot)
{



/*
if (xmlhttp==null)
  {
  alert ("Your browser does not support XMLHTTP!");
  return;
  }  
*/
var url="/ajax/RegistrationCheck/";
url=url+"?login="+encodeURIComponent(login);
url=url+"&pass1="+encodeURIComponent(pass1);
//url=url+"&pass2="+pass2;
url=url+"&email="+encodeURIComponent(email);

var xmlhttp = getXMLHttp();
xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.readyState == 4)
    {
		stateChanged(xmlhttp.responseText);
    }
  }
xmlhttp.open("GET",url,true);
xmlhttp.send(null);

}


function stateChanged(response)
{

//depending on value returned we'll switch the visibility of images

  
  		

	var str = "";

	
	var i;
	
	 var strArray=response.split("\n");

     
         /*
            The following line switches displaying the result of RegistrationCheck.js
            and used only for debugging
         */
    // document.getElementById("Header").innerHTML = strArray;
     
	for(i=0;i<strArray.length;i++){
	   	str=str+strArray[i];
	}
	  

      
	//  document.getElementById("registrationResult").innerHTML = response;
	  
  	var reg_login = document.getElementById("reg_login").value;
	var reg_pass1 = document.getElementById("reg_pass1").value;
	//var reg_pass2 = document.getElementById("reg_pass2").value;
	var reg_email = document.getElementById("reg_email").value;
	var reg_antibot = document.getElementById("antibot").value;
	  
	  //due to yet unknown bug, the first letter starts from '1' not '0'
	  if (str.charAt(0)=="0")
	  	{
		document.getElementById("loginWarning").style.color="green";
		document.getElementById("loginWarning").innerHTML="Ваше имя пользователя";
		document.getElementById("loginCorrectDiv").style.display = "";
		document.getElementById("loginWrongDiv").style.display = "none";
		}
			else
		{
	//	document.getElementById("loginWarning").style.visibility = "hidden";	
		if (reg_login!="" && reg_login!="UNDEFINED")
		{
			document.getElementById("loginWrongDiv").style.display = "";	
			document.getElementById("loginWarning").style.color="red";
			document.getElementById("loginWarning").innerHTML="Имя пользователя уже занято";
		}
			else
			document.getElementById("loginWrongDiv").style.display = "none";
			document.getElementById("loginCorrectDiv").style.display = "none";
		}
		
	  if (str.charAt(1)=="0") 	  	
	  	{			
		
			if (validateEmail(document.getElementById("reg_email").value)==0)
			{
			document.getElementById("emailWarning").style.color="green";
			document.getElementById("emailWarning").innerHTML="Ваша электронная почта";	
			document.getElementById("emailCorrectDiv").style.display = "";
			document.getElementById("emailWrongDiv").style.display = "none";
			}
			else
			{
			document.getElementById("emailCorrectDiv").style.display = "none";
			document.getElementById("emailWrongDiv").style.display = "";
			document.getElementById("emailWarning").style.color="red";
			document.getElementById("emailWarning").innerHTML="Email задан не верно";
			}
			
		}
			else			
		{	
			document.getElementById("emailCorrectDiv").style.display = "none";
			if ( reg_email!="" && reg_email!="UNDEFINED")
				{
				document.getElementById("emailWrongDiv").style.display = "";
				document.getElementById("emailWarning").style.color="red";
				document.getElementById("emailWarning").innerHTML="Email уже используется";
				}
			else
				document.getElementById("emailWrongDiv").style.display = "none";

		}
		
		
		
		
	/*
	  if (str.charAt(2)=="0")	  	
	  	{
		document.getElementById("passWarning").style.visibility = "hidden";	
		document.getElementById("passwordCorrectDiv1").style.display = "inline";
		document.getElementById("passwordCorrectDiv2").style.display = "inline";
		document.getElementById("passwordWrongDiv1").style.display = "none";
		document.getElementById("passwordWrongDiv2").style.display = "none";
		
		}
			else
		{	
		document.getElementById("passWarning").style.visibility = "hidden";	
		document.getElementById("passwordCorrectDiv1").style.display = "none";
		document.getElementById("passwordCorrectDiv2").style.display = "none";
		if (reg_pass1!="" && reg_pass1!="UNDEFINED")
			document.getElementById("passwordWrongDiv1").style.display = "inline";
			else
			document.getElementById("passwordWrongDiv1").style.display = "none";
		if (reg_pass2!="" && reg_pass2!="UNDEFINED")	
			document.getElementById("passwordWrongDiv2").style.display = "inline";		
			else
			document.getElementById("passwordWrongDiv2").style.display = "none";		
		}
*/
  }



