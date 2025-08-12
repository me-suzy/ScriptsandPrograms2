function no_error() { 
	return true; 
}
window.onerror=no_error;

function decision(message, url){
	if(confirm(message)) location.href = url;
}

var enablepersist="off" //Enable saving state of content structure? (on/off)
if (document.getElementById){
document.write('<style type="text/css">')
document.write('.switchcontent{display:none;}')
document.write('</style>')
}
function getElementbyClass(classname){
ccollect=new Array()
var inc=0
var alltags=document.all? document.all : document.getElementsByTagName("*")
for (i=0; i<alltags.length; i++){
if (alltags[i].className==classname)
ccollect[inc++]=alltags[i]
}
}
function contractcontent(omit){
var inc=0
while (ccollect[inc]){
if (ccollect[inc].id!=omit)
ccollect[inc].style.display="none"
inc++
}
}
function expandcontent(cid){
if (typeof ccollect!="undefined"){
contractcontent(cid)
document.getElementById(cid).style.display=(document.getElementById(cid).style.display!="block")? "block" : "none"
selectedItem=cid+"|"+document.getElementById(cid).style.display
}
}
function revivecontent(){
selectedItem=getselectedItem()
selectedComponents=selectedItem.split("|")
contractcontent(selectedComponents[0])
document.getElementById(selectedComponents[0]).style.display=selectedComponents[1]
}
function get_cookie(Name) { 
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) { 
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}
function getselectedItem(){
if (get_cookie(window.location.pathname) != ""){
selectedItem=get_cookie(window.location.pathname)
return selectedItem
}
else
return ""
}
function saveswitchstate(){
if (typeof selectedItem!="undefined")
	document.cookie=window.location.pathname+"="+selectedItem
}
function do_onload(){
	getElementbyClass("switchcontent")
	if (enablepersist=="on" && getselectedItem()!="")
	revivecontent()
	}
	if (window.addEventListener)
	window.addEventListener("load", do_onload, false)
	else if (window.attachEvent)
	window.attachEvent("onload", do_onload)
	else if (document.getElementById)
	window.onload=do_onload
	if (enablepersist=="on" && document.getElementById)
	window.onunload=saveswitchstate


/*
Block multiple form submission script- By JavaScriptKit.com
Visit http://www.javascriptkit.com for this script and more
This notice must stay intact for use
*/

//Enter error message to display if submit button has been pressed multiple times below.
//Delete below line if you don't want a message displayed:

var formerrormsg="You\'ve attempted to submit the form multiple times.\n Please reload page if you need to resubmit form."

function checksubmit(submitbtn){
submitbtn.form.submit()
checksubmit=blocksubmit
return false
}

function blocksubmit(){
if (typeof formerrormsg!="undefined")
alert(formerrormsg)
return false
}



