<?php require_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>delete_record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}
.style2 {color: #FF3300}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
//-->
</script>
</head>

<body>
<div id="byID" style="position:absolute; left:10px; top:75px; width:600px; height:187px; z-index:1; visibility: hidden;">
  <form action="confirm_delete.php" method="post" name="delete_record" id="delete_record">
    <table width="600" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td class="forTableBgLeft">Please enter the <span class="style2">ID</span> of the record you wish to <span class="style1">delete</span>: (You can see the Record ID from Main Page) </td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="deletionID" type="text" class="forForm" id="deletionID2"></td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="Submit" type="submit" class="forButton" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
<div id="byEmail" style="position:absolute; left:10px; top:75px; width:600px; height:165px; z-index:2; visibility: hidden;">
  <form action="confirm_deleteEmail.php" method="post" name="delete_record" id="delete_record">
    <table width="600" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td class="forTableBgLeft">Please enter the <span class="style2">Email Address</span> of the record you wish to <span class="style1">delete</span>: </td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="deletionEmail" type="text" class="forForm" id="deletionID3"></td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="Submit2" type="submit" class="forButton" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
<table width="600" border="0" cellspacing="1" cellpadding="3">
  <tr class="forTableBgLeft">
    <td height="25" colspan="3"><div align="center">Choose your way of Deleteting Record </div></td>
  </tr>
  <tr class="forTableBgRight">
    <td width="200"><div align="center">[ <a href="#" onClick="MM_showHideLayers('byID','','show','byEmail','','hide','byDate','','hide')">By ID</a> ]</div></td>
    <td width="200"><div align="center">[ <a href="#" onClick="MM_showHideLayers('byID','','hide','byEmail','','show','byDate','','hide')">By Email</a> ]</div></td>
    <td width="200"><div align="center">[ <a href="#" onClick="MM_showHideLayers('byID','','hide','byEmail','','hide','byDate','','show')">By Date and Time </a> ]</div></td>
  </tr>
</table>
<div id="byDate" style="position:absolute; left:10px; top:75px; width:600px; height:165px; z-index:3; visibility: hidden;">
  <form action="confirm_deleteName.php" method="post" name="delete_record" id="delete_record">
    <table width="600" border="0" cellspacing="1" cellpadding="2">
      <tr>
        <td class="forTableBgLeft">Please enter the <span class="style2">Date</span> of the record you wish to <span class="style1">delete</span>: Example 


 16 Feb 2000 07:19 PM</td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="deletionName" type="text" class="forForm" id="deletionEmail"></td>
      </tr>
      <tr>
        <td class="forTableBgRight"><input name="Submit22" type="submit" class="forButton" value="Submit"></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
