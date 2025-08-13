<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
<title>p4CMS - [ <? echo($HTTP_SESSION_VARS[u_name]); ?> ]</title>
</head>
<frameset rows="45,26,2,*,30" border="0">
 <frame src="/p4cms/oben.php?d4sess=<? echo($sessid); ?>" scrolling="no" name="oben" noresize>

 <frame src="/p4cms/reg.php?mode=dokumente&d4sess=<? echo($sessid); ?>" scrolling="no" name="register" noresize>

<frame src="/p4cms/empty.htm" scrolling="no" name="oben" noresize>

 <frameset cols="195,2,*" border="1">
   <frame src="/p4cms/struktur_dokumente.php?d4sess=<? echo($sessid); ?>" scrolling="auto" name="struktur">
 <frame src="/p4cms/empty.htm" scrolling="no"  name="vertikal">
   <frame src="/p4cms/dokumente_main.php?d4sess=<? echo($sessid); ?>" scrolling="auto" name="inhalt">
  <frame src="UntitledFrame-2"></frameset>

<frameset rows="2,*" border="0">

<frame src="/p4cms/empty.htm" scrolling="no" name="no" noresize>
 <frame src="/p4cms/unten.php?d4sess=<? echo($sessid); ?>" scrolling="no" name="unten" noresize>
</frameset>

<noframes></noframes>
</html>
