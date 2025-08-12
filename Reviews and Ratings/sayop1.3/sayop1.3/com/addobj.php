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

if($_POST["obj_name"]) {
$obj_name = $_POST["obj_name"];
$insert = "INSERT INTO ".$so_prefix."_obj (obj_name) VALUES ('$obj_name')"; 
    mysql_query($insert) or die("Sorry, could not add new object >>> " . mysql_error());

smsg("New article added successfully!<br />If the page doesn&#39;t refresh, <a href='../newobj.php'>Click here</a> to retrun to the control panel.");
header('Refresh: 3; URL=../newobj.php');

} else { echo "No name specified.";}
} else { echo "Access Denied."; }
?>