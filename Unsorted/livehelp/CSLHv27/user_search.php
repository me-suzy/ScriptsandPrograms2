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

// get department information...
   if($department!=""){ $where = " WHERE recno=$department "; }
   $query = "SELECT * FROM livehelp_departments $where ";
   $data_d = $mydatabase->select($query);  
   $department_a = $data_d[0];
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body bgcolor=FFFFC0 onload=expandit()>
<br>
<SCRIPT>
function expandit() {
 
  window.parent.resizeTo(window.screen.availWidth - 100,      window.screen.availHeight - 100);
 
}
</SCRIPT>
<table width=100% bgcolor=FFFFEE>
<tr bgcolor=EFEF9D>
<td NOWRAP width=1%>
<b>Help Topics:</b>
</td>
<td width=99% align=right> &nbsp;
</td></tr>

<td NOWRAP width=1%>
<img src=images/help_folder.gif>Getting started.
</td>
<td width=99%>
</td></tr>

</table>
</body>
<?

$mydatabase->close_connect();
?>