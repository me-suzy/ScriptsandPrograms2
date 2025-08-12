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



if($_POST["url"] || $_POST["code"]) {
$url = addslashes($_POST["url"]);
$code = addslashes($_POST["code"]);

$imgurl = "<img src=".$url." alt=\"\" border=\"0\">";
$insert = "INSERT INTO ".$so_prefix."_smilies (code,smilie) VALUES ('$code','$imgurl')"; 
    mysql_query($insert) or die("Sorry, could not add new smilie >>> " . mysql_error());
smsg("New smilie was successfully added to the database!<br />If the page doesn&#39;t refresh, <a href='../smile.php'>Click here</a> to retrun to the control panel.");
header('Refresh: 3; URL=../smile.php');

} 

if($_GET["id"]) {
$id = $_GET["id"];

$delsmilie = "DELETE FROM ".$so_prefix."_smilies WHERE id='$id'";
    mysql_query($delsmilie) or die("Sorry, could not delete smilie >>> " . mysql_error());

smsg("Smilie was deleted successfully!<br />If the page doesn&#39;t refresh, <a href='../smile.php'>Click here</a> to retrun to the control panel.");

header('Refresh: 3; URL=../smile.php');

}


} else { echo "Access Denied."; }
?>