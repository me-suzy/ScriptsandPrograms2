<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Admin Login/Control Panel</title>
	<?
	 $file = $_POST['file'];
	 $folder = $_POST['folder'];
	 ?>
</head>
<body>
<?
include("header.php");
?>
<form action="editprog.php" method="POST">
<input name="filen" type="hidden" value="<? echo $file; ?>">
<input name="folder" type="hidden" value="<? echo $folder; ?>">
<textarea name="edited" rows=25 cols=100><?
$str = file_get_contents("$folder/$file");
echo $str;
 ?></textarea><br>
<input type="submit" value="Edit"><br><br>

</form>
<?
include("footer.php");
?>
</body>
</html>

