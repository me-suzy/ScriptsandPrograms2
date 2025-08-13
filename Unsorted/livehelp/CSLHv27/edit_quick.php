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

$timeof = date("YmdHis");

checkuser();

if($myid == ""){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}


if($what == "save"){      
      $comment = addslashes($comment);
      $comment = ereg_replace("\r\n","",$comment);
      $comment = ereg_replace("\n","",$comment);  
   if($editid != ""){ 
      $query = "UPDATE livehelp_quick set name='$notename',message='$comment' WHERE id='$editid'";	
      $messages = $mydatabase->sql_query($query);	
   } else {
      $query = "INSERT INTO livehelp_quick (name,message,typeof) VALUES ('$notename','$comment','$typeof')";	
      $mydatabase->insert($query);
   }
   $what = "";
   $quicknote = "";
}

if($what == "remove"){
   $query = "DELETE FROM livehelp_quick WHERE id='$editid' ";
   $mydatabase->sql_query($query);
}


?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body bgcolor=E0E8F0>
<table width=100%><tr><td align=left>
<?
if($typeof==""){
 print "<h2>Edit Quick Notes:</h2>";
 $what = " typeof!='URL' AND typeof!='IMAGE' ";	
}
if($typeof=="URL"){
 print "<h2>Edit Push Urls:</h2>";	
 $what = " typeof='URL' ";	
}
if($typeof=="IMAGE"){
 print "<h2>Edit Images:</h2>";	
 $what = " typeof='IMAGE' ";	
}
?>
</td><td align=right>
<a href=javascript:window.close()>CLOSE</a>
</td></tr></table>
<table width=555>
<tr bgcolor=DDDDDD><td><b>Title</b></td><td>Visiblity</td><td>Options</td></tr>
<?


if ($action == ""){
  $query = "SELECT * FROM livehelp_quick WHERE $what ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
   $row = $result[$j];
   $in= $j + 1;
   print "<tr bgcolor=$bgcolor><td><b>$row[name]</b></td><td>Global</td><td><a href=edit_quick.php?typeof=$typeof&action=edit&id=$row[id]>Edit</a></td></tr>\n";	
  } 
?>
</table><br>
<a href=edit_quick.php?action=edit&typeof=<?= $typeof ?> >Create a new row</a>
<SCRIPT>
window.focus();
</SCRIPT>
<?
} else {
  $query = "SELECT * FROM livehelp_quick WHERE id='$id' ";
  $result = $mydatabase->select($query);
  $result = $result[0];
?>
<table width=100% bgcolor=FFFFC0><tr><td><b>EDIT/ADD :</b></td></tr></table>
<form action=edit_quick.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=alt_what value="">
<input type=hidden name=typeof value="<?= $typeof ?>">
<input type=hidden name=timeof value=<?= $timeof ?> >
<input type=hidden name=editid value="<?= $result[id] ?>">
<b>Name:</b><input type=text name=notename value="<?= $result[name] ?>"><br>
<? if($typeof == ""){ ?>
Message:
<textarea cols=40 rows=2 name=comment>
<?= $result[message] ?>
</textarea><br>
<? } else { ?>
<b>Path:</b><input type=text size=60 name=comment value="<?= $result[message] ?>"><br>
<? } ?>
<input type=submit name=what value=save><input type=submit name=what value=remove>
</form>
<? }?>
</body>
<?
$mydatabase->close_connect();
?>