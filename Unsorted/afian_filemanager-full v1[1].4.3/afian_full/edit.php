<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Afian file manager - Edit text</title>
<meta name="author" content="Vlad Roman vlad@afian.com">
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/style.css">
</head>

<body>
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
error_reporting(0);
require_once("config.php");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");

$filename = safeFilename($filename);
$dir = stripslashes(safepath($dir));

	//set path
	$base_dir = $config[base_dir];
	if ($dir) {
		$base_dir = $base_dir . $dir;
	}


if ($submit) {
	
$fileContent = stripslashes($fileContent);
$fp = @fopen("$base_dir/$filename","w");
$lenght = strlen($fileContent);

if (!$fp) {
	if (@superChmod($base_dir."/".$filename, "777")) {
		$fp = @fopen("$base_dir/$filename","w");
	}
}
		if (!$fp) {
			echo "Failed to create/open file.";
		} else {
			@fputs($fp, $fileContent, $lenght);
?>
	<script>
	//parent.closePopup();
	parent.location.href='index.php?dir=<?echo $dir?>';
	</script>
<?
			fclose($fp);
		}



}
?>
<br>
<form action="edit.php?dir=<?echo $dir?>&submit=true" method="post" name="editform">
<table border="0" align="center" width="500" cellpadding="0" cellspacing="0">
<tr>
	<td><strong>Filename:</strong></td>
	<td><!-- <?echo $dir?>/  --><input type="text" name="filename" value="<?echo $filename?>" size="30"> <?if ($new == 1) {?><input type="button" class="button" value="insert HTML template" onClick="document.editform.fileContent.value='<html>\n<head>\n\t<title>Untitled</title>\n</head>\n\n<body>\n\n\n</body>\n</html>'"><?}?></td>
</tr>
</table>
<div align="center">
<br>
<textarea name="fileContent" cols="70" rows="19"><?
if ($new != 1) {
$fp = @fopen($base_dir."/".$filename,"r");

if (!$fp) {
	if (@superChmod($base_dir."/".$filename, "644")) {
		$fp = @fopen($base_dir."/".$filename,"r");
	}
}
if (!$fp) {
	echo "Failed to open file \"".$filename."\". Check file's permissions.";
} else {
	$contents = fread ($fp, filesize ($base_dir."/".$filename));
	echo htmlspecialchars($contents);
}




}
?></textarea>
<br>
<br>
<input type="submit" name="submit" value="save" class="button">
 
<input type="button" onClick="javascript:parent.closePopup()" value="cancel" class="button">
</div>

</form>
</body>
</html>