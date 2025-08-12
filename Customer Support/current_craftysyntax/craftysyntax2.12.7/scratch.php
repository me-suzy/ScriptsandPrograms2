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


// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];

// update scratch
if(isset($UNTRUSTED['new_scratch_space'])){
  $query = "UPDATE livehelp_config 
            SET scratch_space='".filter_sql($UNTRUSTED['new_scratch_space'])."'";
  $mydatabase->query($query);  
  $CSLH_Config['scratch_space'] = $UNTRUSTED['new_scratch_space'];
}

?>
<SCRIPT>
function adminonly(){
	 alert("You must be logged in with Admin rights in order to change/view security settings");
}
</SCRIPT>
<body bgcolor=<?php echo $color_background;?> marginheight=0 marginwidth=0 topmargin=0>
<center>
<?php 
if(!(isset($UNTRUSTED['editbox']))){
  ?>
<?php
   // Display any Security Warnings, Updates, Notices etc..
   // ! * Do not remove this block of code * !. 
   // There are Security holes discovered
   // every day in Open source programs and not knowing about them
   // could be fatal to your website so this is *VERY* important:
   if(empty($CSLH_Config['directoryid'])) 
     $CSLH_Config['directoryid'] = 0;
   if($isadminsetting != "Y")
     print "<a href=\"javascript:adminonly()\">";  
   else
     print "<a href=http://security.craftysyntax.com/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " target=_blank>";

   print "<img src=http://security.craftysyntax.com/?p=scratch&randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " name=security border=0></a><br>";
}
?>
<br>
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=500 background=images/dotted.gif><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
<tr>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=500 bgcolor=FFFFFF>
<?php 
if(isset($UNTRUSTED['editbox'])){
  ?>
<form action=scratch.php name=chatter method=post>
<input type=hidden name=whattodo value=update_scratch_space>
<textarea cols=65 rows=20 ID="Content" name=new_scratch_space><?php echo $CSLH_Config['scratch_space']; ?></textarea><br><br>
<input type=submit value=UPDATE>
</FORM>
  <?php
} else {
   // show edit button:
   if ( ($isadminsetting!="R") && ($isadminsetting!="L") ) 
     print "<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td align=right><a href=scratch.php?editbox=1><img src=images/editbox.gif width=34 height=19 border=0></a></td></tr></table>";
   

   // Display Scratch space from configuration table.
   print $CSLH_Config['scratch_space'];
} ?>
</td>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
<tr>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=500 background=images/dotted.gif><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=1 height=1><img src=images/blank.gif width=1 height=1 border=0><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
</table>