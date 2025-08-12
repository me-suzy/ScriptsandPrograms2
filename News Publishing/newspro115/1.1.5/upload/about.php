<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();
$USER = unp_getUser();
unp_getsettings();
// +------------------------------------------------------------------+
// | Get Information                                                  |
// +------------------------------------------------------------------+
$getdbsize = $DB->query("SHOW TABLE STATUS");
$dbsize = 0;
$dbspace = array();
$dbperc = array();
while ($dbdata = $DB->fetch_array($getdbsize))
{
	$dbsize = $dbsize + $dbdata['Data_length'] + $dbdata['Index_length'];
	if ($dbdata['Name'] == 'unp_template')
	{
		$dbspace['templates'] = $dbdata['Data_length'] + $dbdata['Index_length'];
	}
	if ($dbdata['Name'] == 'unp_news')
	{
		$dbspace['news'] = $dbdata['Data_length'] + $dbdata['Index_length'];
	}
	if ($dbdata['Name'] == 'unp_comments')
	{
		$dbspace['comments'] = $dbdata['Data_length'] + $dbdata['Index_length'];
	}
}	
$dbsize = $dbsize / 1024; // convert database size to KB from bytes
$dbsize = round($dbsize, 2);

$dbspace['templates'] = $dbspace['templates'] / 1024;
$dbspace['news'] = $dbspace['news'] / 1024;
$dbspace['comments'] = $dbspace['comments'] / 1024;

$dbspace['templates'] = round($dbspace['templates'], 2);
$dbspace['news'] = round($dbspace['news'], 2);
$dbspace['comments'] = round($dbspace['comments'], 2);

$dbspace['other'] = $dbsize - $dbspace['templates'] - $dbspace['news'] - $dbspace['comments'];

$dbperc['templates'] = (($dbspace['templates'] / $dbsize) * 100);
$dbperc['news'] = (($dbspace['news'] / $dbsize) * 100);
$dbperc['comments'] = (($dbspace['news'] / $dbsize) * 100);

$dbperc['templates'] = round($dbperc['templates'],2);
$dbperc['news'] = round($dbperc['news'],2);
$dbperc['comments'] = round($dbperc['comments'],2);

$dbperc['other'] = 100 - $dbperc['templates'] - $dbperc['news'] - $dbperc['comments'];

$totalnews = $DB->query("SELECT COUNT(*) AS NumNews FROM `unp_news`");
$totalnews2 = $DB->fetch_array($totalnews);
$totalnewsnum = $totalnews2['NumNews'];

$totalcomments = $DB->query("SELECT COUNT(*) AS NumComm FROM `unp_comments`");
$totalcomments2 = $DB->fetch_array($totalcomments);
$totalcommentsnum = $totalcomments2['NumComm'];

$totalusers = $DB->query("SELECT COUNT(*) AS NumUsers FROM `unp_user`");
$totalusers2 = $DB->fetch_array($totalusers);
$totalusersnum = $totalusers2['NumUsers'];

$totaladmins = $DB->query("SELECT COUNT(*) AS NumAdmins FROM `unp_user` WHERE groupid='1'");
$totaladmins2 = $DB->fetch_array($totaladmins);
$totaladminsnum = $totaladmins2['NumAdmins'];

$mysqlversion = $DB->query("SELECT VERSION() AS version");
$mysqlversion2 = $DB->fetch_array($mysqlversion);
$mysqlVersion = $mysqlversion2['version'];

require('news.inc.php');

// +------------------------------------------------------------------+
// | Process Page                                                     |
// +------------------------------------------------------------------+

include('header.php');
unp_openBox();
echo '
<center>
<strong><span class="largefont">Utopia News Pro</span></strong><br />
</center>
<table cellpadding="4" cellspacing="0" border="0" width="100%" style="border: 1px solid black">
<tr>
	<td bgcolor="#6384B0" colspan="4" style="border-bottom: 1px solid black"><strong><span class="tblheadtxt">General Information</span></strong></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Total News Posts</strong></td>
	<td class="alt2" width="25%" align="left">'.$totalnewsnum.'</td>
	<td class="alt1" width="25%" align="left"><strong>Total Comments</strong></td>
	<td class="alt2" width="25%" align="left">'.$totalcommentsnum.'</td>

</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Total Users</strong></td>
	<td class="alt2" width="25%" align="left">'.$totalusersnum.'</td>
	<td class="alt1" width="25%" align="left"><strong>Total Administrators</strong></td>
	<td class="alt2" width="25%" align="left">'.$totaladminsnum.'</td>

</tr>
</table>

<br />
<table cellpadding="4" cellspacing="0" border="0" width="100%" style="border: 1px solid black">
<tr>
	<td bgcolor="#6384B0" colspan="4" style="border-bottom: 1px solid black"><strong><span class="tblheadtxt">Server Information</span></strong></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Server Type</strong></td>
	<td class="alt2" width="25%" align="left">PHP '.phpversion().' / MySQL '.$mysqlVersion.'</td>
	<td class="alt1" width="25%" align="left"><strong>Total Database Size</strong></td>
	<td class="alt2" width="25%" align="left">'.$dbsize.' KB</td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Total Comments Use</strong></td>
	<td class="alt2" width="25%" align="left">'.$dbspace['comments'].' KB / '.$dbperc['comments'].'%</td>
	<td class="alt1" width="25%" align="left"><strong>Total News Use</strong></td>
	<td class="alt2" width="25%" align="left">'.$dbspace['news'].' KB / '.$dbperc['news'].'%</td>

</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Total Template Use</strong></td>
	<td class="alt2" width="25%" align="left">'.$dbspace['templates'].' KB / '.$dbperc['templates'].'%</td>
	<td class="alt1" width="25%" align="left"><strong>Other Database Use</strong></td>
	<td class="alt2" width="25%" align="left">'.$dbspace['other'].' KB / '.$dbperc['other'].'%</td>
</tr>
</table>

<br />
<table cellpadding="4" cellspacing="0" border="0" width="100%" style="border: 1px solid black">
<tr>
	<td bgcolor="#6384B0" colspan="4" style="border-bottom: 1px solid black"><strong><span class="tblheadtxt">UNP Information</span></strong></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Version</strong></td>
	<td class="alt2" width="25%" align="left">'.$version.' [<a href="javascript:void(0)" onClick=\'open("http://www.utopiasoftware.net/newspro/chkupd.php?version='.str_strip('.',$version).'&amp;versionpds='.$version.'","Update","width=450, height=180, top=20,left=20,scrollbars=no, status=no, toolbar=no, menubar=no")\'>Check For Update</a>]</td>
	<td class="alt1" width="25%" align="left"><strong>MySQL Driver Version</strong></td>
	<td class="alt2" width="25%" align="left">'.$mysqlClassVersion.'</td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>News Parser Version</strong></td>
	<td class="alt2" width="25%" align="left">'.$newsParserVersion.'</td>
	<td class="alt1" width="25%" align="left"><strong>Register UNP</strong></td>
	<td class="alt2" width="25%" align="left"><a href="javascript:void(0)" onClick=\'open("http://www.utopiasoftware.net/newspro/registersite.php?siteurl='.$siteurl.'&amp;unpurl='.$unpurl.'&amp;sitename='.$sitetitle.'","Register","width=450, height=180, top=20,left=20,scrollbars=no, status=no, toolbar=no, menubar=no")\'>Register Your Site</a></td>

</tr>
</table>

<br />
<table cellpadding="4" cellspacing="0" border="0" width="100%" style="border: 1px solid black">
<tr>
	<td bgcolor="#6384B0" colspan="4" style="border-bottom: 1px solid black"><strong><span class="tblheadtxt">Product Information</span></strong></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Software Developed By</strong></td>
	<td class="alt1" width="85%" align="left"><a href="http://www.utopiasoftware.net/" target="_blank">Utopia Software</a></td>
</tr>
<tr valign="top" align="center">
	<td class="alt2" width="25%" align="left"><strong>Developers</strong></td>
	<td class="alt2" width="85%" align="left"><a href="http://www.utopiasoftware.net/forum/index.php?s=&amp;act=Profile&amp;CODE=03&amp;MID=1" target="_blank">Brian Earley</a></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Graphics</strong></td>
	<td class="alt1" width="85%" align="left"><a href="http://www.utopiasoftware.net/forum/index.php?s=&amp;act=Profile&amp;CODE=03&amp;MID=2" target="_blank">Brian "Nairb" Baker</a></td>
</tr>
<tr valign="top" align="center">
	<td class="alt2" width="25%" align="left"><strong>Web</strong></td>
	<td class="alt2" width="85%" align="left"><a href="http://www.utopiasoftware.net/" target="_blank">http://www.utopiasoftware.net</a></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>Email</strong></td>
	<td class="alt1" width="85%" align="left"><a href="mailto:utopiasupport@gmail.com" target="_blank">utopiasupport@gmail.com</a></td>
</tr>
</table>
';
echo '
<!-- VOTE -->
<br />
<form action="http://www.hotscripts.com/cgi-bin/rate.cgi" method="post" style="display:block" target="_blank">
<input type="hidden" name="ID" value="24058" />
<table cellpadding="4" cellspacing="0" border="0" width="100%" style="border: 1px solid black">
<tr>
	<td bgcolor="#6384B0" colspan="4" style="border-bottom: 1px solid black"><strong><span class="tblheadtxt">Rate Utopia News Pro</span></strong></td>
</tr>
<tr valign="top" align="center">
	<td class="alt1" width="25%" align="left"><strong>How would you rate UNP?</strong></td>
	<td class="alt1" width="85%" align="left">
		<select name="ex_rate" size="1">
			<option selected="selected">Select</option>
			<option value="5">Excellent</option>
			<option value="4">Very Good</option>
			<option value="3">Good</option>
			<option value="2">Fair</option>
			<option value="1">Poor</option>
		</select>
		<input type="submit" value="Vote" />
	</td>
</tr>
</table>
</form>
<!-- VOTE -->
';
unp_closeBox();
include('footer.php');
?>