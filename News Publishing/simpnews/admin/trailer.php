<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<div class=\"bottombox\" align=\"center\">\n";
if(($userdata["rights"]>2)||($shutdown<1))
	echo "<a href=\"".do_url_session("index.php?$langvar=$act_lang")."\">$l_mainmenu</a><br>";
$usermode=$l_admin_rights[$admin_rights];
if($user_loggedin)
{
	echo "$l_loggedinas <i>".$userdata["username"]."</i> ($usermode)&nbsp;&nbsp;";
	if($enable_htaccess)
		echo "<a href=\"javascript:alert('$l_notavail_htaccess2')\">";
	else
		echo "<a href=\"".do_url_session("logout.php?$langvar=$act_lang")."\">";
	echo "$l_logout</a><br>";
}
echo "</div>\n<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>\n";
if($user_loggedin)
{
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$displaytime=date($l_admdateformat,$acttime);
	echo "<table class=\"timebox\" align=\"center\">\n";
	echo "<tr><td align=\"center\" class=\"timebox\">";
	echo "$l_timeonserver: $displaytime";
	echo "<br><span class=\"timezone\">$l_timezone_note ".timezonename($displaytimezone);
	$gmtoffset=tzgmtoffset($displaytimezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span>";
	if(($admin_rights>2) && (!$enable_htaccess))
		echo "<br>$l_current_sessions: ".count_sessions()."(".$loginlimit.")</span>";
	echo "</td></tr></table>\n";
	if(($usemenubar==1) && ($shutdown<1))
	{
		if($usenewmenu)
		{
			include_once('./includes/coolmenu.inc');
			make_menu();
		}
	}
}
?>
</body></html>