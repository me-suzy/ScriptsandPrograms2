<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>{title}</title>
<link rel="stylesheet" href="{baseurl}/template/css/{css}" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
  <div id="dhtmltooltip"></div>

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.x+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.y+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>
<div id="content">
<div class="main">
<table border="0" cellpadding="1" width="650" cellspacing="0">
<tr>
<td>
<table cellpadding="5" border="1" width="100%" cellspacing="0">
<tr>
<td colspan="2" class="tablehead"><center><div class="head">{title}</center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse"  width="90%">
  <tr>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%" >
<tr>
      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        <tr> 
        <tr> 
          <td>
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="100%">
              <tr> 
                <td class="tablehead" align="center">
                  {startdate} <a onMouseover="ddrivetip('{starttip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {siteexposure} <a onMouseover="ddrivetip('{siteexptip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {siteclicks} <a onMouseover="ddrivetip('{clicksfromtip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {percentage} <a onMouseover="ddrivetip('{percentouttip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {ratio} <a onMouseover="ddrivetip('{ratioouttip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
              </tr>
              <tr> 
                <td align="center" class="tablebodycenter"><b>
                  {start_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {siteexp_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {siteclicks_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {outpercent_data}%</b></td>
                <td align="center" class="tablebodycenter"><b>
                  {outratio_data}
                  </b></td>
              </tr>
              <tr> 
                <td class="tablehead" align="center"> 
                  {exposures} <a onMouseover="ddrivetip('{exposurestip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {avgexposures} <a onMouseover="ddrivetip('{avgexptip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {clicks} <a onMouseover="ddrivetip('{clickstip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {inpercent} <a onMouseover="ddrivetip('{percentintip}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
                <td class="tablehead" align="center">
                  {inratio} <a onMouseover="ddrivetip('{ratioin}','Beige', 180)";
				onMouseout="hideddrivetip()">[?]</a>
                </td>
              </tr>
              <tr> 
                <td align="center" class="tablebodycenter"><b>
                  {exposures_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {avgexp_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {clicks_data}
                  </b></td>
                <td align="center" class="tablebodycenter"><b>
                  {inpercent_data}
                  %</b></td>
                <td align="center" class="tablebodycenter"><b>
                  {inratio_data}
                  </b></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
	  <p>
	  <center><b>{credits_hdr}</b> <a onMouseover="ddrivetip('{creditstip}','Beige', 240)";
				onMouseout="hideddrivetip()">[?]</a>: {credits}</center>
	  <p>
    <hr width="500">
{banexp_msg}
<p>
{approved_msg}
<p>
{bannercount} {found}
</div>
</td>
</tr>
</table>
</td>
</tr>
</table><p>
</div>
</div>
<div class="footer">
{footer}
</div>
</div>
{menu}
