<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>develooping flash chat 1.6.5</title>
	<style type="text/css">
body {
	background-color: #EEEEEE;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	vertical-align: middle;
}
a:link{ color :#990000;text-decoration: none;}
a:active{ color :#FF9933;text-decoration: none;}
a:visited {  color :#CC6666;text-decoration: none;}
a:hover { text-decoration: underline; 
color : #990000;
}
    </style>
    <script language="JavaScript" type="text/JavaScript"> 
<!--
var chat_win = null;
var w = 480, h = 340;
if (document.all) {
   w = document.body.clientWidth; 
   h = document.body.clientHeight;
}
if (document.layers) { 
w = window.innerWidth; 
h = window.innerHeight; 
}
function openBrWindow(theURL,winName,features) {
  chat_win=window.open(theURL,winName,features);
  if (chat_win.moveTo) {
  chat_win.moveTo(w/2,h/2);
  }
}

function abrechat(){
if (chat_win && chat_win.open && !chat_win.closed){
chat_win.focus();
}else{
openBrWindow('intro.php?self=<?php echo $PHP_SELF;?>','chat','toolbar=no,scrollbars=no,scrollbars=0,width=550,height=350');
}
}
//-->
    </script>
</head>
<body bgcolor="#EEEEEE">
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle">
  <a href="javascript:abrechat();">develooping flash chat 1.6.5</a>
    </td>
  </tr></table>
</body>
</html>
