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
//  | message.php                                                               |
//  +---------------------------------------------------------------------------+
list($usec, $sec) 			= explode(' ', microtime());
$cfg['start_time']			= (float)$usec + (float)$sec;
$cfg['header']		 		= 'align';

header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');// HTTP/1.0

require_once('include/config.inc.php');
require_once('include/globalize.inc.php');
require_once('include/header.inc.php');

$type						= post('type');
$cfg['username']			= post('username');				//required for footer
$cfg['netjukebox_version']	= post('netjukebox_version');	//required for footer
?>
<table cellspacing="8" class="<?php echo $type; ?>">
<tr>
	<td valign="top"><?php if (file_exists($cfg['img'] . '/message_' . $type . '.gif')) echo '<img src="' . $cfg['img'] . '/message_' . $type . '.gif" alt="" border="0">'; ?></td>
	<td valign="top"><?php echo post('message'); ?></td>
</tr>
</table>
<?php
require_once('include/footer.inc.php');
?>

