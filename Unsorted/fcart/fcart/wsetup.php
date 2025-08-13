<?
include "config.php";
include "mod.php";

if ($REQUEST_METHOD=="POST") {
	$name="Message from: ".r_secure($HTTP_POST_VARS["name"]);
	$email="E-mail: ".r_secure($HTTP_POST_VARS["email"]);
	$about="About: ".r_secure($HTTP_POST_VARS["about"]);
	$message="Message:\n".r_secure($HTTP_POST_VARS["message"])."\n\n--\nwww.fcart.com";
	mail($support_email, "Message from www.FCART.com",$name."\n".$email."\n".$about."\n".$message,"From: $email\nReply-To: $email\nX-Mailer: PHP/".phpversion());
}
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?></title>
<? include "cssstyle.php" ?>
<style>
body { background-repeat: repeat-y }
</style>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>" background="images/fon.jpg">
<?
include "top.php";
$tabnames = array("F-Cart shopping system","How to setup your on-line store?");
$taburls = array("http://$http_location/welcome.php","http://$http_location/wsetup.php");
$tabimages = array("","");
include "tabs.php";
?>
<tr> 
<? $tabcount++ ?>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<!-- main frame here -->
<table border="0" width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<hr>
<?
if ($REQUEST_METHOD!="POST") {
echo <<<EOT
<h3>Setting up your own FCart-based web store.</h3>
Installation and customization of on-line store based on F-Cart shopping system costs <b>$950</b>.<br>
The price includes lifelong support and discounts for future additions and updates.
<p>
EOT;
echo "With questions concerning ordering and installation of F-Cart shopping system,<br>please contact us by sending e-mail to <a href=\"mailto:".ereg_replace(".*<","",ereg_replace(">.*","",$support_email))."\">".ereg_replace(".*<","",ereg_replace(">.*","",$support_email))."</a>";
echo <<<EOT
 or by filling the contact form:
<form action="$PHP_SELF" method="post">
<table>
<tr><td><b>Your name:</b>&nbsp;&nbsp;</td><td><input type="text" name="name" size="50"></td></tr>
<tr><td><b>Your e-mail:</b>&nbsp;&nbsp;</td><td><input type="text" name="email" size="50"></td></tr>
<tr><td><b>About you:</b>&nbsp;&nbsp;</td><td><select name="about">
<option value="Potential F-Cart customer" selected>I'm a potential F-Cart customer</option>
<option value="FCart-based web store administrator">I'm FCart-based web store administrator</option>
<option value="I want to know more about F-Cart">I want to know more about F-Cart</option>
</select>
</td></tr>
<tr><td valign="top"><b>Message:</b>&nbsp;&nbsp;</td><td><textarea cols="50" rows="5" name="message"></textarea>
</td></tr>
<tr><td>&nbsp;</td><td><input type="submit" value="&nbsp;&nbsp;Send&nbsp;&nbsp;"></td></tr>
</table>
</form>
EOT;
} else {
echo <<<EOT
Thank you for your interest in F-Cart shopping system.
EOT;
}
?>
<hr>
</td>
</tr>
</table>
<!-- /main frame -->
</td>
<?
$dont_display_lc = 1;
include "bottom.php";
?>
</body>
</html>
