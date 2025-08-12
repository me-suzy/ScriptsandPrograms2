<?

include("conn.php");



if($type == "home") {


 echo" <table width= '100%' border='0' cellpadding='0' cellspacing='0'>
  <tr> 
    <td height='52' align = 'center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>You cannot delete the home page</font><br>
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








}else {



if(isset($Submit)) {

$sql6 = "UPDATE menu SET deleted ='1' WHERE id='$id'";
$query6 = mysql_query($sql6) or die("Cannot update record.<br>" . mysql_error());


echo "<table width= '100%' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td height='52' align = 'center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Your page have been removed</font><br>
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


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Delete</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body onunload="opener.location=('mainf.php')">


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
<form name="form1" method="post" action="delete.php">
        <input type="submit" name="Submit" value="Yes &gt;&gt;">
        <input type="hidden" name="id" value="<?=$id?>">
		 <input type="hidden" name="type" value="<?=$type?>">
      </form></td>
  </tr>
</table>
</body>
</html>
<?
}

}
?>
