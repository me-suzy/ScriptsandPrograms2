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
$isadminsetting = $people['isadmin'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 

?>
<body bgcolor=<?php echo $color_alt3; ?>>
<center>
<SCRIPT>
function openwindow(url){ 
 window.open(url, 'chat54057', 'width=572,height=320,menubar=no,scrollbars=1,resizable=1');
}
</SCRIPT>
<SCRIPT>

r_arrow = new Image;
h_arrow = new Image;
r_arrow.src = 'images/arrow_off.gif';
h_arrow.src = 'images/arrow_on.gif';

</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" > 
<table border=0 cellpadding=0 cellspacing=0 width=200>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=<?php echo $color_alt4; ?> align=center> <b>Crafty Syntax Live Help:</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?> align=center><font color=000066> Version <b><?php echo $CSLH_Config['version'];?></b></font><br></td></tr>
<tr><td bgcolor=<?php echo $color_background;?>> <b><?php echo $lang['general']; ?>:</b></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i1><a class="adminnavleft" onmouseout="document.i1.src=r_arrow.src"  onmouseover="document.i1.src=h_arrow.src" href=http://www.craftysyntax.com/remote/docs/ target=_blank><?php echo $lang['documentation']; ?></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i2><a class="adminnavleft" onmouseout="document.i2.src=r_arrow.src"  onmouseover="document.i2.src=h_arrow.src" href=admin.php?page=help.php target=_top><?php echo $lang['txt88']; ?></a></td></tr>	
<?php if ($isadminsetting=="Y" ) { ?>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i3><a class="adminnavleft" onmouseout="document.i3.src=r_arrow.src"  onmouseover="document.i3.src=h_arrow.src" href=http://www.craftysyntax.com/remote/updates.php  target=_blank><?php echo $lang['txt89']; ?></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i4><a class="adminnavleft" onmouseout="document.i4.src=r_arrow.src"  onmouseover="document.i4.src=h_arrow.src" href=admin.php?page=mastersettings.php&tab=settings target=_top ><?php echo $lang['txt90']; ?></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i4a><a class="adminnavleft" onmouseout="document.i4a.src=r_arrow.src"  onmouseover="document.i4a.src=h_arrow.src" href=registerit.php target=contents >Security Registration</a></td></tr>
<?php } ?>
<tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<tr><td bgcolor=<?php echo $color_background;?>> <b><?php echo $lang['livehelp']; ?>:</b></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i5><a class="adminnavleft" onmouseout="document.i5.src=r_arrow.src"  onmouseover="document.i5.src=h_arrow.src" href=live.php target=_top ><font color=007700><?php echo $lang['txt91']; ?></font></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i6><a class="adminnavleft" onmouseout="document.i6.src=r_arrow.src"  onmouseover="document.i6.src=h_arrow.src" href="javascript:openwindow('edit_quick.php')" ><?php echo $lang['txt32']; ?></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i7><a class="adminnavleft" onmouseout="document.i7.src=r_arrow.src"  onmouseover="document.i7.src=h_arrow.src" href="javascript:openwindow('edit_quick.php?typeof=IMAGE')" ><?php echo $lang['txt30']; ?></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i8><a class="adminnavleft" onmouseout="document.i8.src=r_arrow.src"  onmouseover="document.i8.src=h_arrow.src" href="javascript:openwindow('edit_quick.php?typeof=URL')" ><?php echo $lang['txt28']; ?></a></td></tr>
<?php if ($isadminsetting=="Y" ) { ?>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i8a><a class="adminnavleft" onmouseout="document.i8a.src=r_arrow.src"  onmouseover="document.i8a.src=h_arrow.src" href="admin.php?page=edit_smile.php&tab=settings" target=_top>Emotion Icons</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i8b><a class="adminnavleft" onmouseout="document.i8b.src=r_arrow.src"  onmouseover="document.i8b.src=h_arrow.src" href="admin.php?page=edit_layer.php&tab=settings" target=_top>Edit Layer Images</a></td></tr>
<?php } ?>

<tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<tr><td bgcolor=<?php echo $color_background;?>> <b><?php echo $lang['operators']; ?>:</b></td></tr>
<?php if ($isadminsetting=="Y" ) { ?>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i9><a class="adminnavleft" onmouseout="document.i9.src=r_arrow.src"  onmouseover="document.i9.src=h_arrow.src" href=admin.php?page=operators.php&tab=oper target=_top ><?php echo $lang['CREATE']; ?>/<?php echo $lang['EDIT']; ?>/<?php echo $lang['DELETE']; ?></a></td></tr>
<?php } ?>	
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i9a><a class="adminnavleft" onmouseout="document.i9a.src=r_arrow.src"  onmouseover="document.i9a.src=h_arrow.src" href=operators.php?editit=<?php echo $myid ?> target=contents ><?php echo $lang['EDIT']; ?> Your account</a></td></tr>

<tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<?php if( ($isadminsetting == "Y") || ($isadminsetting == "N") || ($isadminsetting == "R") ){ ?>
	
 <?php if ( ($isadminsetting == "Y") || ($isadminsetting == "N") ){  ?>
 <tr><td bgcolor=<?php echo $color_background;?>> <b><?php echo $lang['dept']; ?>:</b></td></tr>
 <?php if ($isadminsetting=="Y" ) { ?>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i10><a class="adminnavleft" onmouseout="document.i10.src=r_arrow.src"  onmouseover="document.i10.src=h_arrow.src" href=admin.php?page=departments.php&tab=dept target=_top ><?php echo $lang['CREATE']; ?>/<?php echo $lang['EDIT']; ?>/<?php echo $lang['DELETE']; ?></a></td></tr>
 <?php } ?>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i11><a class="adminnavleft" onmouseout="document.i11.src=r_arrow.src"  onmouseover="document.i11.src=h_arrow.src" href=admin.php?page=departments.php&tab=dept&help=1 target=_top >HTML CODE for <?php echo $lang['dept']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<?php } ?> 

 <tr><td bgcolor=<?php echo $color_background;?>> <b><?php echo $lang['data']; ?></b></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12a><a class="adminnavleft" onmouseout="document.i12a.src=r_arrow.src"  onmouseover="document.i12a.src=h_arrow.src" href=data.php?tab=0 target=contents ><?php echo $lang['txt100']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12b><a class="adminnavleft" onmouseout="document.i12b.src=r_arrow.src"  onmouseover="document.i12b.src=h_arrow.src" href=data.php?tab=1 target=contents >Messages</a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12c><a class="adminnavleft" onmouseout="document.i12c.src=r_arrow.src"  onmouseover="document.i12c.src=h_arrow.src" href=data.php?tab=2 target=contents ><?php echo $lang['txt98']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12d><a class="adminnavleft" onmouseout="document.i12d.src=r_arrow.src"  onmouseover="document.i12d.src=h_arrow.src" href=data.php?tab=3 target=contents ><?php echo $lang['txt99']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12e><a class="adminnavleft" onmouseout="document.i12e.src=r_arrow.src"  onmouseover="document.i12e.src=h_arrow.src" href=data.php?tab=4 target=contents ><?php echo $lang['keywords']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i12f><a class="adminnavleft" onmouseout="document.i12f.src=r_arrow.src"  onmouseover="document.i12f.src=h_arrow.src" href=data.php?tab=5 target=contents ><?php echo $lang['users']; ?></a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
 
 <tr><td bgcolor=<?php echo $color_background;?>> <b>Modules</b>:</td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i13><a class="adminnavleft" onmouseout="document.i13.src=r_arrow.src"  onmouseover="document.i13.src=h_arrow.src" href=qa.php target=contents >Questions & Answers</a></td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<?php } ?> 
 <tr><td bgcolor=<?php echo $color_background;?>> <b>Extras:</b>:</td></tr>
 <?php if($CSLH_Config['showgames']=="Y"){ ?>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i14><a class="adminnavleft" onmouseout="document.i14.src=r_arrow.src"  onmouseover="document.i14.src=h_arrow.src" href=http://games.craftysyntax.com/ target=contents>View Games</a></td></tr>
 <?php } ?>
 <?php if($CSLH_Config['showdirectory']=="Y"){ ?> 
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i14a><a class="adminnavleft" onmouseout="document.i14a.src=r_arrow.src"  onmouseover="document.i14a.src=h_arrow.src" href=http://directory.craftysyntax.com/ target=contents>View Directory</a></td></tr>
 <?php } ?>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
 <tr><td bgcolor=<?php echo $color_background;?>> <b>Additional Information</b>:</td></tr>
 <tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i15a><a class="adminnavleft" onmouseout="document.i15a.src=r_arrow.src"  onmouseover="document.i15a.src=h_arrow.src" href=http://www.craftysyntax.com/remote/updates.php#donate  target=_blank  ><B>Donations to the project</b></a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i17b><a class="adminnavleft" onmouseout="document.i17b.src=r_arrow.src"  onmouseover="document.i17b.src=h_arrow.src" href=README_FILES/CHANGELOG.txt target=contents >Change Log (version <?php echo $CSLH_Config['version']; ?>)</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i17c><a class="adminnavleft" onmouseout="document.i17c.src=r_arrow.src"  onmouseover="document.i17c.src=h_arrow.src" href=http://www.craftysyntax.com/remote/version3.php target=contents >VERSION 3.1.X !</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i17d><a class="adminnavleft" onmouseout="document.i17d.src=r_arrow.src"  onmouseover="document.i17d.src=h_arrow.src" href=http://www.craftysyntax.com/remote/todo.php target=contents >Current TODO LIST</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i17e><a class="adminnavleft" onmouseout="document.i17e.src=r_arrow.src"  onmouseover="document.i17e.src=h_arrow.src" href=http://www.craftysyntax.com/remote/bugs.php target=contents >Bugs/Security Holes</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i17f><a class="adminnavleft" onmouseout="document.i17f.src=r_arrow.src"  onmouseover="document.i17f.src=h_arrow.src" href=http://www.craftysyntax.com/remote/cvs.php target=contents >CVS </a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i16g><a class="adminnavleft" onmouseout="document.i16g.src=r_arrow.src"  onmouseover="document.i16g.src=h_arrow.src" href=http://www.craftysyntax.com/projects/ target=_blank >Additional Programs</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/arrow_off.gif width=20 height=12 border=0 name=i19h><a class="adminnavleft" onmouseout="document.i19h.src=r_arrow.src"  onmouseover="document.i19h.src=h_arrow.src" href=README_FILES/gpl.htm target=contents >License</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=<?php echo $color_alt4; ?> align=center> <b>&nbsp;</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td bgcolor=<?php echo $color_alt3; ?>><br></td></tr>
</table>
<font size=-2><center>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a class="adminnavleft" href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a><br> &copy; 2003 - 2005  <a class="adminnavleft" href=http://www.craftysyntax.com/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <br><a class="adminnavleft" href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>
</font></center>
</font><br><br><br>
</body>
<?php
$mydatabase->close_connect();
?>