<?
if (!$_GET[id]) {
	header("Location: http://website.com/food_menu.php");
   exit;
} else {
	
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

$sql = "SELECT * FROM $table_name WHERE id = '$_GET[id]'";
$result = @mysql_query($sql, $connection) or die (mysql_error());
	while ($row = mysql_fetch_array ($result)) {
$food_name = $row['food_name'];
$food_category = $row['food_category'];
$serving_size = $row['serving_size'];
$gmwt1 = $row['gmwt1'];
$gmwt_desc1 = $row['gmwt_desc1'];
$gmwt2 = $row['gmwt2'];
$gmwt_desc2 = $row['gmwt_desc2'];
$calories = $row['calories'];
$total_fat = $row['total_fat'];
$saturated_fat = $row['saturated_fat'];
$trans_fat = $row['trans_fat'];
$carbohydrates = $row['carbohydrates'];
$dietary_fiber = $row['dietary_fiber'];
$sugar = $row['sugar'];
$protein = $row['protein'];
$cholesterol = $row['cholesterol'];
$sodium = $row['sodium'];
		}
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Foods Listed By Name</title>
</head>

<body>
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
<th align="left"><h3 align="center">Calorie Queen Admin</h3>
<h2>Food Details for <? echo "$food_name"; ?></h2>

<p><strong>Food Category:</strong> <? echo "$food_category"; ?><br>
		
<strong>Serving Size:</strong> <? echo "$serving_size"; ?><br>
	
<font face="Times New Roman">
<strong>GmWt1:</strong><? echo "$gmwt1"; ?><br>
<strong>GmWt_Desc1:</strong><? echo "$gmwt_desc1"; ?><br>
<strong>GmWt2:</strong><? echo "$gmwt2"; ?><br>
<strong>GmWt_Desc2:</strong><? echo "$gmwt_desc2"; ?><br>
<strong>Calories:</strong> <? echo "$calories"; ?><br>
	
<strong>Total Fat:</strong> <? echo "$total_fat"; ?><br>
	
<strong>Saturated Fat:</strong> <? echo "$saturated_fat"; ?><br>
	
<strong>Trans-Fat:</strong> <? echo "$trans_fat"; ?><br>

<strong>Carbohydrates:</strong> <? echo "$carbohydrates"; ?><br>

<strong>Dietary Fiber:</strong> <? echo "$dietary_fiber"; ?><br>

<strong>Sugar:</strong> <? echo "$sugar"; ?><br>

<strong>Protein:</strong> <? echo "$protein"; ?><br>
	
<strong>Cholesterol:</strong> <? echo "$cholesterol"; ?><br>
	
<strong>Sodium:</strong> <? echo "$sodium"; ?></p>

<p><a href="food_menu.php">Return to Main Menu</a></p>
</tr>
</table>
</form>

</body>

</html>

		<p>

        
        <br>
      </div>
    </tr>
</table>
</body>
</html>