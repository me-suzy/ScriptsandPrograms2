<?php
session_start();
if(!$_SESSION['username']){ 
echo "Please login.";
exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ice-Downloader</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
a:link {
	color: #FF0000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF0000;
}
a:hover {
	text-decoration: underline;
	color: #FF6600;
}
a:active {
	text-decoration: none;
	color: #FF6600;
}
a {
	font-size: 10px;
}
-->
</style></head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Protected Area</title>
</head>

<body>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr>
    <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center"><img src="logo.jpg" height="130"></div></td>
      </tr>
      <tr>
        <td height="19"><table width="110" border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#000000">
          <tr>
            <td width="110" height="21"> <?php include('menu.php') ?><br>
<br>
</td>
          </tr>
        </table>
          <table width="290" height="25" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="290" height="21" bgcolor="#FFFFFF"><form name="form1" method="post" action="add.php">
  <p>Name:<br>
    <input name="name" type="text" id="name">
    <br>
  Screenshot:<br>
  <input name="picture" type="text" id="picture" value="http://">
  <br>
  Desription:<br>
  <textarea name="description" cols="30" wrap="VIRTUAL" id="description"></textarea>
  <br>
  Download:<br>
  <input name="download" type="text" id="download" value="http://">
  <br>
  <br>
  <input type="submit" name="submit" value="Add Download!">
</p>
  </form>
  <?php if($_POST['submit']){
$name = $_POST['name'];
$picture = $_POST['picture'];
$description = $_POST['description'];
$download = $_POST['download'];
$username = $_SESSION['username'];
include('config.php');
$query = mysql_query("INSERT INTO downloads VALUES ('null', '$name', '$picture', '$description', '$download', '$username')");
if($query){ 
echo "Your download have been addet, if you want to add more downloads please go to the <a href=add.php>add page</a>";
}
else {
echo "Sorry! Please try again later.";
}
} ?>
              </td>
            </tr>
          </table>
          </td>
      </tr>
      <tr>
        <td><div align="center">Powered by <a href="www.ice-host.net" target="_blank">Ice-Downloads</a></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
