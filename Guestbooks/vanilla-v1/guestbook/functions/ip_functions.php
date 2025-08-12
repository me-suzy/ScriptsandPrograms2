<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: ip_functions.php
*	Description: Functions for banning IPs, etc.
****************************************************************************
*	Build Date: August 20, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//Ban an ip address from posting
function ip_ban($ip)
{
	$ipquery = mysql_query("SELECT * FROM vanilla_config WHERE config_name = 'banned_ip'");
	$ipdata = mysql_fetch_array($ipquery);
	$ipdata = $ipdata["config_value"];

	//Check if the IP was already banned
	$iparray = explode("\n", $ipdata);
	if (in_array($ip, $iparray))
	{
		echo "<p class=\"response\">The IP Address <strong>$ip</strong> is already banned.</p>";
	}

	//If it wasn't banned, update the list
	else
	{
		$ipdata = $ipdata."\n".$ip;
		mysql_query("UPDATE vanilla_config SET config_value = '$ipdata' WHERE config_name = 'banned_ip'") or die(mysql_error());
		echo "<p class=\"response\">The IP Address <strong>$ip</strong> has been banned from making comments.</p>";
	}
	ip_manage();

	mysql_free_result($ipquery);
}

//Manage the list of IPs
function ip_manage()
{
	$ipquery = mysql_query("SELECT * FROM vanilla_config WHERE config_name = 'banned_ip'");
	$ipdata = mysql_fetch_array($ipquery);
	$ipdata = $ipdata["config_value"];
?>
<h1>Banned IP Addresses</h1>

<form id="manage_ip" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

<div class="bk_form">
	<input type="hidden" name="act" value="update_ip" />
	<span class="bk_label" style="display: block;clear: both; text-align: center;">
		<label for="ip">Banned IP Addresses:</label>
One per line.
	</span>
	<span class="bk_field" style="display: block;text-align: center;">
		<textarea name="ip" cols="15" rows="5"><?php echo $ipdata; ?></textarea>
	</span>
</div>
<div class="bk_form bk_buttons">
	<input type="submit" value="Update Ban List" />
</div>

</form>
<?php
}

//If the form is just being used, do a general update:
function ip_update()
{
	$ip = $_POST["ip"];

	//Strip out whitespace
	$ip = preg_replace("/[\r\n]+[\s\t]*[\r\n]+/","\n",$ip);
	$ip = trim($ip);

	//Check if the IP was already banned, if so, strip it out of the array
	$iparray = explode("\r\n", $ip);
	$iparray = array_unique($iparray);

	//Finalise the list
	$ip = implode("\n", $iparray);

	mysql_query("UPDATE vanilla_config SET config_value = '$ip' WHERE config_name = 'banned_ip'") or die(mysql_error());
	echo "<p class=\"response\">Ban list updated.</p>";
	ip_manage();
}
?>