<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: update_functions.php
*	Description: Check for updates
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

//Check if updates for this script are available
function update()
{
	GLOBAL $version;
	$file = "http://tachyondecay.net/archives/downloads/vanilla/vanilla-cversion.txt";
	$cversion = file_get_contents($file);
?>
<h1 class="configheader">Vanilla Statistics:</h1>

<ul>
	<li>Your Version: <?php echo $version; ?></li>
	<li>Current Version: <?php echo $cversion; ?></li>
	<li>Do you need to update?&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	if ($version != $cversion)
	{
		echo '<span style="color: #ff0000; font-weight: bold;">Yes</span>';
		$update_stat = "yes";
	}

	if ($version == $cversion)
	{
		echo '<span style="color: #006400; font-weight: bold;">No</span>';
		$update_stat = "no";
	}
?>
	</li>
</ul>

<?php
	if ($update_stat == "no")
	{
?>
	<p>Congratulations, you have the most current version of Vanilla Guestbook.  You do not need to update at this time, but continue checking this page or my <a href="http://tachyondecay.net/">website</a> for updates.</p>
<?php
	}

	if ($update_stat == "yes")
	{
?>
	<p>You don't have the most current version of Vanilla Guestbook.  You need to update at this time, please go to the <a href="http://tachyondecay.net/archives/downloads/vanilla/">Vanilla Guestbook Website</a> and download the latest version.  (If that link does not work, try <a href="http://tachyondecay.net/">http://tachyondecay.net/</a>.)</p>
<?php
	}
}
?>