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
include("user_access.php");

$timeof = date("YmdHis");
$timeof_old = $timeof - 100000;

checkuser();

 // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $show_arrival = $people[show_arrival]; 
  $user_alert = $people[user_alert];

$prev = mktime ( date("H"), date("i")-35, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
$rightnow = date("YmdHis");
 
 
if(($alterations == "Y") && ($show_arrival_new == "")){ $show_arrival_new = "N"; } 
if(($alterations == "Y") && ($user_alert_new == "")){ $user_alert_new = "Y"; } 
if($status == ""){ $status = "Y"; } 

if($alterations == "Y"){
  $alterations_sql = "auto_invite='$auto_invite',show_arrival='$show_arrival_new',user_alert='$user_alert_new',";
}
    
if($status == "N"){
$query = "UPDATE livehelp_users set " . $alterations_sql . "isonline='N',lastaction='$oldtime',status='offline' WHERE username='$username'";
$mydatabase->sql_query($query);
}
if($status == "Y"){
$query = "UPDATE livehelp_users set " . $alterations_sql . "isonline='Y',lastaction='$rightnow',status='chat' WHERE username='$username'";
$mydatabase->sql_query($query);
}



$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
if($row[isonline] == "N"){
	$offline = " SELECTED ";
}
if($row[isonline] == "Y"){
	$online = " SELECTED ";
}
if($row[show_arrival] == "Y"){
	$show_arrival = " CHECKED ";
}
if($row[user_alert] == "N"){
	$user_alert = " CHECKED ";
}
if($row[auto_invite] == "Y"){
	$auto_invite = " CHECKED ";
}
?>
<SCRIPT>

function my_auto_invite(){
  url = 'autoinvite.php';
  if(document.mine.auto_invite.checked){
    window.open(url, 'chat545087', 'width=590,height=400,menubar=no,scrollbars=1,resizable=1');
  }
  for($i=0;$i<100000;$i++){
   // sleep  	
  }
   document.mine.submit();
}
</SCRIPT>
<body bgcolor=FFFFCC marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<form action=admin_rooms.php method=post name=mine>
<input type=hidden name=alterations value=Y>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr>
<td NOWRAP><b>Status:</b><select name=status onchange=document.mine.submit()>
<option value=Y <?= $online ?> >Online</option>
<option value=N <?= $offline ?>>Off Line</option>
</select></td>
<td NOWRAP><input type=checkbox value=Y name=auto_invite <?= $auto_invite ?> onclick="javascript:my_auto_invite();"><b><font color=007777>AUTO INVITE</font></b></td>
<td NOWRAP><input type=checkbox value=Y name=show_arrival_new <?= $show_arrival ?> onclick=document.mine.submit() ><b>Alert of Visitors</b> </td>
<td NOWRAP><input type=checkbox value=N name=user_alert_new  <?= $user_alert ?> onclick=document.mine.submit() ><b>Sound Alert</b></td>
</tr></table>
</form>
</body>
<?
$mydatabase->close_connect();
?>