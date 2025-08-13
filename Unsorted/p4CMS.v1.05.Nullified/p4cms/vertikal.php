<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <title>D4C.M Frame Vertikal</title>
 <? StyleSheet(); ?>  
</head>
<body background="gfx/main/reg_back.gif" topmargin="0" leftmargin="0">
</body>
</html>
