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
 url = 'http://www.craftysyntax.com/livehelp_18/livehelp.php?doubleframe=yes&page=faq.php&tab=qa&topic=' + topic
 window.open(url, 'chat540872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}
function openwindow(url){ 
 window.open(url, 'chat54057', 'width=572,height=320,menubar=no,scrollbars=0,resizable=1');
}
</SCRIPT>
<?
if($membernum < 10000){
?>
<font color=990000><b>This program has not been Registered yet.</b><a href=registerit.php>Click here to register</a><br><br>
<?} ?>
<table border=0 cellpadding=0 cellspacing=0 width=580>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=EFEF9D> <b>CSLH Overview index:</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td bgcolor=FFFFC0><b>Info/News/Updates/Help:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one5><a href=http://www.craftysyntax.com/livehelp/howto.php target=_blank onmouseover="document.one5.src=r_about.src" onmouseout="document.one5.src=h_about.src">Getting Started.(How to setup and use CSLH)</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one3><a href=admin.php?page=help.php target=_top onmouseover="document.one3.src=r_about.src" onmouseout="document.one3.src=h_about.src">General Help / Support Message Board / FAQ Site.</a></td></tr>	
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');" ><img src=images/blank.gif width=22 height=21 name=five><a href=http://www.craftysyntax.com/livehelp/updates.php  target=_blank onmouseover="document.five.src=r_about.src" onmouseout="document.five.src=h_about.src">News, Updates and About the Program</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');" ><img src=images/blank.gif width=22 height=21 name=five1><a href=http://www.craftysyntax.com/livehelp/todo.php?v=<?= $version ?>  target=_blank onmouseover="document.five1.src=r_about.src" onmouseout="document.five1.src=h_about.src">To do list , Version Check and Downloads</a></td></tr>	
<tr><td bgcolor=FFFFC0><br></td></tr>
<tr><td bgcolor=FFFFC0><b>Live Help:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one6><a href=live.php target=_top onmouseover="document.one6.src=r_about.src" onmouseout="document.one6.src=h_about.src"><font color=770000>Monitor Traffic online.</font></a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one2><a href=javascript:openwindow('edit_quick.php') onmouseover="document.one2.src=r_about.src" onmouseout="document.one2.src=h_about.src">Edit Quick Notes</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one22><a href=javascript:openwindow('edit_quick.php?typeof=IMAGE') onmouseover="document.one22.src=r_about.src" onmouseout="document.one22.src=h_about.src">Edit push Images</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one222><a href=javascript:openwindow('edit_quick.php?typeof=URL') onmouseover="document.one222.src=r_about.src" onmouseout="document.one222.src=h_about.src">Edit push URL's</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Operators:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one7><a href=admin.php?page=operators.php&tab=oper target=_top onmouseover="document.one7.src=r_about.src" onmouseout="document.one7.src=h_about.src">Add/edit/delete Operators</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Departments:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one9><a href=admin.php?page=departments.php&tab=dept target=_top onmouseover="document.one9.src=r_about.src" onmouseout="document.one9.src=h_about.src">Add/edit/delete Departments</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one99><a href=admin.php?page=departments.php&tab=dept&help=1 target=_top onmouseover="document.one99.src=r_about.src" onmouseout="document.one99.src=h_about.src">HTML CODE for Departments</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Settings:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one11><a href=admin.php?page=mastersettings.php&tab=settings target=_top onmouseover="document.one11.src=r_about.src" onmouseout="document.one11.src=h_about.src">Edit your system settings and Preferences</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Data:</b></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one12><a href=admin.php?page=data.php&tab=data target=_top onmouseover="document.one12.src=r_about.src" onmouseout="document.one12.src=h_about.src">Edit/view your Data</a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one15><a href=admin.php?page=data.php&tab=data target=_top onmouseover="document.one15.src=r_about.src" onmouseout="document.one15.src=h_about.src">Edit/view REFERERS and Page views</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Q&A Help (Auto Help)</b>:</td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');"><img src=images/blank.gif width=22 height=21 name=one14><a href=qa.php target=_blank onmouseover="document.one14.src=r_about.src" onmouseout="document.one14.src=h_about.src">Edit/Add/Remove Questions & Answers</a></td></tr>
<tr><td bgcolor=FFFFC0><b>Additional Programs / Hosting / Support and Donations</b>:</td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');" ><img src=images/blank.gif width=22 height=21 name=seven1><a href=http://www.craftysyntax.com/livehelp/updates.php#donate  target=_blank  onmouseover="document.seven1.src=r_about.src" onmouseout="document.seven1.src=h_about.src"><B>Donations to the project</b></a></td></tr>
<tr><td bgcolor=FFFFC0 onmouseover="inCell(this, '#EFEF9D');" onmouseout="outCell(this, '#FFFFC0');" ><img src=images/blank.gif width=22 height=21 name=four><a href=http://craftysyntax.com/projects/ target=_blank onmouseover="document.four.src=r_about.src" onmouseout="document.four.src=h_about.src">Additional Programs and Projects</a></td></tr>
<tr><td height=10 bgcolor=FFFFC0><img src=images/blank.gif width=10 height=10 border=0></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
</table>
<br><br>
<a href=rules.php>- Click here for Copyright and License Overview - </a>
<br><br><br><br><br>
<font size=-2><center>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font></center>
</font><br><br><br><br><br><bR>
</body>
<?
$mydatabase->close_connect();
?>