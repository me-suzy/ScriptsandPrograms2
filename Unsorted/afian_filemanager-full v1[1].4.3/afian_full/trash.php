<?
/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/


require_once("config.php");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");

$filename = safeFilename($filename);
$dir = stripslashes(safepath($dir));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Afian file manager - Trash</title>
	<meta name="author" content="Vlad Roman vlad@afian.com">
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/style.css">
</head>

<body>
<form action="index.php?dir=<?echo prepUrl($dir)?>&act=trash" name="FM_trashform" target="filemanager" method="post">
<?
$i=0;
$fd = fopen ("$config[root_dir]/trashundo.txt", "r");
$trashOptions = "";
if ($fd) {
while (!feof ($fd)) {
	$line = fgets($fd, 4096);
	if (strlen($line) > 3) {
		$i++;
		$splited = split(" @#@ ",$line);
		$isdir = is_dir($config[root_dir]."/trash/".trim($splited[2]));
?>
<input type="checkbox" name="trashfiles[]" value="<?echo $splited[2]?>" id="<?echo $i?>" checked> <img src="<?echo icon($splited[1], $isdir, $returnType = 0, $dirSize = 0, $public = 0, false, true);?>" align="middle" width="16" height="16" border="0"> <label for="<?echo $i?>"><span title="Deleted from: <?echo $splited[0]?>" <?if($isdir){?>style="font-weight:bold;"<?}?>><?echo $splited[0] ?></span></label><br>
<?
	}
}

fclose ($fd);
}
?>
<div align="center">
<?if ($i > 0){
$trashStatus = "$i objects in trash.";
?>
<br><br>
<input type="hidden" name="action" value="">
<input type="button" class="button" value="restore" onClick="javascript:this.form.action.value='restore';this.form.submit()"> 
<input type="button" class="button" value="move here" onClick="javascript:this.form.action.value='move';this.form.submit()"> 
<input type="button" class="button" value="delete" onClick="javascript:this.form.action.value='delete';this.form.submit()">
<input type="button" onClick="javascript:parent.closePopup()" class="button" value="close">
<?} else {
$trashStatus = "Trash empty.";
?>
<br><br>
<strong>Trash empty.</strong>
<br><br>
<?}?>
</div>
</form>


</body>
</html>