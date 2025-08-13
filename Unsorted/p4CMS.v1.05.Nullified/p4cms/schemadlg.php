<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
 <html>
<head>
<title>Auto-Dateiname-Schema</title>
<link rel="stylesheet" href="style/style.css">
<? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<center>
  <table width="98%" cellspacing="0" cellpadding="0"><tr><td width="100%"><input type="text" name="filename" value="" style="width:98%;" class="feld"></td><td width="1"><input type="button" class="button" onClick="SaveDialog('');" value="Durchsuchen"></td></tr></table>
<br>
<table width="98%" border="0" cellpadding="0" cellspacing="1" bgcolor="#666666">
                    <tr> 
                      <td height="17" class="boxheader">&nbsp;Variable</td>
                      <td height="17" class="boxheader">&nbsp;Beschreibung</td>
                      <td height="17" class="boxheader">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{d}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Tag</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('d');" class="subnav">Einfügen</a></td>
                    </tr>
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{m}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Monat</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('m');" class="subnav">Einfügen</a></td>
                    </tr>
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{y}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Jahr</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('y');" class="subnav">Einfügen</a></td>
                    </tr>        
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{h}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Stunde</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('h');" class="subnav">Einfügen</a></td>
                    </tr> 
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{i}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Minute</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('i');" class="subnav">Einfügen</a></td>
                    </tr>    
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{s}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;Sekunde</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('s');" class="subnav">Einfügen</a></td>
                    </tr>  
                    <tr> 
                      <td height="17" bgcolor="#ffffff">&nbsp;{r}</td>
                      <td height="17" bgcolor="#ffffff">&nbsp;3-Stellige Zuffals-Zahl</td>
                      <td height="17" bgcolor="#ffffff" align="center"><a href="javascript:doin('r');" class="subnav">Einfügen</a></td>
                    </tr>                                                                                             
  </table>
                  <br>
                  <input type="button" class="button" onClick="cws();" value="&Uuml;bernehmen">
</center>

<script language="javascript">
<!--
 document.all.filename.value = window.opener.document.all.shema.value;
 document.all.filename.focus();
 
 function doin(wert) {
 	document.all.filename.value = document.all.filename.value + '{' + wert + '}';
 	document.all.filename.focus();
 }
 
 function cws() {
 	window.opener.document.all.shema.value = document.all.filename.value;
 	parent.close();
 }
//-->
</script>
</body>
<html>