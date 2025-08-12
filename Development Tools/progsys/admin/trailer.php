<?php
echo "<div class=\"bottombox\" align=\"center\">";
if(($userdata["rights"]>2)||($shutdown<1))
	echo "<a href=\"".do_url_session("index.php?lang=$lang")."\">$l_mainmenu</a><br>";
$usermode=$l_admin_rights[$admin_rights];
if($user_loggedin)
{
	echo "$l_loggedinas <i>".$userdata["username"]."</i> ($usermode)&nbsp;&nbsp;";
	if($enable_htaccess)
		echo "<a href=\"javascript:alert('$l_notavail_htaccess2')\">";
	else 
		echo "<a href=\"".do_url_session("logout.php?lang=$lang")."\">";
	echo "$l_logout</a><br>";
	$displaytime=date("H:i");
	echo "<table class=\"timebox\" align=\"center\">";
	echo "<tr><td align=\"center\" class=\"timebox\">";
	echo "$l_currtime $displaytime";
	echo "<br><span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span>";
	if(($admin_rights>2) && (!$enable_htaccess))
		echo "<br>$l_current_sessions: ".count_sessions()."(".$loginlimit.")</span>";
	echo "</td></tr></table>";
	echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"./wz_tooltip.js\"></script>";
}
echo "</div><hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
?>
</body></html>