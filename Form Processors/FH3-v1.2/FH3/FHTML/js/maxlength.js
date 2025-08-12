var ns6=document.getElementById&&!document.all 

function restrictinput(maxlength,e,placeholder){ 
    if (window.event&&event.srcElement.value.length>=maxlength) 
        return false 
    else if (e.target&&e.target==eval(placeholder)&&e.target.value.length>=maxlength) 
    { 
        var pressedkey=/[a-zA-Z0-9\.\,\/]/ //detect alphanumeric keys 
        if (pressedkey.test(String.fromCharCode(e.which))) 
            e.stopPropagation() 
    } 
} 

function restrictpaste(maxlength,e,placeholder)
{
    if (window.event&&event.srcElement.value.length+window.clipboardData.getData("Text").length>maxlength)
        return false
    else
        countlimitafterpaste(maxlength,e,placeholder)
}

function countlimit(maxlength,e,placeholder,displaymessage) 
{ 
    var theform=eval(placeholder) 
    var lengthleft=maxlength-theform.value.length 
    var placeholderobj=document.all? document.all[placeholder] : document.getElementById(placeholder) 
    if (window.event||e.target&&e.target==eval(placeholder)) 
    { 
        if (lengthleft<0) 
            theform.value=theform.value.substring(0,maxlength) 
        if (displaymessage) 
            placeholderobj.innerHTML=lengthleft 
    } 
}

function countlimitafterpaste(maxlength,e,placeholder)
{
    var theform=eval(placeholder)
    var lengthleft=maxlength-theform.value.length-window.clipboardData.getData("Text").length
    var placeholderobj=document.all? document.all[placeholder] : document.getElementById(placeholder)
    if (window.event||e.target&&e.target==eval(placeholder))
    {
        if (lengthleft<0)
            theform.value=theform.value.substring(0,maxlength)
        placeholderobj.innerHTML=lengthleft
    }
}


function displaylimit(thename, theid, thelimit, displaymessage, message) 
{ 
    var theform=theid!=""? document.getElementById(theid) : thename 
    var limit_text=message.replace('%d', '<span id="'+theform.toString()+'">'+thelimit+'</span>'); 
    var empty_text='<b><span id="'+theform.toString()+'"></span>' 

    if ((document.all||ns6) && displaymessage) 
        document.write(limit_text) 
    else 
        document.write(empty_text) 

    if (document.all) 
    { 
        eval(theform).onpaste=function(){ return restrictpaste(thelimit,event,theform)}
        eval(theform).onkeypress=function(){ return restrictinput(thelimit,event,theform)} 
        eval(theform).onkeyup=function(){ countlimit(thelimit,event,theform,displaymessage)} 
    } 
    else if (ns6) 
    { 
        document.body.addEventListener('keypress', function(event) { restrictinput(thelimit,event,theform) }, true); 
        document.body.addEventListener('keyup', function(event) { countlimit(thelimit,event,theform,displaymessage) }, true); 
    } 
} 