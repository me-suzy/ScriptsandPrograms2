<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<div class=\"bottombox\" align=\"center\">";
if(($userdata["rights"]>3)||($shutdown<1))
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
echo "</div>";
echo "<hr><div class=\"copyright\" align=\"center\">";
echo "$copyright_url $copyright_note</div>\n";
if(($showcurrtime==1) || ($admin_rights>2))
{
	$displaytime=date("H:i");
	echo "<table class=\"timebox\" align=\"center\">";
	echo "<tr><td align=\"center\" class=\"timebox\">";
	if($showcurrtime==1)
	{
		echo "$l_currtime $displaytime";
		if($showtimezone==1)
		{
			echo "<br><span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
			$gmtoffset=tzgmtoffset($server_timezone);
			if($gmtoffset)
				echo " (".$gmtoffset.")";
			echo "</span><br>";
		}
	}
	if(($admin_rights>2) && (!$enable_htaccess))
		echo "$l_current_sessions: ".count_sessions()."(".$loginlimit.")</span>";
	echo "</td></tr></table>";
}
if(($user_loggedin) && ($shutdown==0))
{
	if($usemenubar==1)
	{
		if(!$alt_admmenu && (is_opera() || is_ns4() || is_gecko() || is_msie()))
		{
			if(($userdata["rights"]>3)||($shutdown<1))
			{
				include_once('./includes/coolmenu.inc');
				make_menu();
			}
		}
	}
	if($page=="settings")
	{
		echo "\n<div id=\"divDescription\" class=\"clDescriptionCont\">\n";
		echo "<!-- Empty div -->\n";
		echo "</div>\n";
		echo "<script type=\"text/javascript\">setPopup();</script>\n";
	}
}
?>
</body></html>