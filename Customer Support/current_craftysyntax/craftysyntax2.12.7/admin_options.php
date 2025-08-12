<?php 
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2005 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
// --------------------------------------------------------------------------  
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , skinable version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released! 
//===========================================================================
require_once("admin_common.php");
validate_session($identity);
  
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
?>
<html>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 bgcolor=FFFFC9>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width=100%>
<tr>
<td width=98% background=images/<?php echo $CSLH_Config['colorscheme'];?>/top_trim.gif><img src=images/blank.gif width=443 height=10 border=0></td>
<td width=2% rowspan=2 valign=top><a href=http://www.craftysyntax.com target=_blank><img src=images/<?php echo $CSLH_Config['colorscheme'];?>/version.gif width=267 height=32 border=0></a></td>
</tr>
<tr><td width=98% align=left  background=images/<?php echo $CSLH_Config['colorscheme'];?>/bk.gif>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 background=images/<?php echo $CSLH_Config['colorscheme'];?>/bk.gif>
<TR ALIGN=CENTER>
<td width=7><img src=images/blank.gif width=7 height=10></td>
<?php 
$tabshown = 0;
if($UNTRUSTED['tab'] == "live"){ ?>
<td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab8.gif><A HREF="live.php" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>
<TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="live.php" target=_top class="tabNav"><?php echo $lang['livehelp']; ?></A></TD>
  <?php if ($isadminsetting == "L"){  ?>
  	<td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab7.gif><img src=images/blank.gif width=14 height=20 border=0></td>
  <?php } else { ?>	
    <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab4.gif><A HREF="live.php" target=_top><img src=images/blank.gif width=19 height=20 border=0></a></td>
  <?php }
     $tabshown = 1;
   } else { ?>
<td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab1.gif><A HREF="live.php" target=_top><img src=images/blank.gif width=14 height=20 border=0></a></td>
<TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="live.php" target=_top  class="tabNav"><?php echo $lang['livehelp']; ?></A></TD>
  <?php 
    if ($isadminsetting == "L"){  ?>
  	  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab10.gif><img src=images/blank.gif width=14 height=20 border=0></td>
<?php } 

  }  
if ( ($isadminsetting!="R") && ($isadminsetting!="L") ) {
  if($UNTRUSTED['tab'] == "oper"){ ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=operators.php&tab=oper" target=_top  class="tabNav"><?php echo $lang['operators']; ?></A></TD>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
 <?php 
 $tabshown = 1;
 } else {
 ?>
   <?php if ($tabshown == 0) { ?><td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><?php } ?>
   <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=operators.php&tab=oper" target=_top  class="tabNav"><?php echo $lang['operators']; ?></A></TD>
 <?php 
 $tabshown = 0;
 }
} ?>
<?php 
if ( ($isadminsetting!="R") && ($isadminsetting!="L") ) {
  if($UNTRUSTED['tab'] == "dept"){ ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=departments.php&tab=dept" target=_top  class="tabNav"><?php echo $lang['dept']; ?></A></TD>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
 <?php 
 $tabshown = 1;
 } else {
 ?>
   <?php if ($tabshown == 0) { ?><td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><?php } ?>
   <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=departments.php&tab=dept" target=_top  class="tabNav"><?php echo $lang['dept']; ?></A></TD>
 <?php
 $tabshown = 0;
 } 
} ?>
<?php 
 if ($isadminsetting=="Y") {
    if($UNTRUSTED['tab'] == "settings"){ ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=mastersettings.php&tab=settings" target=_top class="tabNav"><?php echo $lang['settings']; ?></A></TD>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
 <?php 
 $tabshown = 1;
 } else {
 ?>
  <?php if ($tabshown == 0) { ?><td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><?php } ?>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=mastersettings.php&tab=settings" target=_top class="tabNav"><?php echo $lang['settings']; ?></A></TD>
 <?php 
 $tabshown = 0;
 }
} 
 
if ($isadminsetting!="L") {
	 if($UNTRUSTED['tab'] == "data"){ ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=data.php&tab=data" target=_top class="tabNav"><?php echo $lang['data']; ?></A></TD>
  <?php if ($isadminsetting != "Y"){  ?>
  	<td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab7.gif><img src=images/blank.gif width=14 height=20 border=0></td>
  <?php } else { ?>
    <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab4.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <?php  
  }
 $tabshown = 1;
 } else {
 ?>
   <?php if ($tabshown == 0) { ?><td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><?php } ?>
   <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=data.php&tab=data" target=_top class="tabNav"><?php echo $lang['data']; ?></A></TD>
  <?php if ($isadminsetting != "Y"){  ?>
    <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab10.gif><img src=images/blank.gif width=14 height=20 border=0></td>
  <?php }
 $tabshown = 0;
 } 
}?>
<?php 
 if ($isadminsetting=="Y") {
 if($UNTRUSTED['tab'] == "tabs"){ ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab11.gif><img src=images/blank.gif width=19 height=20 border=0></td>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab5.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=modules.php&tab=tabs" target=_top class="tabNav"><?php echo $lang['tabs']; ?></A></TD>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab7.gif><img src=images/blank.gif width=14 height=20 border=0></td>
<?php 
$tabshown = 1;
} else {
?>
  <?php if ($tabshown == 0) { ?>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab3.gif><img src=images/blank.gif width=20 height=20 border=0></td><?php } ?>
  <TD background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab2.gif NOWRAP=NOWRAP NOWRAP><A HREF="admin.php?page=modules.php&tab=tabs" target=_top class="tabNav"><?php echo $lang['tabs']; ?></A></TD>
  <td background=images/<?php echo $CSLH_Config['colorscheme'];?>/tab10.gif><img src=images/blank.gif width=14 height=20 border=0></td>
<?php 
$tabshown = 0;
} 
}
?>

</TR>
</table>
</td></tr>
<tr>
<td align=right width=1% NOWARP=NOWRAP colspan=2 background=images/<?php echo $CSLH_Config['colorscheme'];?>/bk.gif>
<table cellpadding=0 cellspacing=0 border=0>
<td width=99% background=images/<?php echo $CSLH_Config['colorscheme'];?>/nav_bot.gif><img src=images/blank.gif height=25 width=43 border=0></td>
<td align=right>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<td NOWRAP=NOWRAP><a href=admin.php target=_top><img src=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_1.gif width=56 height=26 border=0></a></td>
<td  NOWRAP=NOWRAP Valign=top background=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_2.gif><a href=admin.php target=_top class="adminnavtop"><?php echo $lang['over']; ?></a>&nbsp;</td>
<td><a href="admin.php?page=help.php" target="_top"><img src=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_3.gif width=63 height=26 border=0></a></td>
<td  NOWRAP=NOWRAP Valign=top  background=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_4.gif><a href="admin.php?page=help.php" target="_top"  class="adminnavtop"><?php echo $lang['hlp']; ?></a>&nbsp;</td>
<td><a href="logout.php" target="_top"><img src=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_5.gif width=48 height=26 border=0></a></td>
<td NOWRAP=NOWRAP Valign=top  background=images/<?php echo $CSLH_Config['colorscheme'];?>/toplinks_4.gif><a href="logout.php" target="_top" class="adminnavtop"><?php echo $lang['exit']; ?></a>&nbsp;</td>
</tr></table>
</td>
</td>
</tr>
</table>
</td></tr>
</table> 

</body>
</html>
<?php
$mydatabase->close_connect();
?>