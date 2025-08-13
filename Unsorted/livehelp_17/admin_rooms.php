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

$timeof = date("YmdHis");
$timeof_old = $timeof - 100000;

$username = $username;
checkuser();

$prev = mktime ( date("H"), date("i")-35, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
$rightnow = date("YmdHis");
 
 
if($status == ""){ $status = "Y"; } 
    
if($status == "N"){
$query = "UPDATE livehelp_users set isonline='N',lastaction='$oldtime',status='offline' WHERE username='$username'";
$mydatabase->sql_query($query);
}
if($status == "Y"){
$query = "UPDATE livehelp_users set isonline='Y',lastaction='$rightnow',status='chat' WHERE username='$username'";
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
?>
<body bgcolor=FFFFCC marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<form action=admin_rooms.php method=post name=mine>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr>
<td NOWRAP><b>User:</b><?= $username ?></td>
<td NOWRAP><b>Status:</b><select name=status onchange=document.mine.submit()>
<option value=Y <?= $online ?> >Online</option>
<option value=N <?= $offline ?>>Off Line</option>
</select></td>
<!--
<td NOWRAP>
<b>Department:</b><select name=department onchange=document.mine.submit()>
<option value=0>default</option>
</select></td>
-->
<td NOWRAP><a href=logout.php target=_top><font color=990000>LOG OUT</font></a>
</td>
</tr></table>
</form>
<embed src="insite.wav" hidden=true autostart=false loop=false name="insiteSound" mastersound>
<embed src="sound.wav" hidden=true autostart=false loop=false name="firstSound" mastersound>
</body>
<?
$mydatabase->close_connect();
?>