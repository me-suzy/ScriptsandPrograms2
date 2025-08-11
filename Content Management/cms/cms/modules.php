<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: modules.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  include outsource content as a sub modules in the CMS
// ----------------------------------------------------------------------
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php
     $module = escapeshellcmd($_GET[mod]);
     if(!is_dir("modules/$module")){ header("Location: index.php"); }
     $script = escapeshellcmd($_GET[scr]);
     if(!is_file("modules/$module/$script")){ header("Location: index.php"); }
     if(is_numeric($_GET[hei])){ $height = $_GET[hei]; }else{ $height = 600; }
?>
<?php include_once ("header.php") ?>
<iframe align=top valign=right width=100% height=<?php echo $height; ?> marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=auto src="modules/<?php echo $module; ?>/<?php echo $script; ?>"></iframe>
<?php include_once ("footer.php") ?>
