<?php

// $Id: tool.php 184 2005-09-28 09:06:31Z ryan $

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

// Direct access prevention
defined('WB_PATH') OR die(header('Location: ../index.php'));

// Show form
?>
<br />
<form name="prompt" method="post" action="<?php echo WB_URL; ?>/modules/backup/backup-sql.php">
	<input type="submit" name="backup" value="<?php echo $TEXT['BACKUP_DATABASE']; ?>" onClick="javascript: if(!confirm('<?php echo $MESSAGE['GENERIC']['PLEASE_BE_PATIENT']; ?>')) { return false; }" />
</form>