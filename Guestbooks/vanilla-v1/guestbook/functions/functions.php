<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: functions.php
*	Description: Generic functions
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

//Display the index page (default Admin CP page)
function index_display()
{
	$query = mysql_query ("SELECT AVG(score) AS AvgScore FROM vanilla_entry") or die(mysql_error());
	$avg_score = mysql_fetch_array($query);
	$avg_score = $avg_score["AvgScore"];
	mysql_free_result($query);

	$query = mysql_query("SELECT ID FROM vanilla_entry") or die(mysql_error());
	$total_entries = mysql_num_rows($query);
	mysql_free_result($query);


?>
<p>Welcome to the Vanilla Guestbook.  Although it only comes in one flavour, it's a simple guestbook tool for your website.  From this control panel, you can access any part of the guestbook's various controls and carry out a number of tasks.  If you need help, click the Request Support link on the side to fill out a form and email me.</p>

<h1>Guestbook Statistics</h1>

<table class="guestbook_stats">
<thead>
<tr>
	<th>Statistic</th>
	<th>Value</th>
</tr>
</thead>
<tbody>
	<tr>
		<td class="bk_stat">Total Entries</td>
		<td class="bk_value"><?php echo $total_entries;?></td>
	</tr>
	<tr>
		<td class="bk_stat">Average Score</td>
		<td class="bk_value"><?php echo $avg_score;?></td>
</tbody>
</table>

<?php
}

//Display the navigation menu
function nav_display($act)
{
	global $queue;

	$queuequery = mysql_query("SELECT * FROM vanilla_entry WHERE queue = '1'");
	$num = mysql_num_rows($queuequery);
	mysql_free_result($queuequery);

	if ($num > 0)
	{
		$adjacent = "<span style=\"font-weight: bold;\">(".$num.")</span>";
	}
?>
<div class="links">
	<h2 class="navheader">Navigation</h2>
<ul>
	<li class="navtitle">Guestbook
	<ul>
		<li><a href="admin.php?act=view">Browse Entries</a></li>
		<li><a href="admin.php?act=manage_ip">Manage IPs</a></li>
		<li><a href="admin.php?act=queue">Manage Queue <?php echo $adjacent;?></a></li>
	</ul>
	</li>

	<li class="navtitle">Configuration
	<ul>
		<li><a href="admin.php?act=bk_config">Guestbook Options</a></li>
		<li><a href="admin.php?act=mysql">MySQL Info</a></li>
		<li><a href="admin.php?act=pass">Change Password</a></li>
		<li><a href="admin.php?act=update">Check for Updates</a></li>
		<li><a href="admin.php?act=support">Request Support</a></li>
	</ul>
	</li>
<?php
		if (!isset($_SESSION['password']) && $act != "login_check")
		{
?>
	<li><a href="admin.php?act=login">Login</a></li>
<?php
		}

		else
		{
?>
	<li><a href="admin.php?act=logout">Logout</a></li>
<?php
		}
?>
</ul>
</div>
<?php
}
?>