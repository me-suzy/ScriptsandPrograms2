<?
 include("include/config.inc.php"); 
 include("include/mysql-class.inc.php");
 include("include/functions.inc.php");
?>
<html>
<head>
<title>Pfad / Datei</title>
<link rel="stylesheet" href="style/style.css">
<script language="JavaScript">
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Inhalt wird markiert (und in die Zwischenablage kopiert) !"
setTimeout("window.status=''",1800)
}
}
</script>
</head>

<body class="boxstandart">
<?="<form name=code>";?>
<input class="feld" style="width:100%;font-size:13px;" name="source" value="
<?
ob_start();
echo $_REQUEST['path'];
$code = ob_get_contents();
ob_end_clean();
echo (htmlentities($code));
?>">
<center><br>
<input class="button" onClick="HighlightAll('code.source')"  type="button" value="in die Zwischenablage kopieren">
<input class="button" type="button" onClick="window.close();"  value=" Schließen ">


</center></form>
</body>
</html>