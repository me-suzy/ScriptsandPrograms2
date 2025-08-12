<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_colors?></title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Original:  D10n (biab@iinet.net.au) -->
<!-- Web Site:  http://www.iinet.net.au/~biab -->
<!-- Modifed for FAQEngine by Boesch IT-Consulting -->
<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
addary = new Array();           //red
addary[0] = new Array(0,1,0);   //red green
addary[1] = new Array(-1,0,0);  //green
addary[2] = new Array(0,0,1);   //green blue
addary[3] = new Array(0,-1,0);  //blue
addary[4] = new Array(1,0,0);   //red blue
addary[5] = new Array(0,0,-1);  //red
addary[6] = new Array(255,1,1);
clrary = new Array(360);
for(i = 0; i < 6; i++)
for(j = 0; j < 60; j++) {
clrary[60 * i + j] = new Array(3);
for(k = 0; k < 3; k++) {
clrary[60 * i + j][k] = addary[6][k];
addary[6][k] += (addary[i][k] * 4);
   }
}
function capture() {
if(document.layers) {
layobj = document.layers['wheel'];
layobj.document.captureEvents(Event.MOUSEMOVE);
layobj.document.onmousemove = moved;
}
else {
layobj = document.all["wheel"];
layobj.onmousemove = moved;
   }
}
function moved(e) {
y = 4 * ((document.layers)?e.layerX:event.offsetX);
x = 4 * ((document.layers)?e.layerY:event.offsetY);
sx = x - 512;
sy = y - 512;
qx = (sx < 0)?0:1;
qy = (sy < 0)?0:1;
q = 2 * qy + qx;
quad = new Array(-180,360,180,0);
xa = Math.abs(sx);
ya = Math.abs(sy);
d = ya * 45 / xa;
if(ya > xa) d = 90 - (xa * 45 / ya);
deg = Math.floor(Math.abs(quad[q] - d));
n = 0;
sx = Math.abs(x - 512);
sy = Math.abs(y - 512);
r = Math.sqrt((sx * sx) + (sy * sy));
if(x == 512 & y == 512) {
c = "000000";
}
else {
for(i = 0; i < 3; i++) {
r2 = clrary[deg][i] * r / 256;
if(r > 256) r2 += Math.floor(r - 256);
if(r2 > 255) r2 = 255;
n = 256 * n + Math.floor(r2);
}
c = n.toString(16);
while(c.length < 6) c = "0" + c;
}
if(document.layers) {
document.layers["wheel"].document.colorform.colorcode.value = "#" + c;
document.layers["wheel"].bgColor = "#" + c;
}
else {
document.all["wheel"].document.colorform.colorcode.value = "#" + c;
document.all["wheel"].style.backgroundColor = "#" + c;
}
return false;
}
//  End -->
</script>
<script language='javascript'>
function choosecolor()
{
	mywin=parent.window.opener;
	mywin.setcolor(document.colorform.colorcode.value);
	parent.window.focus();
	top.window.close();
}
</SCRIPT>
</head>
<body onLoad="capture()">
<table width="98%" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER">
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR class="headingrow" ALIGN="CENTER"><td><h3><?php echo $l_colors?></h3></td>
<td class="actionrow" align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr id="wheel"><td colspan="2">
<a href="javascript:void(0)" onClick="choosecolor()"><img src="gfx/colorwheel.jpg" width=256 height=256 border=0></a>
</td></tr>
<tr class="displayrow2">
<td class="displayrow2"><?php echo $l_mousecolor?></td>
<td class="inputrow" align="right">
<form name="colorform">
<input class="faqeinput" type="text" name="colorcode" size=27>
</form></td></tr></table>
</body>
</html>