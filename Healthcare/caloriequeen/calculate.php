<?
$db_name = "";
$table_name = "foodcomp";
$connection = @mysql_connect("localhost", "", "") 
	or die(mysql_error());
$db = @mysql_select_db($db_name, $connection) or die(mysql_error());
$chk_id = "SELECT id FROM $table_name WHERE id = '$_GET[id]'";
$chk_id_res = @mysql_query($chk_id, $connection) or die (mysql_error());
$chk_id_num = mysql_num_rows($chk_id_res);
	if ($chk_id_num != 1) {
	header("Location: http://website.com/20.htm");
    exit;

} else {

$sql = "SELECT * FROM $table_name WHERE id = '$_GET[id]'";
$result = @mysql_query($sql, $connection) or die (mysql_error());
	while ($row = mysql_fetch_array ($result)) {
$food_name = $row['food_name'];
$serving_size = $row['serving_size'];
$gmwt1 = $row['gmwt1'];
$gmwt_desc1 = $row['gmwt_desc1'];
$gmwt_desc2 = $row['gmwt_desc2'];
$calories = $row['calories'];
$total_fat = $row['total_fat'];
$saturated_fat = $row['saturated_fat'];
$carbohydrates = $row['carbohydrates'];
$dietary_fiber = $row['dietary_fiber'];
$sugar = $row['sugar'];
$protein = $row['protein'];
$cholesterol = $row['cholesterol'];
$sodium = $row['sodium'];
$calories2 = round($calories*$gmwt1/100);
$total_fat2 = round($total_fat*$gmwt1/100);
$saturated_fat2 = round($saturated_fat*$gmwt1/100);
$carbohydrates2 = round($carbohydrates*$gmwt1/100);
$dietary_fiber2 = round($dietary_fiber*$gmwt1/100);
$sugar2 = round($sugar*$gmwt1/100);
$protein2 = round($protein*$gmwt1/100);
$cholesterol2 = round($cholesterol*$gmwt1/100);
$sodium2 = round($sodium*$gmwt1/100);
$option_block .= "<a href=\"calculate.php?id=$id\">$gmwt_desc1</a><br><a href=\"calculate2.php?id=$id\">$gmwt_desc2</a>";
		}
}
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

       <form name="form" action="search.php" method="get">
			<p align="right"><b><font size="2" face="Arial">Search Foods By Name:</font></b> 
<input type="text" name="id" size="40" /> <input type="submit" name="Submit" value="Search" /></p>
		</form>
		<table border="0" width="100%" id="table1">
			<tr>
				<td align="center"><b><font face="Arial" size="4"><? echo "$food_name"; ?></font></b></p>
				     
				<p align="center">Serving Size</font><?php echo "$display_block"; ?><br>
&nbsp;<? echo "$option_block"; ?></p>
				<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table2">
					<tr>
						<td width="180">&nbsp;</td>
						<td width="307">
<table border="0" width="36%" id="table3" cellspacing="0" cellpadding="0" style="border: 2px solid #000000; font-family:Arial; font-size:12pt; color:#FF0000; font-weight:bold">
	<tr>
		<td>
		<p align="center">
		<img border="0" src="images/labeltop.gif" width="300" height="54"></td>
	</tr>
	<tr>
		<td width="94%" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Serving Size</font><?php echo "$gmwt_desc1"; ?></td>
	</tr>
	<tr>
		<td width="94%" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<img border="0" src="images/labelbottom.gif" width="300" height="19"></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000" size="2">Amount Per Serving</font></td>
	</tr>
	<tr>
		<td width="93%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; border-bottom: 1px solid #000000">
		<font color="#000000">Calories</font>&nbsp;<? echo "$calories2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td width="93%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Total Fat</font>&nbsp;<? echo "$total_fat2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		&nbsp;&nbsp;&nbsp;&nbsp; <font color="#000000">Saturated Fat</font>&nbsp;<? echo "$saturated_fat2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Cholesterol</font>&nbsp;<? echo "$cholesterol2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Sodium</font>&nbsp;<? echo "$sodium2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Total Carbohydrate</font>&nbsp;<? echo "$carbohydrates2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		&nbsp;&nbsp;&nbsp;&nbsp; <font color="#000000">Dietary Fiber</font>&nbsp;<? echo "$dietary_fiber2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		&nbsp;&nbsp;&nbsp;&nbsp; <font color="#000000">Sugars</font>&nbsp;<? echo "$sugar2"; ?></td>
	</tr>
	<tr>
		<td width="98%" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top: 1px solid #000000; border-bottom: 1px solid #000000">
		<font color="#000000">Protein</font>&nbsp;<? echo "$protein2"; ?></td>
	</tr>
	<tr>
		<td width="84%">
		<img border="0" src="images/labelbottom.gif" width="300" height="19"></td>
	</tr>
</table>
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
<br>
 <br>

<p>&nbsp;</p>
        <br>
        <br>
      </div>
    </tr>
</table>
</body>
</html>