<?
include "protection.php";
include "header.php";
$pagetitle = "$sitename $split $txt_loggedin";
include "template.php";

echo "<font class=\"header\">$txt_loggedin</font><p/>

<div id=\"central\"><div class=\"boxes\"><div class=\"boxbot\"><p/>
$txt_thanks <i>$loginname</i>. $txt_cont<p/>
</div></div></div>";

include "footer.php";

?>