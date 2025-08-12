<html>
<head>
	<title>Submit an article</title>
	<?PHP
    include("config.php");
    ?>
</head>

<body>
<?
include("header.php");
?>
<form action="submit.php" method=post  name="post" onsubmit="return checkData()">
<input name="Name" type="text" value=""> Name of the article<br><br>
<select name="Cata">
<?PHP
echo $catlist;
?>
</select> Catagory the article falls under<br><br>
<textarea name="Stuff" rows=10 cols=75% ></textarea> <br><center>Type the article here</center><br><br>
<input name="Alias" type="text" value=""> Posting alias (Your name)<br><br>
<input type=submit value="Submit Article"><br><br>
 
<?
include("footer.php");
?>
</form>
</body>
</html>
