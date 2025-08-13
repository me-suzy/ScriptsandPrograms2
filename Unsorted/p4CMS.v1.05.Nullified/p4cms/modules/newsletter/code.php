<?PHP
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
 
 $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
 ereg("http:\/\/(.*)" . preg_quote($p4cms_pfad) . "\/", $url, $find);
 $baseurl = $find[0];
 $subscribe = $baseurl . "modules/newsletter/subscribe.php";
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
<textarea name="source" style="width:100%;height:93%;">
<?
ob_start();
if ($_REQUEST['action']=="multi") {
?>
<!-- NEWSLETTER -->
<script language="javascript" type="text/javascript">
<!--
function chkabo()
{
if(document.newsletter.email.value==""){
alert("Bitte E-Mail angeben");
document.newsletter.email.focus();
return false;}

if(document.newsletter.email.value.indexOf('@') == -1){
alert("Bitte E-Mail angeben");
document.newsletter.email.focus();
return false;}

if(document.newsletter.name.value==""){
alert("Bitte Name angeben");
document.newsletter.name.focus();
return false;}

}
//-->
</script>
<form name="newsletter" style="display:inline;" action="<?=$subscribe;?>" method="post" target="_blank" onSubmit="return chkabo();">
<table>
<tr>
<td>E-Mail:</td>
<td><input class="feld" type="text" name="email" value="" size="30"></td>
</tr>
<tr>
<td>Name:</td>
<td><input class="feld"  type="text" name="name" value="" size="30"></td>
</tr>
<tr>
<td>Art:</td>
<td><input type="radio" name="art" value="text" checked> Text &nbsp;&nbsp;<input type="radio" name="art" value="html"> HTML</td>
</tr>
<tr>
<td>Liste:</td>
<td><select class="feld"  name="list"><?
while (list($key,$val) = each($_POST)) {
	if (substr($key, 0, 1)=="l") {
		$id = (int)str_replace("l", "", $key);
		$sql =& new MySQLq();
		$sql->Query("SELECT titel FROM " . $sql_prefix . "mailinglisten WHERE id='$id'");
		$row = $sql->FetchRow();
		$titel = stripslashes($row->titel);
		$sql->Close();
		echo "<option value=\"$id\">$titel</option>\n";
	}
}
?></select></td>
</tr>
<tr>
<td></td>
<td>
<input class="button" type="submit" value="Eintragen">
</td>
</tr>
</table>

</form>
<!-- NEWSLETTER -->
<?
} else {
?>
<!-- NEWSLETTER -->
<script language="javascript" type="text/javascript">
<!--
function chkabo()
{
if(document.newsletter.email.value==""){
alert("Bitte E-Mail angeben");
document.newsletter.email.focus();
return false;}

if(document.newsletter.email.value.indexOf('@') == -1){
alert("Bitte E-Mail angeben");
document.newsletter.email.focus();
return false;}

if(document.newsletter.name.value==""){
alert("Bitte Name angeben");
document.newsletter.name.focus();
return false;}

}
//-->
</script>
<form name="newsletter" style="display:inline;" action="<?=$subscribe;?>?list=<?=$_REQUEST['id'];?>" method="post" target="_blank" onSubmit="return chkabo();">
<table>
<tr>
<td>E-Mail:</td>
<td><input type="text" name="email" value="" size="30"></td>
</tr>
<tr>
<td>Name:</td>
<td><input type="text" name="name" value="" size="30"></td>
</tr>
<tr>
<td>Art:</td>
<td><input type="radio" name="art" value="text" checked> Text &nbsp;&nbsp;<input type="radio" name="art" value="html"> HTML</td>
</tr>
<tr>
<td></td>
<td>
<input class="button" type="submit" value="Eintragen">
</td>
</tr>
</table>

</form>
<!-- NEWSLETTER -->
<?	
}
$code = ob_get_contents();
ob_end_clean();
echo (htmlentities($code));
?>
</textarea>
<center>
<input class="button" onClick="HighlightAll('code.source')" type="button" value="in die Zwischenablage kopieren">
<input class="button" type="button" onClick="window.close();" style="" value=" Schließen ">		

</center></form>
</body>
</html>