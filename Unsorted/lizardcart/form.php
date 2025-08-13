<? include ("config.inc.php");?>
<? include ("header.php");?>

<html>
<head><title>Send a link</title></head>
<body bgcolor="#ffffff">
<form action="refer.php" method="post">
<?php $fullurl = "{$HTTP_REFERER}";
 echo "$fullurl<br> " ;
 echo"<input type=hidden name=link value=$fullurl>";?>
Friend E-mail: <input type="text" name="email"><br>
Friend Name: <input type="text" name="name"><br>
Your Name: <input type="text" name="sendername"><br>
Your E-mail: <input type="text" name="senderemail"><br>
<input type="submit" value="Go!"> <input type="reset" value="reset">
</form>
</body>
</html>
<? include ("footer.php");?>