<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | footer.inc.php                                                            |
//  +---------------------------------------------------------------------------+
list($usec, $sec) = explode(' ', microtime());
$execution_time = (float)$usec + (float)$sec - (float)$cfg['start_time'];

if (isset($cfg['footer']) && $cfg['footer'] == 'close')
	{
	echo '</body>' . "\n";
	echo '</html>' . "\n";
	echo '<!-- ---- end of file ----- -->' . "\n";
	exit();
	}
?>
<!-- ---- begin footer ----- -->
	</td>	
</tr>
<tr valign="bottom">
	<td height="30" align="center"><font class="small"><?php if (!empty($cfg['username'])) echo '| <a href="users.php?command=UserMenu" target="main">User: ' . htmlentities($cfg['username']) . '</a> '; ?>| <a href="about.php" target="main">netjukebox <?php echo $cfg['netjukebox_version']; ?></a> | Script execution time: <?php echo number_format($execution_time * 1000, 1); ?>  ms |</font></td>	
</tr>
</table>
<?php
if (isset($cfg['footer']) && $cfg['footer'] == 'dynamic')
	echo '<!-- ---- dynamic content ----- -->' . "\n";
else
	{
	echo '</body>' . "\n";
	echo '</html>' . "\n";
	echo '<!-- ---- end of file ----- -->' . "\n";
	exit();
	}
?>
