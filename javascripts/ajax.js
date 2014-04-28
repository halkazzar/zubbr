var req = Create(); 

function Create(){  
if(navigator.appName == "Microsoft Internet Explorer"){ 
req = new ActiveXObject("Microsoft.XMLHTTP");  
} else {  
req = new XMLHttpRequest();  
}  
return req;  
}  

function Request(query,arg) 
{ 
req.open('post', 'reg_ajax.php' , true);
if (arg == 'nickname') req.onreadystatechange = RefreshUser;
if (arg == 'email') req.onreadystatechange = RefreshEmail;
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
req.send(query);  
}  

function RefreshUser()
{ 
if( req.readyState == 4 ) 
document.getElementById('nickname_result').innerHTML = req.responseText; 

} 

function RefreshEmail() 
{ 
if( req.readyState == 4 ) 
document.getElementById('email_result').innerHTML = req.responseText; 

} 

function Start(arg) 
{  
var query;  

query =arg+'='+encodeURIComponent(document.getElementById(arg).value); 
Request(query,arg); 
} 