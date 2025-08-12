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

if($_POST["ip"]) {
$ip = $_POST["ip"];
$date = date("g:i a, j F Y");

   if(preg_match("([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})", $ip)) {



   $insert = "INSERT INTO ".$so_prefix."_bannedip (bannedip,bandate) VALUES ('$ip','$date')"; 
   mysql_query($insert) or die("Sorry, could not add IP ban >>> " . mysql_error());
   smsg("IP ban added to database.<br />If the page doesn&#39;t refresh, <a href='../ip.php'>Click here</a> to retrun to the control panel.");
   header('Refresh: 3; URL=../ip.php');
   } else { smsg("Invalid IP address!<br />Please <a href='javascript:history.back()'>go back</a> to correct.");}
}
if($_GET["ip"]) {
$ip = $_GET["ip"];

   $delete = "DELETE FROM ".$so_prefix."_bannedip WHERE bannedip='$ip'"; 
   mysql_query($delete) or die("Sorry, could not unban IP >>> " . mysql_error());
   smsg("IP successfully unbanned.<br />If the page doesn&#39;t refresh, <a href='../ip.php'>Click here</a> to retrun to the control panel.");
   header('Refresh: 3; URL=../ip.php');

} 

} else { echo "Access Denied."; }
?>