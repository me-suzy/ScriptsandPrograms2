<?
include "header.php";
$pagetitle = "$sitename $split $txt_loggedout";
include "template.php";

echo "<font class=\"header\">$txt_loggedout</font><p/>

<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\"><p/>
$txt_logoutmsg<p/>
$txt_loginagain<p/>
</div></div></div>";

include "footer.php"; ?>