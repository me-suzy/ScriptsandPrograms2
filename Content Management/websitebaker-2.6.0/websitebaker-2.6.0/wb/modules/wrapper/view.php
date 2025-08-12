<?php

// $Id: view.php 106 2005-09-15 19:15:43Z stefan $

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

// Get url
$get_settings = $database->query("SELECT url,height FROM ".TABLE_PREFIX."mod_wrapper WHERE section_id = '$section_id'");
$fetch_settings = $get_settings->fetchRow();
$url = ($fetch_settings['url']);

?>
<iframe src="<?php echo $url; ?>" width="100%" height="<?php echo $fetch_settings['height']; ?>px" frameborder="0" scrolling="auto">
Your browser does not support inline frames.<br />
Click on the link below to visit the website that was meant to be shown here...<br />
<a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
</iframe>