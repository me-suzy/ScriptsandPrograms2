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

$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
$isadminsetting = $row[isadmin];
if($isadminsetting != "Y"){
 print "sorry you do not have access to this... ";
$mydatabase->close_connect();
 exit;	
}

if($action == "update"){
   $lastchar = substr($newwebpath,-1);
   if($lastchar != "/"){ $newwebpath .= "/"; }
  
    $query = "UPDATE livehelp_config SET speaklanguage='$newspeaklanguage',webpath='$newwebpath',show_typing='$newshow_typing',offset='$newoffset',use_flush='$newuse_flush'";
    $mydatabase->sql_query($query);
    print "<font color=007700 size=+2>DATABASE UPDATED.. </font>";  
$mydatabase->close_connect();
    exit;  
}
?>
<body bgcolor=FFFFEE><center>
<?
$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->select($query);
$data = $data[0];
$offset = $data[offset];

if($data[use_flush] == "YES"){
$continuous = " SELECTED ";
$refresh = "";
} else {
$continuous = "";
$refresh = " SELECTED ";	
}

if($data[show_typing] == "N"){
  $typing_n = " CHECKED ";	 
} else {
  $typing_y = " CHECKED ";
}

?>
<table width=600<tr bgcolor=DDDDDD><td>
<b>Config settings:</b></td></tr>
<tr bgcolor=FFFFFF><td>
<form action=mastersettings.php method=post>
<input type=hidden name=action value=update>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Full Http path to live help:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
This is the FULL http:// path on the web to live help.. 
This path needs to be something like 
http://www.yourpath.com/livehelp/
</td></tr></table>
<b>web path:</b><input type=text name=newwebpath value="<?= $webpath ?>" size=55><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Language:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
The Language the CSLH should be in.
</td></tr></table>
<b>Language:</b>
<select name=newspeaklanguage>
<option value=eng>English </option>
<option value=frn <? if ($speaklanguage == "frn"){ print " SELECTED "; } ?> >French </option>
<option value=ger <? if ($speaklanguage == "ger"){ print " SELECTED "; } ?> >German </option>
<option value=ita <? if ($speaklanguage == "ita"){ print " SELECTED "; } ?> >Italian </option>
<option value=por <? if ($speaklanguage == "por"){ print " SELECTED "; } ?> >Portuguese</option>
<option value=spn <? if ($speaklanguage == "spn"){ print " SELECTED "; } ?> >Spanish</option>
</select>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Chat Type:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
The Chat type is the way that the program 
refreshes or does not refresh the chat. Some
Servers will not send the buffer for the chat 
until the page is fully loaded so the program 
will not show anything in Continuous mode. 
</td></tr></table>
<b>Chat Type:</b><select name=newuse_flush>
<option value=no <?= $refresh ?> > Refresh</option>
<option value=YES <?= $continuous ?> > Continuous </option>
</select><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Time Offset:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
The Time Offset is the difference in Hours 
between the time on the server and the time 
you want to see on your website. 
</td></tr></table>
<b>Time Offset:</b><select name=newoffset>
<option value=<?= $offset ?>><?= $offset ?></option>
<option value=<?= $offset ?>>---</option>
<? for($i=-12;$i<13; $i++){ 
print "<option value=$i>$i</option>\n";
} 
?>
</select><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Live help title:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
This is the title of the live help.
</td></tr></table>
<b>Live help title:</b><input type=text name=newsite_title value="<?= $site_title ?>"><br>
<table width=100% bgcolor=FFFFC0><tr><td>
<b>Is Typing.. message:</b>
</td></tr></table>
<table width=100% bgcolor=FFFFEE><tr><td>
To add a little notice that reads "user is typing.." when the user
or the operator is
typing 
</td></tr></table>
<b>Enable is typing:</b> <input type=radio value=Y name=newshow_typing <?=$typing_y?> > YES &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio value=N name=newshow_typing <?=$typing_n?> > NO<br>
<input type=submit value=update>
</body>
<?
$mydatabase->close_connect();
?></td></tr></table>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>