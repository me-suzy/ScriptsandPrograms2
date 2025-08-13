<?
include "config.php";
include "mod.php";
include "processt.php";

if ($transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
}

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
	$gamount = r_secure($HTTP_POST_VARS["gamount"]);
	$purchaser = r_secure($HTTP_POST_VARS["purchaser"]);
	$recipient = r_secure($HTTP_POST_VARS["recipient"]);
	$message = r_secure($HTTP_POST_VARS["message"]);
	$remail = r_secure($HTTP_POST_VARS["remail"]);
	$passwd = r_secure($HTTP_POST_VARS["passwd"]);
} else {
	$firsttime = true;
	$fillerror = false;
	include "params.php";
}

?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: Send Gift Certificate</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "cssstyle.php" ?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Continue shopping","View cart","Order");
$taburls = array("http://$http_location/main.php","http://$http_location/cart.php",($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php".($transfer_cookie ? "?id=$id" : ""));
$tabimages = array("images/narrow.gif","images/minicart.gif","");
include "tabs.php";
?>
<tr> 
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include("login.php");
include("cat.php");
include("searchform.php");
include("help.php");
include "poweredby.php";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<center>
<?
if ($logged_in == "") {
	echo "<hr><b><font size=\"-1\"><font color=\"red\">You must be registered user to send Gift Certificates.</font><br>&nbsp;Please log in or&nbsp;&nbsp;<a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/register.php".($transfer_cookie ? "?id=$id" : "")."\">Register for free</a></font></b>";
} elseif ($gift_log) {
	echo "<hr><font size=\"3\" color=\"red\"><b><i>You're not allowed to order gift certificates as you are logged from gift certificate.</i></b></font>";
} else {
	$fillerror = empty($passwd);
	$result = mysql_query("select password from customers where userid='$id'");
	list($passwd_) = @mysql_fetch_row($result);
	$fillerror |= ($passwd_ != $passwd);
	mysql_free_result($result);
	$fillerror |= empty($gamount) || empty($remail) || ($gamount<$min_gamount) || ($gamount>$max_gamount);

	if (!$fillerror) {
		$cert = md5(uniqid(rand().getmypid()));
		$cert = r_secure($cert);

		$cart = md5(uniqid(rand().getmypid()));
		$cart = r_secure($cart);

		$status = "S";
		$a_date = date("Y-m-d",time());

		mysql_query("insert into giftcerts (cert, userid, cart, purchaser, recipient, remail, message, amount, status, a_date) values ('$cert', '$id', '$cart', '$purchaser', '$recipient', '$remail', '$message', '$gamount', '$status', '$a_date')") or die ("$mysql_error_msg");
		process_template_gift($mail_gift,$purchaser,$recipient,$gamount,$message,$remail,$cert);
		mail($remail,$mail_gift_subj,$mail_gift,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
echo "<hr>";
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/gift.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=gamount value="$gamount">
<input type=hidden name=purchaser value="$purchaser">
<input type=hidden name=recipient value="$recipient">
<input type=hidden name=message value="$message">
<input type=hidden name=remail value="$remail">
<input type=hidden name=mode value="anotherone">
<font size="3"><b><i>Your gift certificate was successfully sent!&nbsp;&nbsp;&nbsp;</i></b></font>
<font size="-1"><b><input type=submit value="Send another one"></b></font>
</form>
EOT;
	} else {
		if ($firsttime) {
			if ($mode != "anotherone")
			echo <<<EOT
<div align="left">
<font color="$cl_header" size="+1"><b><img src="images/gift.gif" width="84" height="69" align="center">&nbsp;&nbsp;Gift Certificates</b></font>
</div>
<hr>
<font size="-1">
Gift certificates are the perfect solution when you just can't seem to find the right gift or you've waited till the last minute.Gift certificates make the perfect present for friends, family, and business recipients.
</font>
EOT;
		} else {
			echo "<div align=\"left\"><font size=\"3\" color=\"red\"><b><i>";
			if ($passwd_ != $passwd || empty($passwd))
				echo "Invalid password<br>";
			if (empty($gamount))
				echo "You forgor to enter amount for gift certificate<br>";
			elseif ($gamount<$min_gamount)
				echo "Gift certificate amount is less than minimum amount<br>";
			elseif ($gamount>$max_gamount)
				echo "Gift certificate amount is more than maximum amount<br>";
			if (empty($remail))
				echo "You forgor to enter recipient's e-mail address<br>";
			echo "</i></b></font></div>";
		}
echo "<hr>";
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/gift.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<table width=100% cellpadding=5 cellspacing=0 border=0>
<tr><td width=100% valign=top colspan=2>
<font size="+2"><b>1.</b></font> <b>Who are you sending this to?</b><br> 
<font size="-1">
We'll include the sender's name, the recipient's name, and a message on the gift certificate. 
</font>
</td></tr>
<tr><td width=10% valign=middle align=right>
From:</td> 
<td width=75% valign=middle align=left>
<input type=text name=purchaser size=30 maxlength=50 value="$purchaser"> <font size=-1>(optional)</font></td></tr>
<tr><td width=10% valign=middle align=right>
To:</td>
<td width=75% valign=middle align=left>
<input type=text name=recipient size=30 maxlength=50 value="$recipient"> <font size=-1>(optional)</font></td></tr>
<tr>
<td width=100% valign=middle colspan=2>
<font size="+2"><b>2.</b></font> <b>Add a message.</b><br>
<font size="-1">
Please type the message you would like to appear on the gift certificate: (optional)</font>
</td>
</tr>
<tr><td width=10% valign=top align=right>Message:</td> 
<td width=75% valign=middle align=left>
<textarea rows="4" cols="50" wrap="virtual" name=message>$message</textarea>
</td></tr>
<tr>
<td width=100% valign=middle colspan=2>
<font size="+2"><b>3.</b></font> <b>Choose an amount. </b><br>
<font size="-1">
Specify the amount in both dollars and cents, or just in dollars.
</font>
</td>
</tr>
<tr><td width=10% valign=middle align=right>$</td>
<td width=75% valign=middle align=left>
<input type=text name=gamount size=12 maxlength=12 value="$gamount"> <font size=-1>(minimum is \$$min_gamount, maximum is \$$max_gamount)</font></td></tr>
<tr>
<td width=100% valign=middle colspan=2>
<font size="+2"><b>5.</b></font> <b>Enter the e-mail address of whom you're sending a Gift Certificate.</b>
</td>
</tr>
<tr>
<td width=10% valign=middle align=right>
&nbsp;
</td>
<td width=75% valign=middle align=left>
<input type=text size="20" value="$remail" name=remail></textarea>
</td>
</tr>
</table>
<hr>
<font size="-1">Password confirmation: </font><input type="password" name="passwd" size="16" maxlength="32">&nbsp;&nbsp;&nbsp;&nbsp;
<font size="-1"><b>
<input type="submit" value="Send Gift Certificate!"></b></font>
</form>
EOT;
	}
}
?>
<hr>
</center>
</td>
</tr>
</table>
</td>
<?
include "bottom.php";
?>
</body>
</html>
