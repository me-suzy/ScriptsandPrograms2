<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

include("config.php");
include("user_access.php");

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

// checklogin etc..
checkuser();
$mydatabase->close_connect();
?>
<title>Live help admin</title>
<frameset rows="40,*,155" border="0" frameborder="0" framespacing="0" spacing="0">
 <frame src="admin_options.php" name="topofit" scrolling="no" border="0" marginheight="0" marginwidth="0">
 <frameset cols="*,300" border="0" frameborder="0" framespacing="0" spacing="0">
  <frameset rows="32,*" border="0" frameborder="0" framespacing="0" spacing="0">
   <frame src="admin_rooms.php" name="rooms" scrolling="NO" border="0" marginheight="0" marginwidth="0">
   <frame src="admin_connect.php?urlof=admin_chat.php" name="connection" scrolling="AUTO" border="0" marginheight="0" marginwidth="0">
  </frameset>
  <frame src="admin_users.php" name="users" scrolling="AUTO" border="0" marginheight="0" marginwidth="0">
 </frameset>
 <frame src="admin_chat_bot.php" name="bottomof" scrolling="no" border="0" marginheight="0" marginwidth="0">
</frameset>