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

 // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $show_arrival = $people[show_arrival]; 
  $user_alert = $people[user_alert];
?>
<html>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<STYLE>
A:link     {text-decoration: none}
A:visited  {text-decoration: none}
A:active   {text-decoration: none}
A:hover    {text-decoration: none}
A:hover    {color: "#000000"}
</STYLE>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 bgcolor=FFFFC9>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width=100%>
<tr>
<td width=98% background=images/top_trim.gif><img src=images/blank.gif width=443 height=10 border=0></td>
<td width=2% rowspan=2 valign=top><img src=images/version.gif width=126 height=32 border=0></td>
</tr>
<tr><td width=98% align=left  background=images/bk.gif>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 background=images/bk.gif>
<TR ALIGN=CENTER>
<td width=7><img src=images/blank.gif width=7 height=10></td>
<? 
$tabshown = 0;
if($tab == "live"){ ?>
<td background=images/tab8.gif><A HREF="admin_connect.php?urlof=live.php" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>
<TD background=images/tab5.gif NOWRAP=NOWRAP width=80><A HREF="admin_connect.php?urlof=live.php" target=_top><b><?= $lang_livehelp ?></b></A></TD>
<td background=images/tab4.gif><A HREF="admin_connect.php?urlof=live.php" target=_top><img src=images/blank.gif width=19 height=20 border=0></a></td>
<? 
$tabshown = 1;
} else { ?>
<td background=images/tab1.gif><A HREF="admin_connect.php?urlof=live.php" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>
<TD background=images/tab2.gif NOWRAP=NOWRAP width=80><A HREF="admin_connect.php?urlof=live.php" target=_top><b><?= $lang_livehelp ?></b></A></TD>
<? } ?>

<? if($tab == "oper"){ ?>
  <td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/tab5.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=operators.php&tab=oper" target=_top><b><?= $lang_operators ?></b></A></TD>
  <td background=images/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
<? 
$tabshown = 1;
} else {
?>
  <? if ($tabshown == 0) { ?><td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><? } ?>
  <TD background=images/tab2.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=operators.php&tab=oper" target=_top><b><?= $lang_operators ?></b></A></TD>
<? 
$tabshown = 0;
} ?>

<? if($tab == "dept"){ ?>
  <td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/tab5.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=departments.php&tab=dept" target=_top><b><?= $lang_dept ?></b></A></TD>
  <td background=images/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
<? 
$tabshown = 1;
} else {
?>
  <? if ($tabshown == 0) { ?><td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><? } ?>
  <TD background=images/tab2.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=departments.php&tab=dept" target=_top><b><?= $lang_dept ?></b></A></TD>
<?
$tabshown = 0;
 } ?>
<? if($tab == "settings"){ ?>
  <td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/tab5.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=mastersettings.php&tab=settings" target=_top><b><?= $lang_settings ?></b></A></TD>
  <td background=images/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
<? 
$tabshown = 1;
} else {
?>
  <? if ($tabshown == 0) { ?><td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><? } ?>
  <TD background=images/tab2.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=mastersettings.php&tab=settings" target=_top><b><?= $lang_settings ?></b></A></TD>
<? 
$tabshown = 0;
} ?>
<? if($tab == "data"){ ?>
  <td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/tab5.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=data.php&tab=data" target=_top><b><?= $lang_data ?></b></A></TD>
  <td background=images/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
<? 
$tabshown = 1;
} else {
?>
  <? if ($tabshown == 0) { ?><td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><? } ?>
  <TD background=images/tab2.gif NOWRAP=NOWRAP width=70><A HREF="admin.php?page=data.php&tab=data" target=_top><b><?= $lang_data ?></b></A></TD>
<? 
$tabshown = 0;
} ?>

<? if($tab == "tabs"){ ?>
  <td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/tab5.gif NOWRAP=NOWRAP width=60><A HREF="admin.php?page=modules.php&tab=tabs" target=_top><b><?= $lang_tabs ?></b></A></TD>
<td background=images/tab7.gif><img src=images/blank.gif width=14 height=20 border=0></td>
<? 
$tabshown = 1;
} else {
?>
  <? if ($tabshown == 0) { ?>
  <td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><? } ?>
  <TD background=images/tab2.gif NOWRAP=NOWRAP width=60><A HREF="admin.php?page=modules.php&tab=tabs" target=_top><b><?= $lang_tabs ?></b></A></TD>
  <td background=images/tab10.gif><img src=images/blank.gif width=14 height=20 border=0></td>
<? 
$tabshown = 0;
} ?>

</TR>
</table>
</td></tr>
<tr>
<td align=right width=1% NOWARP=NOWRAP colspan=2 background=images/bk.gif>
<table cellpadding=0 cellspacing=0 border=0>
<td width=99% background=images/nav_bot.gif><img src=images/blank.gif height=25 width=43 border=0></td>
<td align=right>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<td NOWRAP=NOWRAP><a href=admin.php target=_top><img src=images/toplinks_1.gif width=56 height=26 border=0></a></td>
<td  NOWRAP=NOWRAP Valign=top background=images/toplinks_2.gif><a href=admin.php target=_top><font color=FFFFCC size=-1><b><?= $lang_over ?></b></font></a></td>
<td><a href="admin.php?page=help.php" target="_top"><img src=images/toplinks_3.gif width=63 height=26 border=0></a></td>
<td  NOWRAP=NOWRAP Valign=top  background=images/toplinks_4.gif><a href="admin.php?page=help.php" target="_top"><font  color=FFFFCC size=-1><b><?= $lang_hlp ?></b></font></a></td>
<td><a href="logout.php" target="_top"><img src=images/toplinks_5.gif width=48 height=26 border=0></a></td>
<td NOWRAP=NOWRAP Valign=top  background=images/toplinks_4.gif><a href="logout.php" target="_top"><font color=FFFFCC size=-1><b><?= $lang_exit ?></b>&nbsp;&nbsp;&nbsp;</font></a></td>
</tr></table>
</td>
</td>
</tr>
</table>
</td></tr>
</table> 

</body>
</html>
<?
$mydatabase->close_connect();
?>