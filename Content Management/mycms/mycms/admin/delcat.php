<?

include("conn.php");

$sql = "SELECT * FROM menu where cat = '$name' and deleted = '0'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$icount = mysql_num_rows($query);

if ($icount > 0) {

echo "<table width= '100%' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td height='52' align = 'center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Category cannot be deleted, please delete menu links first then return if you wish to delete.</font><br>
	</td>
  </tr>
  <tr>
    <td valign='bottom'>
      <form method='post'>
    <div align='center' valign = 'middle'>
          <input type='button' value='Close window' onclick='window.close()'>
        </div>
  </form>
	</td>
	</tr>
	</table>";


} else {

if(!$Submit){

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Delete</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body onunload="opener.location=('catitems.php')">


<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="52" align = 'center'>Are you sure you want to delete<br>
	<i><?=$name?></i>
	</td>
  </tr>
  <tr>
    <td valign="bottom">
      <form method='post'>
    <div align='center' valign = 'middle'>
          <input type='button' value='No &gt;&gt;' onclick='window.close()'>
        </div>
  </form>
	</td>
  </tr>
  <tr>
    <td align = 'center' valign="top">
<form name="form1" method="post" action="delcat.php">
        <input type="submit" name="Submit" value="Yes &gt;&gt;">
        <input type="hidden" name="id" value="<?=$id?>">

      </form></td>
  </tr>
</table>
</body>
</html>
<?


} else {

if($Submit){

$sql7 = "delete FROM category where id = '$id'";
$query7 = mysql_query($sql7) or die("Cannot query the database.<br>" . mysql_error());

echo "<table width= '100%' border='0' cellpadding='0' cellspacing='0'>
  <tr> 
    <td height='52' align = 'center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Your catgory have been removed</font><br>
	</td>
  </tr>
  <tr> 
    <td valign='bottom'> 
      <form method='post'>
    <div align='center' valign = 'middle'>
          <input type='button' value='Close window' onclick='window.close()'>
        </div>
  </form>
	</td>
	</tr>
	</table>";




}
}
}
?>
