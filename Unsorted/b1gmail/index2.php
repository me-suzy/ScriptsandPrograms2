<? header("Cache-Control: no-cache");
 session_start();

 if (isset($user)) {
?>
<html>
<head>
<title>E-Mail</title>
</head>
<frameset cols="165,*" border="0">
 <frame src="menu.php?<?php echo SID?>" scrolling="auto" name="links" noresize>
 <frame src="startseite.php?<?php echo SID?>" scrolling="auto" name="rechts" noresize>
</frameset>
</html>
<?
 } else {
?>
 Login erforderlich
<?
 }
?>