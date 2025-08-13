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

?>
<html>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 bgcolor=FFFFC9>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<? if($tab == ""){ ?>
<td width=1%><img src=images/nav_livehelp.gif width=414 height=36 border=0 usemap=#tabs></td>
<? } ?>
<? if($tab == "prefs"){ ?>
<td width=1%><img src=images/nav_pref.gif width=414 height=36 border=0 usemap=#tabs></td>
<? } ?>
<? if($tab == "admin"){ ?>
<td width=1%><img src=images/nav_admin.gif width=414 height=36 border=0 usemap=#tabs></td>
<? } ?>
<? if($tab == "extras"){ ?>
<td width=1%><img src=images/nav_extra.gif width=414 height=36 border=0 usemap=#tabs></td>
<? } ?>
<td width=99% background=images/nav_bg.gif align=right><img src=images/version.gif width=141 height=36></td>
</tr>
</table>
<map name="tabs">
  <area shape="rect" coords="0,0,90,32" href="admin.php" target="_top" alt="setup">
  <area shape="rect" coords="91,0,180,32" href="prefs.php" target="_top" alt="data">
  <area shape="rect" coords="181,0,270,32" href="settings.php" target="_top" alt="forms">
  <area shape="rect" coords="271,0,360,32" href="extras.php" target="_top" alt="programs">
</map>
</body>
</html>
<?
$mydatabase->close_connect();
?>