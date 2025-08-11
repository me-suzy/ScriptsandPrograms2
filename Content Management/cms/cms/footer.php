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
// Filename: footer.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Common footer for system pages
// ----------------------------------------------------------------------

$contents = ob_get_contents(); // Get the contents of the buffer
ob_end_clean(); 	       // End buffering and discard

$LBL_HOME = LBL_HOME;
$LBL_MAP = LBL_MAP;
$LBL_EDIT_ACC = LBL_EDIT_ACC;
$LBL_BACKUP = LBL_BACKUP;
$LBL_RESTORE = LBL_RESTORE;
$LBL_META = LBL_META;
$LBL_MARQUEE = LBL_MARQUEE;
$LBL_LOGOUT = LBL_LOGOUT;
$LBL_LOGIN = LBL_LOGIN;

$nav_links=<<<END
      <A href="index.php">$LBL_HOME</A> ::
      <A href="sitemap.php">$LBL_MAP</A>
END;
if (@$_SESSION["status"] == "login"){
$nav_links.=<<<END
 :: <a href="usersedit.php?key=1">$LBL_EDIT_ACC</a>
 :: <a href="dbbackup.php">$LBL_BACKUP</a>
 :: <a href="dbrestore.php">$LBL_RESTORE</a>
 :: <a href="meta.php">$LBL_META</a>
 :: <a href="marquee.php">$LBL_MARQUEE</a>
 :: <a href="logout.php">$LBL_LOGOUT</a>
END;
}else{
$nav_links.=<<<END
 :: <a href="login.php">$LBL_LOGIN</a>
END;
}
$nav_links.=<<<END
 :: <a href="pageswml.php">WML</a>
END;

$template->replace("CONTENTS", $contents);
$template->replace("NAV_LINKS", $nav_links);
$template->publish();

?>

