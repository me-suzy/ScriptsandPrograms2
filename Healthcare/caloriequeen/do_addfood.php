<?
if ((!$_POST[food_name]) || (!$_POST[food_category])) {
	header("Location: http://website.com/show_addfood.php");
   exit;
} else {
	
	session_start();
}

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

//start creating the SQL statement
$sql = "INSERT INTO $table_name
(id, food_name, food_category, serving_size, gmwt1, gmwt_desc1, gmwt2, gmwt_desc2, calories, total_fat, saturated_fat, trans_fat, carbohydrates, dietary_fiber, sugar, protein, cholesterol, sodium) VALUES ('','$_POST[food_name]', '$_POST[food_category]','$_POST[serving_size]','$_POST[gmwt1]','$_POST[gmwt_desc1]','$_POST[gmwt2]','$_POST[gmwt_desc2]','$_POST[calories]','$_POST[total_fat]','$_POST[saturated_fat]',

'$_POST[trans_fat]','$_POST[carbohydrates]','$_POST[dietary_fiber]','$_POST[sugar]','$_POST[protein]','$_POST[cholesterol]','$_POST[sodium]')";

$result = @mysql_query($sql, $connection) or die (mysql_error());

?>
<html>
<head>

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
<h3 align="center">Add A Food-Food Added</h3>
<p align="center">The following information has been added to <? "echo $table_name"; ?></p>
<form method="post" action="http://nafwa.org/and/do_addfood.php">
<div align="center">
<table cellspacing=0 cellpadding=0 width="364">
<tr>
<th width="233">Food Information</th>
</tr>
<tr>
<td valign=top width="233">
<p><strong>Food Name:</strong><? echo "$_POST[food_name]"; ?><br>
<strong>Food Category:</strong><? echo "$_POST[food_category]"; ?><br>
<strong>Serving Size:</strong><? echo "$_POST[serving_size]"; ?><br>
<strong>GmWt1:</strong><? echo "$_POST[gmwt1]"; ?><br>
<strong>GmWt_Desc1:</strong><? echo "$_POST[gmwt_desc1]"; ?><br>
<strong>GmWt2:</strong><? echo "$_POST[gmwt2]"; ?><br>
<strong>GmWt_Desc2:</strong><? echo "$_POST[gmwt_desc2]"; ?><br>
<strong>Calories:</strong><? echo "$_POST[calories]"; ?><br>
<strong>Total Fat:</strong><? echo "$_POST[total_fat]"; ?><br>
<strong>Saturated Fat:</strong><? echo "$_POST[saturated_fat]"; ?><br>
<strong>Carbohydrates:</strong><? echo "$_POST[carbohydrates]"; ?><br>
<strong>Dietary Fiber:</strong><? echo "$_POST[dietary_fiber]"; ?><br>
<strong>Sugar:</strong><? echo "$_POST[sugar]"; ?><br>
<strong>Protein:</strong><? echo "$_POST[protein]"; ?><br>
<strong>Cholesterol:</strong><? echo "$_POST[cholesterol]"; ?><br>
<strong>Sodium:</strong><? echo "$_POST[sodium]"; ?><br>
</p>
</td>
</tr>
<tr>
<td align=center><br>
<a href="food_menu.php">Return to Main Menu</a>
</td>
</tr>
</table>
</div>
</form>



		<p>

        
        <br>
      </div>
    </tr>
</table>
</body>
</html>