<?
 include("include/include.inc.php");
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
<title>umbenennen</title>
<link rel="stylesheet" href="style/style.css">
</head>

<body class="boxstandart">
<? 
$root = $_SERVER['DOCUMENT_ROOT'];
if($_REQUEST['send']=="1") {

if($_REQUEST['modus']=="dir__"){$_REQUEST['path'] = "/";};

if(ltrim(rtrim($_REQUEST['newname']))!=""){
if($_REQUEST['modus']=="dir")
	{
	@rename("$root$_REQUEST[path_1]", "$root$_REQUEST[path_3]$_REQUEST[newname]");
	?>
	<body onLoad="window.open('filemanager.php?act=reswith&d4sess=<? echo($sessid); ?>&dir=<?=$_REQUEST['path_3'];?>', 'filemanager', 'width=600,height=500,top=20,left=20');window.close();">
	
	
	<?
	} else {
	$new = str_replace("/","",ltrim(rtrim(strip_tags($_REQUEST['newname']))));
	@rename("$root$_REQUEST[path]$_REQUEST[oldname]", "$root$_REQUEST[path]$new");
	@chmod($root.$_REQUEST['path'].$new, 0777);
	?>
	<body onLoad="window.open('filemanager.php?act=reswith&d4sess=<? echo($sessid); ?>&dir=<?=$_REQUEST['path'];?>', 'filemanager', 'width=600,height=500,top=20,left=20');window.close();">
	
	<?
	}
	$_REQUEST['oldname'] = $new;
	}

}
?>
<form name="code">
<table width="100%" border="0" cellpadding="2" cellspacing="1">
  <tr>
    <td>Alter Name </td>
    </tr>
  <tr>
  <td>
<input class="feld" style="width:100%;font-size:13px;" name="source" value="
<?
ob_start();
echo $_REQUEST['oldname'];
$code = ob_get_contents();
ob_end_clean();
echo (htmlentities($code));
?>" disabled>
</td>
  </tr>
  <tr>
    <td>Neuer Name </td>
  </tr>
  <tr>
    <td><input class="feld" style="width:100%;font-size:13px;" name="newname"></td>
  </tr>
  </table>
<center><br>
<input name="send" type="hidden" id="send" value="1">
<? if($_REQUEST['modus']!="dir"){ ?>
<input name="modus" type="hidden" id="modus" value="<?=$_REQUEST['modus'];?>">
<input type="hidden" name="oldname" value="<? echo $_REQUEST['oldname'];?>">
<input type="hidden" name="path" value="<?=$_REQUEST['path'];?>">
<? } else { ?>
<input name="modus" type="hidden" id="modus" value="<?=$_REQUEST['modus'];?>">
<input type="hidden" name="path_1" value="<?=$_REQUEST['path_1'];?>">
<input type="hidden" name="path_2" value="<?=$_REQUEST['path_2'];?>">
<input type="hidden" name="path_3" value="<?=$_REQUEST['path_3'];?>">
<? } ?>
<input name="Senden" type="submit" class="button" value="umbenennen">
<input class="button" type="button" onClick="window.close();"  value=" Schließen ">


</center></form>
</body>
</html>