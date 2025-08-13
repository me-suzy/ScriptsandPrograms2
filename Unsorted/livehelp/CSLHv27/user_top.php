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
   if($department!=""){ $where = " WHERE recno='$department' "; }
   $query = "SELECT * FROM livehelp_departments $where ";
   $data_d = $mydatabase->select($query);  
   $department_a = $data_d[0];
   $department = $department_a[recno];   

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
<SCRIPT>
function expandit() {
 <? if ($tab != 1){ ?>
  window.parent.resizeTo(window.screen.availWidth - 100,      
  window.screen.availHeight - 100);
 <? } ?>
 
}
</SCRIPT>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 onload=window.focus();expandit()>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=1% background=images/bk.gif><img src=images/blank.gif width=10 height=14 border=0><br>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 background=images/bk.gif>
<TR ALIGN=CENTER>
<td width=3><img src=images/blank.gif width=3 height=20></td>
<? 

$tabshown = 0;
$query = "SELECT * FROM livehelp_modules,livehelp_modules_dep WHERE livehelp_modules.id=livehelp_modules_dep.modid AND livehelp_modules_dep.departmentid='$department' ORDER by livehelp_modules_dep.ordernum";
$tabs = $mydatabase->select($query);
for($k=0;$k< count($tabs); $k++){
 $row = $tabs[$k];

 if( ($tab == $row[id]) || ($tab=="") ){
   if($k == 0){ 
     print "<td background=images/tab8.gif><A HREF=\"livehelp.php?page=$row[path]&department=$department&tab=$row[id]\" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>\n";
   } else { 
     print "<td background=images/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>\n";
   } 
?>
<TD background=images/tab5.gif NOWRAP=NOWRAP width=80 NOWRAP><A HREF="livehelp.php?page=<?= $row[path] ?>&department=<?= $department ?>&tab=<?= $row[id] ?>" target=_top><b><?= ereg_replace(" ","&nbsp;",$row[name]) ?></b></A></TD>

<? if ($k != (count($tabs)-1)){ ?>
<td background=images/tab4.gif><A HREF="livehelp.php?page=<?= $row[path] ?>&department=<?= $department ?>&tab=<?= $row[id] ?>" target=_top><img src=images/blank.gif width=19 height=20 border=0></a></td>  
<? } ?>

  <?
 $tabshown = 1;
 } else {
   if (($tabshown == 0) && ($k != 0)) { ?>
     <td background=images/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td>
 <? } else { 	
   if ($tabshown == 0){ 
 ?>
   <td background=images/tab1.gif><A HREF="livehelp.php?page=<?= $row[path] ?>&department=<?= $department ?>&tab=<?= $row[id] ?>" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>
<? }
} 
    $tabshown = 0;
?>
<TD background=images/tab2.gif NOWRAP=NOWRAP width=80 NOWRAP><A HREF="livehelp.php?page=<?= $row[path] ?>&department=<?= $department ?>&tab=<?= $row[id] ?>" target=_top><b><?= ereg_replace(" ","&nbsp;",$row[name]) ?></b></A></TD> 
 <?
}

if ($k == (count($tabs)-1)){
  if($tabshown == 0){
     print "<td background=images/tab10.gif><img src=images/blank.gif width=14 height=20 border=0></td>";
  } else {
     print "<td background=images/tab7.gif><img src=images/blank.gif width=14 height=20 border=0></td>";
  }
}	  
}

$mydatabase->close_connect();
?>

</TR>
</table>
</td>
<td width=99% background=images/user_nav.gif align=right><a href=http://www.craftysyntax.com/livehelp/?v=<?= $version ?> target=_blank><img src=livehelp.gif width=141 height=13 border=0></a></td>
</tr>
</table>
</body>
</html>