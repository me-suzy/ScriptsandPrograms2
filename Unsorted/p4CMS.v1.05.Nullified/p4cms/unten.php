<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <? StyleSheet(); ?>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0">
<table height="100%" cellspacing="0" width="100%" cellpadding="0">
<tr>
<td align="left" valign="middle">&nbsp;&nbsp;&copy; 2002 - 2004 <!--CyKuH [WTN]-->dream4
</td>
<td width="216" valign="top" align="right"><a href="javascript:SwitchPage('about.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="gfx/main/info.gif" alt="" width="76" height="26" border="0"></a><a href="javascript:SwitchPage('logout.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="gfx/main/logout.gif" width="81" height="26" border="0"></a></td>
</tr>
</table>
</body>
</html>
