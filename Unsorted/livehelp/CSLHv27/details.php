<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
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


require("globals.php");
include("config.php");
$query = "SELECT * from livehelp_users WHERE user_id='$id'";
$user_info = $mydatabase->select($query);
$user_info = $user_info[0];

$query = "SELECT * from livehelp_departments WHERE recno='$user_info[department]'";
$tmp = $mydatabase->select($query);
$nameof = $tmp[0];
$nameof = $nameof[nameof];

?>
<BODY bgcolor=FFFFEE>
<table width=100%>
<tr><td colspan=2 bgcolor=DDDDDD> User Information: </td></tr>
<tr><td align=left><?
print "<b>Referer:</b> <a href=$user_info[camefrom] target=_blank>$user_info[camefrom]</a><br>";
print "<b>E-mail:</b> <a href=mailto:$user_info[email]>$user_info[email]</a><br>";
print "<b>Status:</b>$user_info[status]<br>";
print "<b>department</b>$nameof<br>";
print "<b>Identity:</b>$user_info[identity]<br>";
$now = date("YmdHis");
$thediff = $now - $user_info[lastaction];
print "<b>Last Action:</b> $thediff Seconds ago<br>";
 

print "<b>Page trail:</b><br>";
$query = "SELECT * from livehelp_visit_track WHERE id='$id' Order by whendone DESC";
$page_trail = $mydatabase->select($query);

print "<table border=1><tr bgcolor=FFFFFF><td>What page</td><td>when</td></tr>";
for($i=0;$i< count($page_trail); $i++){
  $page = $page_trail[$i];
  if($offset == ""){ $offset = 0; }
  $when = mktime ( substr($page[whendone],8,2)+$offset, substr($page[whendone],10,2), substr($page[whendone],12,2), substr($page[whendone],4,2) , substr($page[whendone],6,2), substr($page[whendone],0,4) );
  print "<tr><td><a href=$page[location]  target=_blank>$page[location]</a></td><td>";
  print date("F j, Y, g:i a",$when);
  print "</td></tr>";
}
print "</table><br><center><a href=javascript:window.close()>Close Window</a>";
$mydatabase->close_connect();
?>