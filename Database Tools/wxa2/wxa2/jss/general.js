
var popWin = null    // use this when referring to pop-up window
var winCount = 0
var winName = "popWin"
function openPopWin(winURL, winWidth, winHeight, winFeatures, winLeft, winTop){
  var d_winLeft = 20  // default, pixels from screen left to window left
  var d_winTop = 20   // default, pixels from screen top to window top
  winName = "popWin" + winCount++ //unique name for each pop-up window
  closePopWin()           // close any previously opened pop-up window
  if (openPopWin.arguments.length >= 4)  // any additional features? 
    winFeatures = "," + winFeatures
  else 
    winFeatures = "" 
  if (openPopWin.arguments.length == 6)  // location specified
    winFeatures += getLocation(winWidth, winHeight, winLeft, winTop)
  else
    winFeatures += getLocation(winWidth, winHeight, d_winLeft, d_winTop)
  popWin = window.open(winURL, winName, "width=" + winWidth 
           + ",height=" + winHeight + winFeatures)
  }
function closePopWin(){    // close pop-up window if it is open 
  if (navigator.appName != "Microsoft Internet Explorer" 
      || parseInt(navigator.appVersion) >=4) //do not close if early IE
    if(popWin != null) if(!popWin.closed) popWin.close() 
  }
  function getLocation(winWidth, winHeight, winLeft, winTop){
  var winLocation = ""
  if (winLeft < 0)
    winLeft = screen.width - winWidth + winLeft
  if (winTop < 0)
    winTop = screen.height - winHeight + winTop
  if (winTop == "cen")
    winTop = (screen.height - winHeight)/2 - 20
  if (winLeft == "cen")
    winLeft = (screen.width - winWidth)/2
  if (winLeft>0 & winTop>0)
    winLocation =  ",screenX=" + winLeft + ",left=" + winLeft	
                + ",screenY=" + winTop + ",top=" + winTop
  else
    winLocation = ""
  return winLocation
  }
  
  function confirm_url(url, msg)
{if (confirm(  msg )) window.location = url;}

function precise_url(url, parm, message, defaut)
{userInput = prompt(message,defaut);
if (userInput != '' && userInput != null) {
 	window.location = url + "&" + parm + "=" + escape(userInput);}

}

///////////////////////////
//location
///////////////////////////
var isDHTML = 0;
var isID = 0;
var isAll = 0;
var isLayers = 0;


if (document.getElementById) {isID = 1; isDHTML = 1;}
else {
if (document.all) {isAll = 1; isDHTML = 1;}
else {
browserVersion = parseInt(navigator.appVersion);
if ((navigator.appName.indexOf('Netscape') != -1) && (browserVersion == 4)) {isLayers = 1; isDHTML = 1;}
}}

function findDOM(objectID,withStyle) {
if (withStyle == 1) {
if (isID) { return (document.getElementById(objectID).style) ; }
else { 
if (isAll) { return (document.all[objectID].style); }
else {
if (isLayers) { return (document.layers[objectID]); }
};}
}
else {
if (isID) { return (document.getElementById(objectID)) ; }
else { 
if (isAll) { return (document.all[objectID]); }
else {
if (isLayers) { return (document.layers[objectID]); }
};}
}
}

function getPageHeight() {
	if (window.innerHeight != null)
		return window.innerHeight; 
	if (document.body.clientHeight != null)
		return document.body.clientHeight;
	return (null);
}

function getPageWidth() {
	if (window.innerWidth != null)
		return window.innerWidth;
	if (document.body.clientWidth != null)
		return document.body.clientWidth;
	return (null);
}
function getObjectTop(objectID) {
	var domStyle = findDOM(objectID,1);
	var dom = findDOM(objectID,0);
	if (domStyle.top)
		return domStyle.top;
	if (domStyle.pixelTop)
		return domStyle.pixelTop;
	if (dom.offsetTop)
		return dom.offsetTop;
	return (null);
}
//do not change because of regular expressions
var thousands_delimiter = " "; 
var thousands_replace = /(\s)+/g;
var decimals_delimiter = "."; 
function isNumeric(amount)
{
var o = new RegExp("^[0-9" + decimals_delimiter + thousands_delimiter +"]+$");
return o.test(amount);
}
function formatIfNumber(amount)
{
var decimals = 2;
if (arguments.length==2) decimals = arguments[1];
if (amount=="" || !isNumeric(amount)) return "";

amount = amount.replace(thousands_replace, "");
if (amount!="" && isNumeric(amount) )
{
var a = amount.split(decimals_delimiter,2)
var d = "";
if (a.length>1) d=a[1];
var i = parseInt(a[0]);
if(isNaN(i)) { return ''; }
var minus = '';
if(i < 0) { minus = '-'; }
i = Math.abs(i);
var n = new String(i);
var a = [];
while(n.length > 3)
{
var nn = n.substr(n.length-3);
a.unshift(nn);
n = n.substr(0,n.length-3);
}
if(n.length > 0) { a.unshift(n); }
n = a.join(thousands_delimiter);
if(d=="" ||decimals==0 ) { amount = n;}
else { amount = n + '.' + d.substring(0,decimals); }
amount = minus + amount;
}
if (amount=="NaN") amount='';
return amount;
}

function restoreNumbers(frm)
{
    var dfe = frm.elements;
    for (var i = 0; i < dfe.length; i++) 
      {
	  var eltype = dfe[i].type;
	  var elv = dfe[i].value;
	  var eln =  dfe[i].name;
	  if (eltype=="text" && isNumeric(elv)) 
			{
			dfe[i].value = dfe[i].value.replace(thousands_replace , "");
			dfe[i].value = dfe[i].value.replace(decimals_delimiter , ".");
			}
			//alert( eln +" : " + elv);
	  }
}

function show_loading() {
if( document.all['loading'] ) {
	document.all['loading'].style.top = document.body.scrollTop;
	document.all['loading'].style.visibility = 'visible';
 }}

 
 // mouseover tr highlight
var couleur_origine="";
var font_origine="";
var color_over = "#efdddd";
var color_highlight="#f2f9ff";
var color_neutral="";
//highlight
function h(obj)
{couleur_origine=obj.bgColor;obj.bgColor=color_over;
obj.style.cursor = 'hand';
}
//unhighlight
function uh(obj)
{obj.bgColor=couleur_origine;obj.style.color=font_origine;
obj.style.cursor = 'default';}

function h1(obj)
{couleur_origine=obj.bgColor;obj.bgColor=color_highlight; 
font_origine = obj.style.color;obj.style.color="white";
obj.style.cursor = 'hand';
}

// the function that selects the right one in the form
function apply_value(this_s, this_value, mode)
{
if (mode=='')
	{for (i=0;i<this_s.length;i++)
	 if (this_s.options[i].value==this_value )
	 	{
		 this_s.options[i].selected=true;
		}	 
	}
if (mode=='checkbox')
	{for (i = 0; i < this_s.length; i++) 
		if (this_s[i].value==this_value) 
				 this_s[i].checked=true;}

}