<?
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
?>
<html>
<head>
<title>Code anzeigen</title>
<link rel="stylesheet" href="../../style/style.css">
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

<body topmargin="0" leftmargin="0">
<?="<form name=code>";?>
<textarea name="source" style="width:100%;height:88%;">
<?
ob_start();
?>
{GALERIE:<?=$_REQUEST['id'];?>}
<?	
$code = ob_get_contents();
ob_end_clean();
echo (htmlentities($code));
?>
</textarea>
<center>
<input class="button" onClick="HighlightAll('code.source')" style="height:10%;" type="button" value="in die Zwischenablage kopieren">
<input class="button" type="button" onClick="window.close();" style="height:10%;" value=" Schließen ">


</center></form>
</body>
</html>