<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $mode = $_REQUEST[mode];
 $nav_dok = "";
 $nav_rub = "";
 $nav_vor = "";
 $nav_abf = "";
 if ($mode == "dokumente") {
 	$bild = "dokumente.gif";
 }
 if ($mode == "rubriken") {
 	$bild = "rubriken.gif";
 }
 if ($mode == "vorlagen") {
 	$bild = "vorlagen.gif";
 }
 if ($mode == "abfragen") {
 	$bild = "abfragen.gif";
 }
 if ($mode == "") {
 	$bild = "dokumente.gif";
 }
?>
<html>
<head>
 <? StyleSheet(); ?>  
  <script language="javascript" src="include/common.js"></script>
</head>
<body background="gfx/main/back.gif" topmargin="0" leftmargin="0" onLoad="MM_preloadImages('gfx/main/dokumente.gif','gfx/main/rubriken.gif','gfx/main/vorlagen.gif','gfx/main/abfragen.gif','/p4cms/gfx/forw.gif','/p4cms/gfx/back.gif')">
<table width="100%" height="29" cellspacing="0" cellpadding="0"><!---->
<tr>
<td width="578" bgcolor="#FEFEFE"><img width="578" src="gfx/main/<? echo($bild); ?>" name="nav"  border="0" usemap="#navMap" id="nav">
  <map name="navMap">
  <area onclick="this.blur()" onfocus="this.blur()"  shape="rect" coords="5,-16,116,24" href="javascript:SwitchMode('dokumente','<? echo($sessid); ?>');" onMouseOver="MM_swapImage('nav','','gfx/main/dokumente.gif',1)" onMouseOut="MM_swapImgRestore()"><area onclick="this.blur()" onfocus="this.blur()"  shape="rect" coords="119,0,229,24" href="javascript:SwitchMode('rubriken','<? echo($sessid); ?>');" onMouseOver="MM_swapImage('nav','','gfx/main/rubriken.gif',1)" onMouseOut="MM_swapImgRestore()"><area onclick="this.blur()" onfocus="this.blur()"  shape="rect" coords="231,3,343,23" href="javascript:SwitchMode('vorlagen','<? echo($sessid); ?>');" onMouseOver="MM_swapImage('nav','','gfx/main/vorlagen.gif',1)" onMouseOut="MM_swapImgRestore()"><area onclick="this.blur()" onfocus="this.blur()"  shape="rect" coords="346,2,455,23" href="javascript:SwitchMode('abfragen','<? echo($sessid); ?>');" onMouseOver="MM_swapImage('nav','','gfx/main/abfragen.gif',1)" onMouseOut="MM_swapImgRestore()">
  </map></td>
  <td nowrap bgcolor="#FEFEFE"><div align="right"><a href="javascript:history.go(-1);" onMouseOver="MM_swapImage('b','','/p4cms/gfx/back.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="/p4cms/gfx/back_0.gif" name="b" width="88" height="26" border="0" id="b"></a><a href="javascript:history.go(+1);" onMouseOver="MM_swapImage('f','','/p4cms/gfx/forw.gif',1)" onMouseOut="MM_swapImgRestore()"><img src="/p4cms/gfx/forw_0.gif" name="f" width="85" height="26" border="0" id="f"></a></div></td>
</tr>
</table>
</body>
</html>
