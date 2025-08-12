<?php

// $Id: tool.php 238 2005-11-21 10:43:34Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

if(!isset($_GET['tool'])) {
	header("Location: index.php?advanced=yes");
}

// Check if tool is installed
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'tool' AND directory = '".$_GET['tool']."'");
if($result->numRows() == 0) {
	header("Location: index.php?advanced=yes");
}
$tool = $result->fetchRow();

$admin = new admin('Settings', 'settings_advanced');

?>
<h4 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	<a href="<?php echo ADMIN_URL; ?>/settings/index.php?advanced=yes"><?php echo $MENU['SETTINGS']; ?></a>
	->
	<a href="<?php echo ADMIN_URL; ?>/settings/index.php?advanced=yes#administration_tools"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
	->
	<?php echo $tool['name']; ?>
</h4>
<?php
require(WB_PATH.'/modules/'.$tool['directory'].'/tool.php');

$admin->print_footer();

?>
