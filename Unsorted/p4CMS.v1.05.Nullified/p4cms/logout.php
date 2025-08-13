<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($_REQUEST['really']=="yes") {
 	$HTTP_SESSION_VARS[u_loggedin] = 'no';
 	session_destroy();
?>
<html>
<head>
 <title>Logout</title>
 <? StyleSheet(); ?>  
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body background="gfx/main/bgbody.gif" onLoad="LogOut();">
<?
 MsgBox("<center>Sitzung wird beendet...</center>");
?>
</body>
</html>
<?
 } else {
?>
<html>
<head>
 <title>Logout...</title>
 <? StyleSheet(); ?>  
</head>
<body background="gfx/main/bgbody.gif">
<?
 MsgBox("<center><img src=\"gfx/promt/questionmark.gif\" border=\"0\" alt=\"\"><br><br>Wollen Sie sich wirklich ausloggen?<br><br><a target=\"_top\" href=\"logout.php?really=yes\"><img src=\"gfx/main/ja.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;<a href=\"javascript:history.back(1);\"><img src=\"gfx/main/nein.gif\" border=\"0\"></a></center>");
?>
</body>
</html>
<?
 }
?>