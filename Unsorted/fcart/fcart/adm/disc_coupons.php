<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";
include "../processt.php";

if ($REQUEST_METHOD == "POST" && $mode != "anotherone") {
	$firsttime=false;
	$category=$default_category;
	if (strlen($HTTP_POST_VARS["category"]) > 0)
		$category = r_secure($HTTP_POST_VARS["category"]);
	if ($category == "All")
		$category=$default_category;
	$sortby = $default_sortby;
	if (strlen($HTTP_POST_VARS["sortby"]) > 0)
		$sortby = r_secure($HTTP_POST_VARS["sortby"]);
	if (strlen($HTTP_POST_VARS["first"]) > 0)
		$first = r_secure($HTTP_POST_VARS["first"]);
	else
		$first = 1;
	$recipient = r_secure($HTTP_POST_VARS["recipient"]);
	$disc_discount = r_secure($HTTP_POST_VARS["disc_discount"]);
	$disc_type = r_secure($HTTP_POST_VARS["disc_type"]);
	$disc_count = r_secure($HTTP_POST_VARS["disc_count"]);
	$day = r_secure($HTTP_POST_VARS["day"]);
	$month = r_secure($HTTP_POST_VARS["month"]);
	$year = r_secure($HTTP_POST_VARS["year"]);
	if ($safe_admin && $recipient=="") safe_mode_msg(true);
	$fillerror = empty($disc_discount) || empty($disc_type) || empty($disc_count) || empty($day) || empty($month) || empty($year);
} else {
	$firsttime=true;
	$fillerror=true;
	if ($mode != "anotherone") {
		$disc_count = 1;
		$date = mktime(0,0,0,date("m")+1,date("d"),date("Y"));
		$day = date("d",$date);
		$month = date("m",$date);
		$year = date("Y",$date);
	}
	include "../params.php";
}

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
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include("cat.php");
include("searchform.php");
echo "<center>";
include("help.php");
echo "</center>";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="500" valign="top"> 
<!-- main frame here --> 
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<center>
<hr>
<!--                                                -->
<?
echo "<form method=\"POST\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/disc_coupons.php\">";
if (!$fillerror) {
	$recipients = array();
	if (empty($recipient)) {
		$result = mysql_query("select email from customers");
		while ($row = mysql_fetch_row($result))
			array_push($recipients, $row[0]);
		@mysql_free_result($result);
	} else
		array_push($recipients, $recipient);

	for (;$k = current($recipients);next($recipients)) {
		$coupon = md5(uniqid(rand().getmypid()));

		mysql_query("insert into discount_coupons (coupon,discount,type,count,expire) values ('$coupon','$disc_discount','$disc_type','$disc_count','$year-$month-$day')") or die ("$mysql_error_msg");
		$mdc = $mail_disc_coupon;
		$mdcs = $mail_disc_coupon_subj;
		process_template_coupon($mdc,$coupon,$recipient,$disc_discount,$disc_type,$disc_count,$day,$month,$year);
		process_template_coupon($mdcs,$coupon,$recipient,$disc_discount,$disc_type,$disc_count,$day,$month,$year);
		mail($k,$mdcs,$mdc,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
		$mail_count++;
	};
	echo <<<EOT
<input type=hidden name=recipient value="$recipient">
<input type=hidden name=disc_discount value="$disc_discount">
<input type=hidden name=disc_type value="$disc_type">
<input type=hidden name=disc_count value="$disc_count">
<input type=hidden name=day value="$day">
<input type=hidden name=month value="$month">
<input type=hidden name=year value="$year">
<input type=hidden name=mode value="anotherone">
<font size="3"><b><i>
EOT;
	echo empty($recipient) ? ($mail_count > 0 ? "$mail_count discount coupons were sent!" : "No users in database!") : 'Discount coupon was successfully sent!';
	echo <<<EOT
&nbsp;&nbsp;&nbsp;</i></b></font>
<font size="-1"><b><input type=submit value="Send another one"></b></font>
</form>
EOT;
} else {
	if (!$firsttime) {
		echo "<div align=\"left\"><font size=\"3\" color=\"red\"><b><i>";
		if (empty($recipient))
			echo "You forgor to enter recipient<br>";
		if (empty($disc_discount))
			echo "You forgor to enter discount value<br>";
		if (empty($disc_type))
			echo "You forgor to enter discount type<br>";
		if (empty($disc_count))
			echo "You forgor to enter count<br>";
		if (empty($day) || empty($month) || empty($year))
			echo "You forgor to enter expiration date<br>";
		echo "</i></b></font></div>";
	}
?>
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td colspan="2" height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Send discount coupon</i></font></b></center>
</td>
</tr>
<?
include "send_disc.php";
?>
</table>
</td>
</tr>
</table>
<br>
<font size="-1"><b>
<input type="submit" value="Submit">
</b></font>
<?
}
?>
</form>
<!--                                                -->
<hr>
</center>
</td>
</tr>
</table>
<!-- /main frame -->
</td>
<?
include "../bottom.php";
?>
</body>
</html>
