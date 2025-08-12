<?php
/*


	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/
session_start();
include("db.php");
include("../inc/redir.php");
include("../inc/auth.php");

if($user == $_SESSION["username"] && $pass == $_SESSION["password"]) {
$referrer =  $_SERVER['HTTP_REFERER'];
$ids = $_POST["ids"];
if(empty($ids)) {
smsg("No objects were selected. Please go <a href='javascript: history.back()'>back</a> and enter the ID(s) you want to delete.");
} else {
$all = "*";
if($ids==$all) {
mysql_query("DELETE FROM ".$so_prefix."_obj")
or die(mysql_error());
mysql_query("DELETE FROM ".$so_prefix."_main")
or die(mysql_error());

smsg("ALL objects AND comments were deleted successfully.<br />If the page doesn&#39;t refresh, <a href='$referrer'>follow this link</a>");
header('Refresh: 3; URL=' . $referrer .' ');
}
else {
$nowarray = explode(',', $ids);

$fid = implode("' OR catid='",$nowarray);
$x="catid='";
$y="'";
$finid = $x . $fid . $y;

mysql_query("DELETE FROM ".$so_prefix."_obj WHERE $finid")
or die(mysql_error());
mysql_query("DELETE FROM ".$so_prefix."_main WHERE $finid")
or die(mysql_error());

smsg("The selected objects were deleted successfully.<br />If the page doesn&#39;t refresh, <a href='$referrer'>follow this link</a>");
header('Refresh: 3; URL=' . $referrer .' ');
}
}


} else { echo "Access Denied."; }
?>