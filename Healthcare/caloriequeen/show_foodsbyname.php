<?
//start a session
session_start();

//validate user to see if they are allowed to be here
if ($_SESSION[valid] != "yes") {
   header("Location: http://website.com/food_menu.php");
   exit;

}
$db_name = "";
$table_name = "foodcomp";
$connection = @mysql_connect("localhost", "", "") 
	or die(mysql_error());
$db = @mysql_select_db($db_name, $connection) or die(mysql_error());
$sql = "SELECT id, food_name, food_category FROM $table_name ORDER BY food_name";
$result = @mysql_query($sql, $connection) or die (mysql_error());
$food_list = "<ul>";
	while ($row = mysql_fetch_array ($result)) {
$id = $row['id'];
$food_name = $row['food_name'];
$food_category = $row['food_category'];

$food_list .= "<li><a href=\"show_food.php?id=$id\">$food_name</a>";

}
$food_list .= "</ul>";
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Calorie Queen-Foods Listed By Name</title>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" background="images/thinbarbkg.gif">

<table cellspacing="0" cellpadding="0" border="0" height="66" width="100%" background="images/topbarbkg.gif">
  <tr valign="top"> 
    <td width="796" height="58"><img src="images/topbar.gif" width="758" height="133"></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" border="0" height="208" width="745">
  <tr> 
    <td width="149" height="2" valign="top"> 
      <div align="left"> 
        <table width="115" border="0" bgcolor="#009999" bordercolor="#009999" align="center" cellpadding="1" cellspacing="1">
          <tr>
            <td height="19"><b><font face="Arial" size="2" color="#FFFFFF">Search Foods</font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbyfood.php">
			<font color="#FFFFFF">By Name</font></a></font></font></b></td>
          </tr>
			<tr>
            <td height="2" bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2">
			<a href="searchbycategory.php"><font color="#FFFFFF">By Category</font></a></font></font></b></td>
          </tr>
			<tr>
            <td bordercolor="#000000" style="border: 1px solid #000000">
			<b><font face="Arial" size="2"><a href="food_menu.php">
			<font color="#FFFFFF">Admin</font></a></font></b></td>
          </tr>
          <tr bgcolor="#009999" valign="top"> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </div>
    </td>
    <td width="596" height="2" valign="top"> 
      <div align="left">
<form method="post" action="do_addfood.php">
<table cellspacing=2 cellpadding=1 width="578">
<tr>
<th><h3>Calorie Queen Admin</h3>
<p>Select a food from the list below, to view the food's record</p>
<? echo "$food_list"; ?><br><p><a href="food_menu.php">Return to main menu</a></p></font></th>
</tr>
</table>
</form>

</body>

</html>