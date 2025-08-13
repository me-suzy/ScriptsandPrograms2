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
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

checkuser();
$pass = $username;
      $query = "SELECT * FROM livehelp_users WHERE username='$username' ";
      $data = $mydatabase->select($query);
      $data = $data[0];
      $email = $data[email];
      $pass = $data[password];
?>
<body bgcolor=FFFFEE>
<center>
<SCRIPT>
function inCell(cell, newcolor) {

        if (!cell.contains(event.fromElement)) {

                cell.bgColor = newcolor;

        }

}



function outCell(cell, newcolor) {

        if (!cell.contains(event.toElement)) {

                cell.bgColor = newcolor;

        }

}
// onmouseovers
r_about = new Image;
h_about = new Image;
r_about.src = 'images/admin_arr.gif';
h_about.src = 'images/blank.gif';

function q_a(topic){
 url = 'http://www.craftysyntax.com/livehelp_1_0/livehelp.php?tab=qa&doubleframe=yes&page=user_qa.php&department=1&topic=' + topic
 window.open(url, 'chat540872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}

</SCRIPT>
<table border=0 cellpadding=0 cellspacing=0 width=580 bgcolor=FFFFEE><tr><td>
Your Are Here: <b><a href=contents.php>Overview Page</a> :: Help Page</b><br>
</td></tr></table>
<table border=0 cellpadding=0 cellspacing=0 width=580>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=EFEF9D> <b>CSLH! Help Page:</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one5><a href=http://www.craftysyntax.com/livehelp/howto.php onmouseover="document.one5.src=r_about.src" onmouseout="document.one5.src=h_about.src">Getting Started.(How to setup and use CSLH)</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one1><a href=http://www.craftysyntax.com/support/?c=3 target=_blank onmouseover="document.one1.src=r_about.src" onmouseout="document.one1.src=h_about.src">Crafty Syntax Online Support Message Board</a></td></tr>	
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one4><a href=http://www.craftysyntax.com/livehelp/support.php target=_blank onmouseover="document.one4.src=r_about.src" onmouseout="document.one4.src=h_about.src">Support Request / Programming Help</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');" ><img src=images/blank.gif width=22 height=21 name=four><a href=javascript:q_a(0)  onmouseover="document.four.src=r_about.src" onmouseout="document.four.src=h_about.src">Launch CSLH Q&A.</a></td></tr>
<tr><td bgcolor=FFFFC0><br></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
</table>
<br><br>
<font size=-2>
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>] 
</font><br><br><br><br><br><br>
</body>
<?
$mydatabase->close_connect();
?>