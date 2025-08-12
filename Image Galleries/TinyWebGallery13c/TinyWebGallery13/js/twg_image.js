
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

var scalling = 1;
var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
    // myHeight = 100; // dummy for thunderbird - that it looks nice ;).
  } else if( document.documentElement &&
      ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  myHeight = myHeight - 57;  // because of padding !!!

// check if we are using Netscape < 4.x
var wrongBrowser = false;
if (parseInt(navigator.appVersion.substring(0,1)) <= 4) {
		if (navigator.appName == "Netscape") 
			wrongBrowser = true;
}

if (wrongBrowser) {
   document.write('<meta http-equiv="refresh" content="0; URL=html/wrongbrowser.htm">');
}

function send_Browser_resolution(included, path) {
var myConnB = new XHConn();
if (!myConnB) return; // if this is not available we use 490 as max. height and 930 as max. width;
var fnWhenDoneR = function (oXML) {};

var y = 0, x = 0;

	if( typeof( window.innerWidth ) == 'number' ) {
		x = window.innerWidth; y = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth ||document.documentElement.clientHeight ) ) {
		x = document.documentElement.clientWidth; y = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		x = document.body.clientWidth; y = document.body.clientHeight;
	}

if (included == 'yes') {
  x = x - findPosX(document.getElementById("counterpixel")) + 55;
  y = y - findPosY(document.getElementById("cornerpixel")) + 5; 
}

// alert(x + "x" + y);

if ((document.createElement) && (document.createTextNode))
	{
		document.writeln('<div id="emsTest" style="position:absolute; left:0px; top:0px; visibility:hidden; font-family:arial,helvetica,sans-serif">A&nbsp;<br />A&nbsp;<br />A&nbsp;<br />A&nbsp;<br />A&nbsp;<br /></div>');
		var h=999;
		if (document.getElementById('emsTest').clientHeight) h=parseInt(document.getElementById('emsTest').clientHeight);
		else if (document.getElementById('emsTest').offsetHeight) h=parseInt(document.getElementById('emsTest').offsetHeight);
		if (h > 100) scalling = ((h - 100)/200) + 1;
		if (scalling >= 1.3) {
		  scalling = scalling * 1.12;
		}
	}

myConnB.connect( path + "image.php?browserx=" + Math.round(x) + "&browsery=" + Math.round(y) + "&fontscale=" + scalling + "&twg_xmlhttp=r", fnWhenDoneR);
}

function ShrinkToFit(id, width, height)
{
  var OriginalWidth = 0, OriginalHeight = 0;
  bild = document.getElementById(id);
	if (OriginalWidth == 0 && OriginalHeight == 0)
	{
		 document.body.style.display = "block";
		 bild.style.display       = "block";

		OriginalWidth  = bild.width;
		OriginalHeight = bild.height;
	}

	var WidthRatio  = OriginalWidth  / width;
	var HeightRatio = OriginalHeight / height;
	var Ratio = WidthRatio > HeightRatio ? WidthRatio : HeightRatio;

	bild.width  = OriginalWidth  / Ratio;
	bild.height = OriginalHeight / Ratio;
}

//the next 3 lines are browser detection for user-agent DOMS
ns4 = (document.layers) ? true:false //required for Functions to work
ie4 = (document.all) ? true:false //required for Functions to work
ng5 = (document.getElementById) ? true:false //required for Functions to work


// hides the administration layers
function hideSec(n) {
if (ng5) {
	if (document.getElementById(n)) {
	  if (document.getElementById(n).style.visibility == "hidden") {
	    return false;
	  } else {
	    document.getElementById(n).style.visibility = "hidden";
	    return true;
	  }
	}
}
else if (ns4) document.layers[n].visibility = "hide";
else if (ie4) document.all[n].style.visibility = "hidden";
return true;
}

var hideLayer = true;

function stickyLayer() {
  hideLayer = false;
}

function hideAll() {
  if (document.getElementById) {
			if (hideLayer) {
					document.getElementById('details').height="1px";
					return hideSec('details');
			} else {
				 return true;
			} 
  } else {
    hideSec('details');
  }
  // hideSec('detailsbig'); 
}

// twg_shows the administration layers
function twg_showSec(n) {
if (navigator.appName == "Netscape") { 
  n = parseInt(n) - 2; 
}

if (ng5) { 
  document.getElementById("details").style.visibility = "visible";
  document.getElementById("details").height=parseInt(n) + "px";
  adjust_iframe();
}
else if (ns4) { 
  document.layers['details'].visibility = "show";
  document.layers['details'].height=n + "px";
}
else if (ie4) {
  document.all['details'].style.visibility = "visible";
  document.all['details'].height = n + "px";
}
}

function adjust_and_resize_admin_iframe() {
    twg_showSec(500);
  	if (ng5) { 
  		var cornerpixel = document.getElementById("cornerpixel");
			document.getElementById("details").style.top=(findPosY(cornerpixel) + 23) + "px";
			document.getElementById("details").style.left=(findPosX(cornerpixel) - 908) + "px";
	    document.getElementById("details").width=900;
	}
}

var adjust=false;

function enable_adjust_iframe() {
  adjust = true;
}


function adjust_iframe() {
	if (ng5 && adjust) { 
		var cornerpixel = document.getElementById("cornerpixel");
		document.getElementById("details").style.top=(findPosY(cornerpixel) + 23) + "px";
		if (scalling > 1) {
		  widthscale = scalling* 1.12;
		} else {
		  widthscale = scalling;
		}
		document.getElementById("details").style.left=(findPosX(cornerpixel) - ((widthscale * 300) + 8)) + "px";
		// alert('top ' + document.getElementById("details").style.top +  ' - left: ' + document.getElementById("details").style.left);
		
	}
}

function adjust_counter_div() {
	  if (document.getElementById("twg_counterdiv")) {
    var counterpixel = document.getElementById("counterpixel");
    n=95;
    if (navigator.appName == "Netscape") { 
		  n = n + 2; 
    }
    if (adjust) {
      document.getElementById("twg_counterdiv").style.top=(findPosY(counterpixel) - n) + "px";
		  document.getElementById("twg_counterdiv").style.left=(findPosX(counterpixel) + 6) + "px";
		}
		}
}

function show_counter_div() {
  adjust_counter_div();
	twg_showDiv('twg_counterdiv');
}

function hide_counter_div() {
  hideSec('twg_counterdiv');
}

function show_smilie_div() {
  twg_showDiv('twg_smilie_bord');
  twg_showDiv('twg_smilie');
}

function hide_smilie_div() {
  hideSec('twg_smilie');
  hideSec('twg_smilie_bord');
  
}

function hide_control_div() {
  hideSec('twg_fullscreencontrol');
}

function show_control_div() {
  twg_showDiv('twg_fullscreencontrol');
}

function adjust_lang_div(height) {
		var langpixel = document.getElementById("langpixel");
    if (adjust) {
      document.getElementById("twg_langdiv").style.left=(findPosX(langpixel) - 19) + "px"; 
      document.getElementById("twg_langdiv").style.top=(findPosY(langpixel) +3) + "px";
    }
}


function show_lang_div(height) {
   adjust_lang_div(height);
	 twg_showDiv('twg_langdiv');
}

function hide_lang_div() {
   if (document.getElementById("langpixel")) {
      hideSec('twg_langdiv');
   }
}

function twg_showDiv(n) {
if (ng5) { 
  if (document.getElementById(n)) {
  	document.getElementById(n).style.visibility = "visible";
  }
} else if (ns4) { 
  document.layers[n].visibility = "show";
} else if (ie4) {
  document.all[n].style.visibility = "visible";
}
}

function closeiframe(){
    n="details";
    var _dt,_td;
    _dt = document.getElementById ? parent.document.getElementById(n) : document.all ? parent.document.all[n] : parent.document.layers[n];
    _td = document.layers ? _dt : _dt.style;
    if(document.layers)
      _td.visibility = "hide";
    else
      _td.visibility = "hidden"
     if (adjust) {
        _td.top="-400px";
     }
    window.location="about:blank";
    reload = true;
}

function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
		// curleft += obj.offsetLeft
	}
	else if (obj.x) {
		 curleft += obj.x;
	}
	return curleft;
}

function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
		 // curtop += obj.offsetTop
	}
	else if (obj.y) {
		curtop += obj.y;
		}
	return curtop;
}

    
scaleWidth = true;
scaleHeight = true;

function makeIm() {

	myLocHeight = myHeight + 57// padding was suptracted !! 

	f1 = imgSRC_x/imgSRC_y;
	if (resize_always) {
		winWid = myWidth;
		winHgt = myLocHeight; 
	} else {
		winWid = (myWidth > imgSRC_x) ? myWidth : imgSRC_x;
		winHgt = (myLocHeight > imgSRC_y) ? myLocHeight : imgSRC_y; 
	}


	f2 = (winWid/winHgt);
	if ( f1 != f2) { // streched !
		if (f1 > f2) {
			winWid = winHgt * f1;
		} else {
			winHgt = winWid / f1;
		}
	}

	imSRC = encodeURI(imSRC);
	imStr = "<DIV ID=elBGim style='width:" + myWidth + "px;height:" +  myLocHeight + "px;' "
	+ " class='twg_background'>"
	+ "<IMG NAME='imBG' BORDER=0 SRC=" + imSRC;
	if (scaleWidth) imStr += " WIDTH=" + winWid;
	if (scaleHeight) imStr += " HEIGHT=" + winHgt;
	imStr += "></DIV>";
	document.write(imStr);
}

<!-- default keysettings ! are overritten most of the time ! --> 
function key_foreward() { }
function key_back() { } 
function key_up() { } 
function centerGalLater() { } 
function setTimer(time) { }

//-->