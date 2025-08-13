<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

include "../params.php";

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "../cssstyle.php"; ?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Products database","Discounts","Discount coupons","Shipping rates","Orders","Configure");
if ($https_adm_enabled=="Y")
	$taburls = array("https://$https_adm_location/main.php","https://$https_adm_location/discounts.php","https://$https_adm_location/disc_coupons.php","https://$https_adm_location/shipping.php","https://$https_adm_location/orders.php","https://$https_adm_location/config_edit.php");
else
	$taburls = array("http://$http_adm_location/main.php","http://$http_adm_location/discounts.php","http://$http_adm_location/disc_coupons.php","http://$http_adm_location/shipping.php","http://$http_adm_location/orders.php","http://$http_adm_location/config_edit.php");
$tabimages = array("","","","","","");
include "../tabs.php";
?>
<tr> 
<?
#echo "<td width=\"10%\" bgcolor=\"$cl_left_tab\" valign=\"top\" rowspan=\"2\">";
#include("cat.php");
#include("searchform.php");
#echo "</td>";
$c_result = mysql_query("select category, count(*) from products group by category");
$tabcount++;
?>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="500" valign="top"> 
<!-- main frame here --> 
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<center>

<div align="left">
<?
include "help.php";
echo <<<EOT
</div>
<div align="left"><font color="$cl_header" size="+1"><b>Edit Config</b></font><br></div>
<hr>
EOT;

echo "<form action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/config_update.php\" method=\"POST\">";
echo "<table border=\"0\" width=\"95%\">";
$c_result = mysql_query("select name, comment, value, field_order,type from config order by field_order");
while ($row = mysql_fetch_row($c_result)) {
	if (($row[3] % 10) == 0)
		echo "<tr><td colspan=\"2\"><hr></td></tr>";
	echo "<tr><td width=\"50%\" nowrap>\n";
	if($row[4]=="text") 
		echo "$row[1] : </td><td><input type=\"text\" maxlength=\"255\" size=\"40\" name=\"$row[0]\" value=\"$row[2]\"></td></tr>\n";
	elseif ($row[4]=="textarea")
		echo "$row[1] : </td><td><textarea name=\"$row[0]\" rows=\"5\" cols=\"40\">$row[2]</textarea></td></tr>\n";
}
mysql_free_result($c_result);
?>
</table>
<hr>
<font size="-1"><b><input type="submit" value="Update values"></b></font>
</form>

<hr>
</center>
</td>
</tr>
</table>
<!-- /main frame -->
</td>
<?
$dont_display_lc = 1;
include "../bottom.php";
?>
</body>
</html>
