<? ob_start();
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <title>Modul [<? echo($_REQUEST[module]); ?>]</title>
 <? StyleSheet(); ?>  
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<?
 if (!@include("modules/" . $_REQUEST[module] . "/" . $_REQUEST[page])) {
 	echo "<b>Fehler...</b><br>Diese Modul ist nicht installiert.";
 }
?>
</body>
</html>