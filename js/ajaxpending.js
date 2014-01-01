// Customise those settings

var seconds = 5;
var divid = "timediv2";
var url = "func/pending.func.php";

////////////////////////////////
//
// Refreshing the DIV
//
////////////////////////////////

function refreshdiv2(){

// The XMLHttpRequest object

var xmlHttp;
try{
xmlHttp=new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
}
catch (e){
try{
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
}
catch (e){
try{
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e){
alert("Your browser does not support AJAX.");
return false;
}
}
}

// Timestamp for preventing IE caching the GET request

fetch_unix_timestamp = function()
{
return parseInt(new Date().getTime().toString().substring(0, 10))
}

var timestamp = fetch_unix_timestamp();
var nocacheurl = url+"?t="+timestamp;

// The code...

xmlHttp.onreadystatechange=function(){
if(xmlHttp.readyState==4){
document.getElementById(divid).innerHTML=xmlHttp.responseText;
setTimeout('refreshdiv2()',seconds*1000);
}
}
xmlHttp.open("GET",nocacheurl,true);
xmlHttp.send(null);
}

// Start the refreshing process

var seconds;
window.onload = function startrefresh(){
setTimeout('refreshdiv2()',seconds*1000);
}